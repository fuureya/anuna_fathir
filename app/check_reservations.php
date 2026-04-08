<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Reservation;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              Check All Reservations Status                    ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$reservations = Reservation::orderBy('reservation_date')->get();

if ($reservations->isEmpty()) {
    echo "⚠️  No reservations found in database.\n";
    exit;
}

echo sprintf("📋 Found %d reservations:\n\n", $reservations->count());

foreach ($reservations as $r) {
    echo sprintf(
        "ID: %-3d | Date: %s | Status: %-10s | AT: %s | ST: %s\n",
        $r->id,
        $r->reservation_date,
        $r->status,
        $r->arrival_time ? $r->arrival_time->format('Y-m-d H:i') : 'NULL',
        $r->start_time ? $r->start_time->format('Y-m-d H:i') : 'NOT PROCESSED'
    );
}

echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║                       Status Breakdown                        ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";

$statuses = Reservation::selectRaw('status, COUNT(*) as count')
    ->groupBy('status')
    ->get();

foreach ($statuses as $s) {
    echo sprintf("  %s: %d\n", ucfirst($s->status), $s->count);
}

echo "\n💡 To process FCFS for a specific date, run:\n";
echo "   php artisan fcfs:process --date=YYYY-MM-DD\n";
