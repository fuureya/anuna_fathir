<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;

echo "=== Checking Duration Minutes ===\n\n";

$reservations = Reservation::where('status', 'confirmed')
    ->orderBy('id')
    ->get(['id', 'full_name', 'duration_minutes']);

foreach ($reservations as $r) {
    $duration = $r->duration_minutes ?? 'NULL';
    echo "ID {$r->id}: {$r->full_name} | Duration: {$duration} menit\n";
}

echo "\n";
