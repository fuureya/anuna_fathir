<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use Carbon\Carbon;

echo "=== Auto-Generate Mobile Library Schedules ===\n\n";

// Get all confirmed reservations
$reservations = Reservation::where('status', 'confirmed')
    ->whereNotNull('visit_time')
    ->orderBy('reservation_date')
    ->orderBy('visit_time')
    ->get();

echo "📋 Found {$reservations->count()} confirmed reservations\n\n";

if ($reservations->count() === 0) {
    echo "❌ No confirmed reservations to generate schedules from.\n";
    exit(0);
}

// Group by date
$byDate = $reservations->groupBy(function($r) {
    return Carbon::parse($r->reservation_date)->format('Y-m-d');
});

$totalGenerated = 0;

foreach ($byDate as $date => $dateReservations) {
    echo "🗓️  Processing date: {$date}\n";
    
    // Delete existing schedules for this date
    $deleted = MobileLibrarySchedule::whereDate('scheduled_date', $date)->delete();
    if ($deleted > 0) {
        echo "   ⚠️  Deleted {$deleted} existing schedules\n";
    }
    
    // Apply interval scheduling algorithm (greedy by earliest finish time)
    $selected = [];
    $lastEnd = null;
    
    foreach ($dateReservations as $r) {
        try {
            // Parse visit_time
            $timeStr = strlen($r->visit_time) === 5 ? $r->visit_time.':00' : $r->visit_time;
            $start = Carbon::createFromFormat('H:i:s', $timeStr);
            
            // Default 2 jam (120 menit) untuk durasi kunjungan perpustakaan keliling
            $duration = $r->duration_minutes ?: 120;
            $end = (clone $start)->addMinutes($duration);
            
            // Check if this reservation fits (no overlap)
            if ($lastEnd === null || $start->greaterThanOrEqualTo($lastEnd)) {
                $selected[] = [
                    'reservation' => $r,
                    'start' => $start,
                    'end' => $end,
                ];
                $lastEnd = $end;
            }
        } catch (\Exception $e) {
            echo "   ⚠️  Skipped reservation #{$r->id}: Invalid time format ({$r->visit_time})\n";
        }
    }
    
    $selectedCount = count($selected);
    echo "   ✅ Selected {$selectedCount} non-overlapping reservations\n";
    
    // Create schedules
    foreach ($selected as $item) {
        $r = $item['reservation'];
        $start = $item['start'];
        $end = $item['end'];
        
        $schedule = MobileLibrarySchedule::create([
            'reservation_id' => $r->id,
            'scheduled_date' => $date,
            'start_time' => Carbon::parse($date . ' ' . $start->format('H:i:s')),
            'end_time' => Carbon::parse($date . ' ' . $end->format('H:i:s')),
        ]);
        
        echo "      📌 Schedule #{$schedule->id}: {$r->full_name} | {$start->format('H:i')} - {$end->format('H:i')}\n";
        $totalGenerated++;
    }
    
    echo "\n";
}

echo "✅ Successfully generated {$totalGenerated} schedules!\n";
echo "\n👉 Check public schedule: http://localhost:8000/schedule\n";
echo "👉 Check admin schedule: http://localhost:8000/admin/schedule\n";
