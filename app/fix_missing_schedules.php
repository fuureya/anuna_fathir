<?php

/**
 * Fix Missing Schedules
 * Create MobileLibrarySchedule for confirmed reservations that don't have one
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=================================================\n";
echo "FIX: Create Missing MobileLibrarySchedule\n";
echo "=================================================\n\n";

// Find all confirmed reservations without MobileLibrarySchedule
$reservations = Reservation::where('status', 'confirmed')
    ->whereNotNull('visit_time')
    ->whereDoesntHave('mobileLibrarySchedule')
    ->get();

if ($reservations->count() === 0) {
    echo "✅ No missing schedules found. All confirmed reservations have schedules.\n";
    exit(0);
}

echo "Found {$reservations->count()} confirmed reservations without schedules:\n\n";

$created = 0;
$failed = 0;

foreach ($reservations as $r) {
    echo "Processing Reservation #{$r->id}: {$r->full_name}\n";
    echo "  Date: {$r->reservation_date}\n";
    echo "  Time: {$r->visit_time}\n";
    
    try {
        // Parse start time - use only date part from reservation_date
        $dateOnly = Carbon::parse($r->reservation_date)->format('Y-m-d');
        $startTime = Carbon::parse($dateOnly . ' ' . $r->visit_time);
        $duration = $r->duration_minutes ?: 120; // Default 2 hours
        $endTime = (clone $startTime)->addMinutes($duration);
        
        // Create schedule
        $schedule = MobileLibrarySchedule::create([
            'reservation_id' => $r->id,
            'scheduled_date' => $r->reservation_date,
            'start_time' => $startTime->format('Y-m-d H:i:s'),
            'end_time' => $endTime->format('Y-m-d H:i:s'),
        ]);
        
        echo "  ✅ Created MobileLibrarySchedule #{$schedule->id}\n";
        echo "     Start: {$startTime->format('Y-m-d H:i:s')}\n";
        echo "     End: {$endTime->format('Y-m-d H:i:s')}\n\n";
        
        $created++;
        
    } catch (\Exception $e) {
        echo "  ❌ Failed: {$e->getMessage()}\n\n";
        $failed++;
    }
}

echo "\n=================================================\n";
echo "SUMMARY\n";
echo "=================================================\n";
echo "✅ Created: {$created}\n";
echo "❌ Failed: {$failed}\n";
echo "📊 Total: " . ($created + $failed) . "\n";
echo "=================================================\n";

if ($created > 0) {
    echo "\n✨ Success! Now check:\n";
    echo "   - /admin/schedule (should show {$created} new schedules)\n";
    echo "   - /schedule (public - should show upcoming schedules)\n";
}
