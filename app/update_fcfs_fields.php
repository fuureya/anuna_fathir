<?php

/**
 * Update Existing Reservations with FCFS Fields
 * 
 * This script updates existing reservations to set arrival_time
 * for reservations that were created before FCFS implementation.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║       Update Existing Reservations with FCFS Fields           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

try {
    // Get reservations without arrival_time
    $reservations = Reservation::whereNull('arrival_time')
        ->whereIn('status', ['pending', 'confirmed'])
        ->get();
    
    if ($reservations->isEmpty()) {
        echo "✅ No reservations need updating.\n";
        echo "All reservations already have arrival_time set.\n\n";
        exit(0);
    }
    
    echo "📋 Found {$reservations->count()} reservations without arrival_time\n\n";
    
    DB::beginTransaction();
    
    $updated = 0;
    foreach ($reservations as $reservation) {
        // Use created_at as arrival_time if available, otherwise use now()
        $arrivalTime = $reservation->created_at ?? now();
        
        $reservation->arrival_time = $arrivalTime;
        $reservation->burst_time = $reservation->duration_minutes ?? 120;
        $reservation->save();
        
        $updated++;
        
        echo "  ✓ ID {$reservation->id}: {$reservation->full_name} | AT: {$arrivalTime}\n";
    }
    
    DB::commit();
    
    echo "\n╔════════════════════════════════════════════════════════════════╗\n";
    echo "║                          SUMMARY                               ║\n";
    echo "╠════════════════════════════════════════════════════════════════╣\n";
    echo "║  Total Updated: " . str_pad($updated . " reservations", 43) . "║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";
    
    echo "✅ Update completed successfully!\n\n";
    echo "Next steps:\n";
    echo "1. Run FCFS processing:\n";
    echo "   php artisan fcfs:process --date=YYYY-MM-DD\n\n";
    echo "2. Or process all pending reservations:\n";
    echo "   php artisan fcfs:process\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
