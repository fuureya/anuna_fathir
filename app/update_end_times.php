<?php
// Script untuk update end_time pada reservasi existing
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;

$reservations = Reservation::whereNotNull('visit_time')
    ->whereNull('end_time')
    ->get();

echo "Found {$reservations->count()} reservations to update\n";

foreach($reservations as $r) {
    try {
        $startTime = \Carbon\Carbon::parse($r->visit_time);
        $r->end_time = $startTime->addHours(2)->format('H:i:s');
        $r->save();
        
        echo "✅ Updated ID {$r->id}: {$r->visit_time} -> {$r->end_time}\n";
    } catch(Exception $e) {
        echo "❌ Error ID {$r->id}: {$e->getMessage()}\n";
    }
}

echo "\nDone!\n";
