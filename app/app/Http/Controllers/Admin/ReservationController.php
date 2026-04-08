<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use App\Mail\ReservationApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::orderByDesc('reservation_date')->paginate(20);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,rejected',
            'visit_time' => 'nullable',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $reservation->status;
        $newStatus = $validated['status'];
        
        $reservation->status = $newStatus;
        
        // Handle rejection reason
        if ($newStatus === 'rejected') {
            $reservation->rejection_reason = $request->input('rejection_reason');
        } else {
            // Clear rejection reason if status changed to confirmed/pending
            $reservation->rejection_reason = null;
        }
        
        // Handle visit_time - sanitize input and auto-calculate end_time
        if ($request->filled('visit_time')) {
            $timeInput = $request->input('visit_time');
            
            // Convert to 24-hour format if needed
            try {
                $parsedTime = \Carbon\Carbon::parse($timeInput);
                $reservation->visit_time = $parsedTime->format('H:i:s');
                
                // Auto-calculate end_time (2 hours after start)
                $reservation->end_time = $parsedTime->addHours(2)->format('H:i:s');
            } catch (\Exception $e) {
                // If parsing fails, store as-is
                $reservation->visit_time = $timeInput;
            }
        }
        
        // Set arrival_time jika belum ada (untuk reservasi lama sebelum fitur FCFS)
        if (!$reservation->arrival_time) {
            $reservation->arrival_time = $reservation->created_at ?? now();
            Log::info("Set arrival_time for reservation #{$reservation->id} (legacy data)");
        }
        
        // Set burst_time jika belum ada
        if (!$reservation->burst_time) {
            $reservation->burst_time = $reservation->duration_minutes ?? 120;
        }
        
        $reservation->save();

        // Trigger FCFS processing whenever status is confirmed
        if ($newStatus === 'confirmed') {
            try {
                $fcfsScheduler = new \App\Services\FCFSScheduler();
                $fcfsScheduler->processQueue($reservation->reservation_date);
                
                // Reload reservation untuk mendapatkan FCFS metrics yang baru dihitung
                $reservation->refresh();
                
                Log::info("FCFS queue processed for date: {$reservation->reservation_date}");
            } catch (\Exception $e) {
                Log::error("FCFS processing failed: " . $e->getMessage());
                // Don't fail the status update if FCFS processing fails
            }
            
            // Auto-create MobileLibrarySchedule entry if visit_time is set
            if ($reservation->visit_time) {
                try {
                    // Check if schedule already exists for this reservation
                    $existingSchedule = MobileLibrarySchedule::where('reservation_id', $reservation->id)->first();
                    
                    if (!$existingSchedule) {
                        // Parse datetime correctly - use only date part from reservation_date
                        $dateOnly = Carbon::parse($reservation->reservation_date)->format('Y-m-d');
                        $startTime = Carbon::parse($dateOnly . ' ' . $reservation->visit_time);
                        $duration = $reservation->duration_minutes ?: 120; // Default 2 hours
                        $endTime = (clone $startTime)->addMinutes($duration);
                        
                        MobileLibrarySchedule::create([
                            'reservation_id' => $reservation->id,
                            'scheduled_date' => $reservation->reservation_date,
                            'start_time' => $startTime->format('Y-m-d H:i:s'),
                            'end_time' => $endTime->format('Y-m-d H:i:s'),
                        ]);
                        
                        Log::info("Auto-created MobileLibrarySchedule for reservation #{$reservation->id}");
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to create MobileLibrarySchedule: " . $e->getMessage());
                    // Don't fail the status update if schedule creation fails
                }
            }
        }

        // Kirim email berdasarkan status
        $emailSent = false;
        $emailError = null;
        
        if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
            if (empty($reservation->email)) {
                Log::warning("Reservation #{$reservation->id} has no email, skipping notification");
                $emailError = 'Email tidak tersedia untuk reservasi ini';
            } else {
                try {
                    Mail::to($reservation->email)->send(new ReservationApproved($reservation));
                    
                    // Force flush mail to ensure it's sent immediately
                    if (method_exists(Mail::getFacadeRoot(), 'getSwiftMailer')) {
                        Mail::getSwiftMailer()->getTransport()->stop();
                    }
                    
                    $emailSent = true;
                    Log::info("Approval email sent to {$reservation->email} for reservation #{$reservation->id}");
                } catch (\Exception $e) {
                    Log::error('Failed to send reservation approval email: ' . $e->getMessage());
                    Log::error('Email: ' . $reservation->email . ', Reservation ID: ' . $reservation->id);
                    $emailError = $e->getMessage();
                }
            }
        } elseif ($newStatus === 'rejected' && $oldStatus !== 'rejected') {
            if (empty($reservation->email)) {
                Log::warning("Reservation #{$reservation->id} has no email, skipping rejection notification");
                $emailError = 'Email tidak tersedia untuk reservasi ini';
            } else {
                try {
                    Mail::to($reservation->email)->send(new \App\Mail\ReservationRejected($reservation));
                    
                    // Force flush mail to ensure it's sent immediately
                    if (method_exists(Mail::getFacadeRoot(), 'getSwiftMailer')) {
                        Mail::getSwiftMailer()->getTransport()->stop();
                    }
                    
                    $emailSent = true;
                    Log::info("Rejection email sent to {$reservation->email} for reservation #{$reservation->id}");
                } catch (\Exception $e) {
                    Log::error('Failed to send reservation rejection email: ' . $e->getMessage());
                    Log::error('Email: ' . $reservation->email . ', Reservation ID: ' . $reservation->id);
                    $emailError = $e->getMessage();
                }
            }
        }

        // Pesan sukses dengan info email
        $message = 'Status reservasi berhasil diperbarui';
        if ($newStatus === 'confirmed' || $newStatus === 'rejected') {
            if ($emailSent) {
                $statusText = $newStatus === 'confirmed' ? 'persetujuan' : 'penolakan';
                $message .= ' dan email ' . $statusText . ' telah dikirim ke ' . $reservation->email;
            } elseif ($emailError) {
                if (str_contains($emailError, '421') || str_contains($emailError, 'too many connections')) {
                    $message .= '. ⚠️ Email gagal terkirim (Gmail limit reached). Tunggu 10 menit lalu coba lagi atau gunakan email queue.';
                } else {
                    $message .= '. ⚠️ Email gagal terkirim: ' . substr($emailError, 0, 100);
                }
            }
        }

        return redirect()->route('admin.reservations.index')->with('success', $message);
    }
}
