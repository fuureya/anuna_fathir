<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== Fix Duration: Update 60 minutes to 120 minutes (2 hours) ===\n\n";

// STEP 1: Update reservations with duration_minutes = 60 to 120
echo "📋 STEP 1: Updating reservations duration_minutes...\n";

$reservationsUpdated = Reservation::where('duration_minutes', 60)
    ->orWhereNull('duration_minutes')
    ->update(['duration_minutes' => 120]);

echo "   ✅ Updated {$reservationsUpdated} reservations to 120 minutes\n\n";

// STEP 2: Recalculate end_time for reservations
echo "📋 STEP 2: Recalculating reservation end_time...\n";

$reservations = Reservation::whereNotNull('visit_time')->get();
$endTimeUpdated = 0;

foreach ($reservations as $r) {
    try {
        $timeStr = strlen($r->visit_time) === 5 ? $r->visit_time.':00' : $r->visit_time;
        $startTime = Carbon::createFromFormat('H:i:s', $timeStr);
        $duration = $r->duration_minutes ?: 120;
        $correctEndTime = (clone $startTime)->addMinutes($duration)->format('H:i:s');
        
        if ($r->end_time !== $correctEndTime) {
            $r->end_time = $correctEndTime;
            $r->save();
            $endTimeUpdated++;
            echo "   ✅ Updated reservation #{$r->id} ({$r->full_name}): end_time = {$correctEndTime}\n";
        }
    } catch (\Exception $e) {
        echo "   ⚠️ Skipped reservation #{$r->id}: " . $e->getMessage() . "\n";
    }
}

echo "   Total: {$endTimeUpdated} reservations end_time updated\n\n";

// STEP 3: Recalculate schedules end_time
echo "📋 STEP 3: Recalculating schedule end_time...\n";

$schedules = MobileLibrarySchedule::with('reservation')->get();
$schedulesUpdated = 0;

foreach ($schedules as $schedule) {
    $startTime = Carbon::parse($schedule->start_time);
    $currentEndTime = Carbon::parse($schedule->end_time);
    
    // Get duration from reservation, default 120 minutes
    $duration = 120;
    if ($schedule->reservation && $schedule->reservation->duration_minutes) {
        $duration = $schedule->reservation->duration_minutes;
    }
    
    $correctEndTime = (clone $startTime)->addMinutes($duration);
    
    if ($currentEndTime->format('Y-m-d H:i:s') !== $correctEndTime->format('Y-m-d H:i:s')) {
        $schedule->end_time = $correctEndTime;
        $schedule->save();
        $schedulesUpdated++;
        
        $name = $schedule->reservation ? $schedule->reservation->full_name : 'Unknown';
        echo "   ✅ Updated schedule #{$schedule->id} ({$name}): {$startTime->format('H:i')} - {$correctEndTime->format('H:i')}\n";
    }
}

echo "   Total: {$schedulesUpdated} schedules end_time updated\n\n";

// STEP 4: Update database default value
echo "📋 STEP 4: Updating database default value...\n";

try {
    DB::statement('ALTER TABLE reservations ALTER COLUMN duration_minutes SET DEFAULT 120');
    echo "   ✅ Database default updated to 120 minutes\n";
} catch (\Exception $e) {
    // Try alternative syntax for MySQL
    try {
        DB::statement('ALTER TABLE reservations MODIFY COLUMN duration_minutes INT DEFAULT 120');
        echo "   ✅ Database default updated to 120 minutes\n";
    } catch (\Exception $e2) {
        echo "   ⚠️ Could not update database default (may require manual SQL): " . $e2->getMessage() . "\n";
    }
}

echo "\n=== Summary ===\n";
echo "✅ Reservations duration updated: {$reservationsUpdated}\n";
echo "✅ Reservations end_time recalculated: {$endTimeUpdated}\n";
echo "✅ Schedules end_time recalculated: {$schedulesUpdated}\n";
echo "\n✨ All done! Duration is now 2 hours (120 minutes)\n";
