<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use Carbon\Carbon;

echo "=================================================\n";
echo "DEBUG: Jadwal Public & Admin Issue\n";
echo "=================================================\n\n";

$now = Carbon::now();
echo "Current DateTime: {$now}\n\n";

// Check reservasi baru (24 & 31 Des)
echo "--- CHECKING NEW RESERVATIONS (24 & 31 Des) ---\n";
$decReservations = Reservation::whereIn('reservation_date', ['2025-12-24', '2025-12-31'])
    ->get();

if ($decReservations->count() > 0) {
    foreach ($decReservations as $r) {
        echo "\n✅ Found Reservation:\n";
        echo "   ID: {$r->id}\n";
        echo "   Name: {$r->full_name}\n";
        echo "   Date: {$r->reservation_date}\n";
        echo "   Visit Time: {$r->visit_time}\n";
        echo "   Status: {$r->status}\n";
        
        // Check if MobileLibrarySchedule exists
        $schedule = MobileLibrarySchedule::where('reservation_id', $r->id)->first();
        if ($schedule) {
            echo "   ✅ MobileLibrarySchedule: EXISTS (ID: {$schedule->id})\n";
            echo "      Start: {$schedule->start_time}\n";
            echo "      End: {$schedule->end_time}\n";
        } else {
            echo "   ❌ MobileLibrarySchedule: NOT FOUND\n";
            echo "      → This reservation won't show in jadwal pusling!\n";
        }
    }
} else {
    echo "❌ No reservations found for 24 & 31 December 2025\n";
}

echo "\n\n--- CHECKING ALL MOBILE_LIBRARY_SCHEDULE ---\n";
$allSchedules = MobileLibrarySchedule::with('reservation')->get();
echo "Total Schedules in DB: {$allSchedules->count()}\n\n";

if ($allSchedules->count() > 0) {
    foreach ($allSchedules as $s) {
        $endTime = Carbon::parse($s->end_time);
        $isPast = $endTime->lessThan($now);
        $status = $isPast ? '❌ PAST' : '✅ UPCOMING';
        
        $reservationName = $s->reservation ? $s->reservation->full_name : 'NULL';
        echo "{$status} - Schedule #{$s->id}\n";
        echo "   Reservation: {$reservationName}\n";
        echo "   Date: {$s->scheduled_date}\n";
        echo "   Start: {$s->start_time}\n";
        echo "   End: {$s->end_time}\n";
        echo "   Is Past?: " . ($isPast ? 'YES (should be hidden)' : 'NO (should show)') . "\n\n";
    }
}

echo "\n--- FILTER TEST ---\n";
$upcomingCount = MobileLibrarySchedule::where('end_time', '>=', $now)->count();
$pastCount = MobileLibrarySchedule::where('end_time', '<', $now)->count();

echo "Upcoming Schedules (should show): {$upcomingCount}\n";
echo "Past Schedules (should hide): {$pastCount}\n\n";

if ($upcomingCount === 0 && $pastCount > 0) {
    echo "⚠️  PROBLEM: All schedules are in the past!\n";
    echo "   This explains why admin schedule is empty.\n\n";
}

// Check which schedules should show in public
echo "\n--- PUBLIC SCHEDULE QUERY SIMULATION ---\n";
$publicSchedules = MobileLibrarySchedule::where('end_time', '>=', $now)
    ->orderBy('scheduled_date')
    ->orderBy('start_time')
    ->get();

echo "Count: {$publicSchedules->count()}\n";
foreach ($publicSchedules as $s) {
    echo "  - {$s->scheduled_date} {$s->start_time} → {$s->end_time}\n";
}

echo "\n=================================================\n";
echo "DONE!\n";
echo "=================================================\n";
