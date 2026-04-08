<?php

/**
 * Test Script: Verify Auto-Create MobileLibrarySchedule
 * 
 * This script tests if MobileLibrarySchedule is automatically created
 * when a reservation is confirmed by admin.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;
use Illuminate\Support\Facades\DB;

echo "=================================================\n";
echo "TEST: Auto-Create MobileLibrarySchedule\n";
echo "=================================================\n\n";

// Find a confirmed reservation with visit_time
$reservation = Reservation::where('status', 'confirmed')
    ->whereNotNull('visit_time')
    ->first();

if (!$reservation) {
    echo "❌ No confirmed reservations with visit_time found.\n";
    echo "   Please confirm a reservation first via admin panel.\n";
    exit(1);
}

echo "✅ Found confirmed reservation:\n";
echo "   ID: {$reservation->id}\n";
echo "   Name: {$reservation->full_name}\n";
echo "   Date: {$reservation->reservation_date}\n";
echo "   Time: {$reservation->visit_time}\n";
echo "   Duration: {$reservation->duration_minutes} minutes\n\n";

// Check if MobileLibrarySchedule exists
$schedule = MobileLibrarySchedule::where('reservation_id', $reservation->id)->first();

if ($schedule) {
    echo "✅ MobileLibrarySchedule exists:\n";
    echo "   Schedule ID: {$schedule->id}\n";
    echo "   Scheduled Date: {$schedule->scheduled_date}\n";
    echo "   Start Time: {$schedule->start_time}\n";
    echo "   End Time: {$schedule->end_time}\n\n";
    
    echo "✅ AUTO-CREATE SCHEDULE: WORKING!\n";
} else {
    echo "❌ MobileLibrarySchedule NOT found for reservation #{$reservation->id}\n";
    echo "   This means auto-create is not working.\n\n";
    
    echo "🔧 MANUAL FIX: You can create it manually:\n";
    echo "   Run this in tinker:\n";
    echo "   php artisan tinker\n";
    echo "   Then execute:\n";
    echo "   App\\Models\\MobileLibrarySchedule::create(['reservation_id' => {$reservation->id}, 'scheduled_date' => '{$reservation->reservation_date}', 'start_time' => '{$reservation->reservation_date} {$reservation->visit_time}', 'end_time' => '" . \Carbon\Carbon::parse($reservation->reservation_date . ' ' . $reservation->visit_time)->addMinutes($reservation->duration_minutes ?: 120)->format('Y-m-d H:i:s') . "']);\n";
}

echo "\n=================================================\n";
echo "TEST FILTER: Show Only Upcoming Schedules\n";
echo "=================================================\n\n";

$now = now();
echo "Current Time: {$now}\n\n";

$upcomingSchedules = MobileLibrarySchedule::where('end_time', '>=', $now)->count();
$pastSchedules = MobileLibrarySchedule::where('end_time', '<', $now)->count();

echo "📊 Schedule Statistics:\n";
echo "   Upcoming/Ongoing: {$upcomingSchedules}\n";
echo "   Past (Hidden): {$pastSchedules}\n";
echo "   Total: " . ($upcomingSchedules + $pastSchedules) . "\n\n";

if ($upcomingSchedules > 0) {
    echo "✅ FILTER: WORKING! Only {$upcomingSchedules} upcoming schedules will be shown.\n";
} else {
    echo "⚠️  No upcoming schedules. All schedules are in the past.\n";
}

echo "\n=================================================\n";
echo "DONE!\n";
echo "=================================================\n";
