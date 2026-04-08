<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;

echo "=== Update Duration to 120 Minutes ===\n\n";

// Update all confirmed reservations to 2 hours (120 minutes)
$updated = Reservation::where('status', 'confirmed')
    ->update(['duration_minutes' => 120]);

echo "✅ Updated {$updated} reservations to 120 minutes (2 hours)\n\n";

// Verify
echo "📋 Updated Reservations:\n";
$reservations = Reservation::where('status', 'confirmed')
    ->orderBy('id')
    ->get(['id', 'full_name', 'duration_minutes']);

foreach ($reservations as $r) {
    echo "  - ID {$r->id}: {$r->full_name} | Duration: {$r->duration_minutes} menit\n";
}

echo "\n✅ Sekarang re-generate jadwal dengan: php generate_schedules.php\n";
