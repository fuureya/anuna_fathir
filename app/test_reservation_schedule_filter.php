<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use Carbon\Carbon;

echo "=================================================\n";
echo "TEST: Reservation Schedule Filter\n";
echo "=================================================\n\n";

$now = Carbon::now();
echo "Current DateTime: {$now}\n\n";

// Test the query from ReservationController::schedule()
echo "--- FILTER TEST: /reservations/schedule ---\n\n";

$allConfirmed = Reservation::where('status', 'confirmed')->get();
echo "Total Confirmed Reservations: {$allConfirmed->count()}\n\n";

foreach ($allConfirmed as $r) {
    $dateOnly = Carbon::parse($r->reservation_date)->format('Y-m-d');
    $visitTime = $r->visit_time ?: '00:00:00';
    $duration = $r->duration_minutes ?: 120;
    
    try {
        $endTime = Carbon::parse($dateOnly . ' ' . $visitTime)->addMinutes($duration);
        $isPast = $endTime->lessThan($now);
        $status = $isPast ? '❌ PAST' : '✅ UPCOMING';
        
        echo "{$status} - Reservation #{$r->id}: {$r->full_name}\n";
        echo "   Date: {$dateOnly}\n";
        echo "   Time: {$visitTime}\n";
        echo "   End: {$endTime}\n";
        echo "   Should Show?: " . ($isPast ? 'NO' : 'YES') . "\n\n";
    } catch (\Exception $e) {
        echo "⚠️  - Reservation #{$r->id}: {$r->full_name} (Parse error: {$e->getMessage()})\n\n";
    }
}

// Test the actual query
$upcomingReservations = Reservation::where('status', 'confirmed')
    ->where(function($q) {
        $q->whereRaw('DATE_ADD(CONCAT(DATE(reservation_date), " ", visit_time), INTERVAL COALESCE(duration_minutes, 120) MINUTE) >= NOW()');
    })
    ->get();

echo "\n--- QUERY RESULT ---\n";
echo "Upcoming Reservations (will show on /reservations/schedule): {$upcomingReservations->count()}\n\n";

foreach ($upcomingReservations as $r) {
    echo "  ✅ #{$r->id}: {$r->full_name} - {$r->reservation_date} {$r->visit_time}\n";
}

echo "\n=================================================\n";
echo "DONE!\n";
echo "=================================================\n";
