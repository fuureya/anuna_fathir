<?php

/**
 * Verify FCFS Processing Results
 * 
 * This script verifies that FCFS calculations are correct
 * by checking WT and TAT values.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use Carbon\Carbon;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              Verify FCFS Processing Results                   ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Get all reservations with FCFS data
$reservations = Reservation::whereNotNull('arrival_time')
    ->whereNotNull('start_time')
    ->whereNotNull('completion_time')
    ->orderBy('reservation_date')
    ->orderBy('arrival_time')
    ->get();

if ($reservations->isEmpty()) {
    echo "⚠️  No FCFS data found. Run 'php artisan fcfs:process' first.\n\n";
    exit(0);
}

echo "📋 Found {$reservations->count()} reservations with FCFS data\n\n";

// Group by date
$groupedByDate = $reservations->groupBy(function($item) {
    return $item->reservation_date->format('Y-m-d');
});

$totalErrors = 0;

foreach ($groupedByDate as $date => $dateReservations) {
    echo "📅 Date: {$date}\n";
    echo str_repeat('─', 120) . "\n";
    
    echo str_pad('ID', 5) . ' | ';
    echo str_pad('Name', 20) . ' | ';
    echo str_pad('AT', 8) . ' | ';
    echo str_pad('ST', 8) . ' | ';
    echo str_pad('CT', 8) . ' | ';
    echo str_pad('BT', 6) . ' | ';
    echo str_pad('WT', 6) . ' | ';
    echo str_pad('TAT', 6) . ' | ';
    echo str_pad('Pos', 4) . ' | ';
    echo "Status\n";
    echo str_repeat('─', 120) . "\n";
    
    $errors = 0;
    
    foreach ($dateReservations as $r) {
        // Verify calculations
        $at = Carbon::parse($r->arrival_time);
        $st = Carbon::parse($r->start_time);
        $ct = Carbon::parse($r->completion_time);
        
        $expectedWT = $at->diffInMinutes($st);
        $expectedTAT = $at->diffInMinutes($ct);
        $expectedBT = $st->diffInMinutes($ct);
        
        $wtMatch = $r->waiting_time == $expectedWT;
        $tatMatch = $r->turnaround_time == $expectedTAT;
        $btMatch = $r->burst_time == $expectedBT;
        
        $status = ($wtMatch && $tatMatch && $btMatch) ? '✅' : '❌';
        
        if (!$wtMatch || !$tatMatch || !$btMatch) {
            $errors++;
            $totalErrors++;
        }
        
        echo str_pad($r->id, 5) . ' | ';
        echo str_pad(substr($r->full_name, 0, 20), 20) . ' | ';
        echo str_pad($at->format('H:i:s'), 8) . ' | ';
        echo str_pad($st->format('H:i:s'), 8) . ' | ';
        echo str_pad($ct->format('H:i:s'), 8) . ' | ';
        echo str_pad($r->burst_time . 'm', 6) . ' | ';
        echo str_pad($r->waiting_time . 'm', 6) . ' | ';
        echo str_pad($r->turnaround_time . 'm', 6) . ' | ';
        echo str_pad($r->queue_position ?? '-', 4) . ' | ';
        echo $status . "\n";
        
        if (!$wtMatch) {
            echo "     ⚠️  WT mismatch: expected {$expectedWT}, got {$r->waiting_time}\n";
        }
        if (!$tatMatch) {
            echo "     ⚠️  TAT mismatch: expected {$expectedTAT}, got {$r->turnaround_time}\n";
        }
        if (!$btMatch) {
            echo "     ⚠️  BT mismatch: expected {$expectedBT}, got {$r->burst_time}\n";
        }
    }
    
    echo str_repeat('─', 120) . "\n";
    
    // Calculate statistics for this date
    $avgWT = round($dateReservations->avg('waiting_time'), 2);
    $avgTAT = round($dateReservations->avg('turnaround_time'), 2);
    $count = $dateReservations->count();
    
    echo "  Statistics: {$count} reservations | Avg WT: {$avgWT}m | Avg TAT: {$avgTAT}m";
    
    if ($errors > 0) {
        echo " | ❌ {$errors} errors found";
    } else {
        echo " | ✅ All correct";
    }
    
    echo "\n\n";
}

// Final summary
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                          SUMMARY                               ║\n";
echo "╠════════════════════════════════════════════════════════════════╣\n";
echo "║  Total Verified: " . str_pad($reservations->count() . " reservations", 42) . "║\n";
echo "║  Total Errors:   " . str_pad($totalErrors, 42) . "║\n";

if ($totalErrors === 0) {
    echo "║  Status:         " . str_pad("✅ All calculations correct!", 42) . "║\n";
} else {
    echo "║  Status:         " . str_pad("❌ Some errors found", 42) . "║\n";
}

echo "╚════════════════════════════════════════════════════════════════╝\n\n";

if ($totalErrors > 0) {
    echo "⚠️  To fix errors, re-run FCFS processing:\n";
    echo "   php artisan fcfs:process --date=YYYY-MM-DD\n\n";
} else {
    echo "✅ FCFS verification complete! All calculations are correct.\n\n";
}
