<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FCFSScheduler
{
    /**
     * Process FCFS queue for a specific date
     * 
     * @param string|null $date Date to process (Y-m-d format), null for today
     * @return array Statistics about processed reservations
     */
    public function processQueue($date = null)
    {
        $date = $date ?? Carbon::today()->format('Y-m-d');
        
        Log::info("Processing FCFS queue for date: {$date}");
        
        // Get all pending or confirmed reservations for this date
        // Order by arrival_time, fallback to id (older records have lower id)
        $reservations = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderByRaw('COALESCE(arrival_time, NOW()) ASC')
            ->orderBy('id', 'asc')
            ->get();
        
        if ($reservations->isEmpty()) {
            Log::info("No reservations to process for {$date}");
            return [
                'processed' => 0,
                'avg_waiting_time' => 0,
                'avg_turnaround_time' => 0,
                'date' => $date
            ];
        }
        
        $previousCompletionTime = null;
        $totalWaitingTime = 0;
        $totalTurnaroundTime = 0;
        $position = 1;
        
        DB::beginTransaction();
        
        try {
            foreach ($reservations as $reservation) {
                // Calculate FCFS times
                $this->calculateTimes($reservation, $previousCompletionTime);
                
                // Set queue position
                $reservation->queue_position = $position;
                $reservation->save();
                
                // Update previous completion time for next iteration
                $previousCompletionTime = $reservation->completion_time;
                
                // Accumulate statistics
                $totalWaitingTime += $reservation->waiting_time ?? 0;
                $totalTurnaroundTime += $reservation->turnaround_time ?? 0;
                
                $position++;
                
                Log::info("Processed reservation #{$reservation->id}: Queue={$reservation->queue_position}, WT={$reservation->waiting_time}, TAT={$reservation->turnaround_time}");
            }
            
            DB::commit();
            
            $count = $reservations->count();
            $stats = [
                'processed' => $count,
                'avg_waiting_time' => $count > 0 ? round($totalWaitingTime / $count, 2) : 0,
                'avg_turnaround_time' => $count > 0 ? round($totalTurnaroundTime / $count, 2) : 0,
                'date' => $date
            ];
            
            Log::info("FCFS processing complete", $stats);
            
            return $stats;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("FCFS processing failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Calculate FCFS times for a reservation
     * 
     * @param Reservation $reservation
     * @param Carbon|null $previousCompletionTime (kept for backwards compatibility, but no longer used for sequential chaining)
     * @return void
     */
    public function calculateTimes(Reservation $reservation, $previousCompletionTime = null)
    {
        // AT = Arrival Time (when request was submitted)
        // Fallback to reservation_date start of day for legacy reservations without arrival_time
        $arrivalTime = $reservation->arrival_time 
            ? Carbon::parse($reservation->arrival_time)
            : Carbon::parse($reservation->reservation_date)->startOfDay();
        
        // Set arrival_time if it was null (for legacy data)
        if (!$reservation->arrival_time) {
            $reservation->arrival_time = $arrivalTime;
        }
        
        // BT = Burst Time (duration in minutes)
        $burstTime = $reservation->burst_time ?? $reservation->duration_minutes ?? 120;
        $reservation->burst_time = $burstTime;
        
        // RT = Requested Time (user's preferred time)
        $requestedTime = null;
        if ($reservation->visit_time) {
            // Parse reservation_date as date only, then append visit_time
            $dateOnly = Carbon::parse($reservation->reservation_date)->format('Y-m-d');
            $requestedTime = Carbon::parse($dateOnly . ' ' . $reservation->visit_time);
        }
        
        // ST = Start Time
        // FIX: Check if requested time slot actually conflicts with other reservations
        // Only adjust start time if there's a real overlap, not just based on FCFS queue order
        if ($requestedTime) {
            // Start with the user's requested time
            $startTime = $requestedTime->copy();
            
            // Check for actual conflicts with other confirmed reservations on the same date
            $conflictingReservations = Reservation::where('reservation_date', $reservation->reservation_date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('id', '!=', $reservation->id)
                ->whereNotNull('start_time')
                ->whereNotNull('completion_time')
                ->get();
            
            $proposedEnd = $startTime->copy()->addMinutes($burstTime);
            $hasConflict = true;
            $maxIterations = 20;
            $iteration = 0;
            
            while ($hasConflict && $iteration < $maxIterations) {
                $hasConflict = false;
                
                foreach ($conflictingReservations as $existing) {
                    $existingStart = Carbon::parse($existing->start_time);
                    $existingEnd = Carbon::parse($existing->completion_time);
                    
                    // Check if time slots overlap
                    if ($startTime->lt($existingEnd) && $proposedEnd->gt($existingStart)) {
                        // Conflict found - move to after this reservation
                        $startTime = $existingEnd->copy();
                        $proposedEnd = $startTime->copy()->addMinutes($burstTime);
                        $hasConflict = true;
                        Log::info("Conflict detected for reservation #{$reservation->id}, moving start time to {$startTime}");
                        break;
                    }
                }
                
                $iteration++;
            }
        } else {
            // No requested time, use arrival time as fallback
            $startTime = $arrivalTime->copy();
        }
        
        // CT = Completion Time
        // Formula: CT = ST + BT
        $completionTime = $startTime->copy()->addMinutes($burstTime);
        
        // WT = Waiting Time (in minutes)
        // Formula: WT = ST - AT
        $waitingTime = $arrivalTime->diffInMinutes($startTime);
        
        // TAT = Turnaround Time (in minutes)
        // Formula: TAT = CT - AT
        $turnaroundTime = $arrivalTime->diffInMinutes($completionTime);
        
        // Update reservation
        $reservation->start_time = $startTime;
        $reservation->completion_time = $completionTime;
        $reservation->waiting_time = $waitingTime;
        $reservation->turnaround_time = $turnaroundTime;
        
        // Update visit_start and visit_end for compatibility
        $reservation->visit_start = $startTime;
        $reservation->visit_end = $completionTime;
    }
    
    /**
     * Find next available time slot if requested time conflicts
     * 
     * @param string $requestedTime Time in H:i format
     * @param string $date Date in Y-m-d format
     * @param int $duration Duration in minutes
     * @return Carbon Next available start time
     */
    public function findNextAvailableSlot($requestedTime, $date, $duration)
    {
        $requestedDateTime = Carbon::parse($date . ' ' . $requestedTime);
        
        // Get all confirmed reservations for this date with their time slots
        $confirmedReservations = Reservation::where('reservation_date', $date)
            ->where('status', 'confirmed')
            ->whereNotNull('start_time')
            ->whereNotNull('completion_time')
            ->orderBy('start_time', 'asc')
            ->get();
        
        $proposedStart = $requestedDateTime->copy();
        $proposedEnd = $proposedStart->copy()->addMinutes($duration);
        
        // Check for conflicts
        $hasConflict = true;
        $maxIterations = 20; // Prevent infinite loop
        $iteration = 0;
        
        while ($hasConflict && $iteration < $maxIterations) {
            $hasConflict = false;
            
            foreach ($confirmedReservations as $existing) {
                $existingStart = Carbon::parse($existing->start_time);
                $existingEnd = Carbon::parse($existing->completion_time);
                
                // Check if time slots overlap
                if ($proposedStart->lt($existingEnd) && $proposedEnd->gt($existingStart)) {
                    // Conflict found, move to after this reservation
                    $proposedStart = $existingEnd->copy();
                    $proposedEnd = $proposedStart->copy()->addMinutes($duration);
                    $hasConflict = true;
                    break;
                }
            }
            
            $iteration++;
        }
        
        return $proposedStart;
    }
    
    /**
     * Get queue position for a reservation based on arrival time
     * 
     * @param Reservation $reservation
     * @return int Position in queue (1-based)
     */
    public function getQueuePosition(Reservation $reservation)
    {
        if (!$reservation->arrival_time) {
            return 0;
        }
        
        $position = Reservation::where('reservation_date', $reservation->reservation_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNotNull('arrival_time')
            ->where('arrival_time', '<=', $reservation->arrival_time)
            ->count();
        
        return $position;
    }
}
