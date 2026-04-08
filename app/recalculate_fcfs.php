<?php
/**
 * Script untuk mengisi field FCFS yang kosong di production
 * Jalankan dengan: php artisan tinker < recalculate_fcfs.php
 * Atau langsung: php recalculate_fcfs.php (jika ada autoload)
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use Carbon\Carbon;

echo "=== Recalculating FCFS fields for all confirmed reservations ===\n\n";

// Get all confirmed reservations that need FCFS calculation
$reservations = Reservation::where('status', 'confirmed')
    ->whereNotNull('visit_time')
    ->orderBy('reservation_date', 'asc')
    ->orderBy('visit_time', 'asc')
    ->get();

echo "Found " . $reservations->count() . " confirmed reservations\n\n";

// Group by date
$byDate = $reservations->groupBy(function($r) {
    return $r->reservation_date->format('Y-m-d');
});

$updated = 0;

foreach ($byDate as $date => $dailyReservations) {
    echo "Processing date: $date (" . $dailyReservations->count() . " reservations)\n";
    
    $position = 1;
    $lastEndTime = null;
    
    foreach ($dailyReservations->sortBy('visit_time') as $reservation) {
        $visitTime = Carbon::parse($reservation->visit_time);
        $duration = $reservation->duration_minutes ?? 120;
        
        // Calculate start time (based on visit_time)
        $startTime = $visitTime;
        
        // Calculate waiting time
        $waitingTime = 0;
        if ($position > 1 && $lastEndTime) {
            // If there's a previous reservation, calculate wait
            $diffMinutes = $startTime->diffInMinutes($lastEndTime, false);
            if ($diffMinutes > 0) {
                $waitingTime = $diffMinutes;
            }
        }
        
        // Calculate end time
        $endTime = $startTime->copy()->addMinutes($duration);
        
        // Calculate TAT (Turnaround Time) = Waiting Time + Duration
        $tat = $waitingTime + $duration;
        
        // Update reservation
        $reservation->update([
            'queue_position' => $position,
            'start_time' => $startTime,
            'completion_time' => $endTime,
            'waiting_time' => $waitingTime,
            'turnaround_time' => $tat,
            'burst_time' => $duration,
        ]);
        
        echo "  - #{$reservation->id}: Position $position, Wait {$waitingTime}m, TAT {$tat}m, Start " . $startTime->format('H:i') . "\n";
        
        $lastEndTime = $endTime;
        $position++;
        $updated++;
    }
    
    echo "\n";
}

echo "=== Done! Updated $updated reservations ===\n";
