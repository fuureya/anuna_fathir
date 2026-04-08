<?php

/**
 * FCFS Algorithm Test Script
 * 
 * This script simulates the FCFS (First Come First Served) scheduling algorithm
 * with sample reservation data to verify calculations.
 */

require __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;

// Sample reservation data
$reservations = [
    [
        'id' => 1,
        'name' => 'SDN 1 Parepare',
        'arrival_time' => '2025-12-09 08:00:00',
        'requested_time' => '2025-12-09 09:00:00',
        'burst_time' => 120, // 2 hours
    ],
    [
        'id' => 2,
        'name' => 'SMP Negeri 5',
        'arrival_time' => '2025-12-09 08:15:00',
        'requested_time' => '2025-12-09 10:00:00',
        'burst_time' => 90, // 1.5 hours
    ],
    [
        'id' => 3,
        'name' => 'Komunitas Baca',
        'arrival_time' => '2025-12-09 08:30:00',
        'requested_time' => '2025-12-09 09:30:00',
        'burst_time' => 60, // 1 hour
    ],
    [
        'id' => 4,
        'name' => 'Puskesmas Kota',
        'arrival_time' => '2025-12-09 09:00:00',
        'requested_time' => '2025-12-09 11:00:00',
        'burst_time' => 120, // 2 hours
    ],
];

echo "╔════════════════════════════════════════════════════════════════════════════════════════╗\n";
echo "║      FCFS (First Come First Served) Scheduling Algorithm - Test Simulation           ║\n";
echo "╚════════════════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "📋 Testing with " . count($reservations) . " sample reservations\n";
echo "📅 Date: 2025-12-09\n\n";

// Process reservations in FCFS order (sorted by arrival time)
usort($reservations, function($a, $b) {
    return strtotime($a['arrival_time']) - strtotime($b['arrival_time']);
});

$results = [];
$previousCompletionTime = null;
$totalWaitingTime = 0;
$totalTurnaroundTime = 0;

foreach ($reservations as $index => $reservation) {
    $arrivalTime = Carbon::parse($reservation['arrival_time']);
    $requestedTime = Carbon::parse($reservation['requested_time']);
    $burstTime = $reservation['burst_time'];
    
    // Calculate Start Time (ST)
    // ST = max(AT, previous CT, RT if available)
    $startTime = $arrivalTime->copy();
    
    if ($previousCompletionTime) {
        $previousCT = Carbon::parse($previousCompletionTime);
        if ($previousCT->gt($startTime)) {
            $startTime = $previousCT->copy();
        }
    }
    
    // Use requested time if it's after calculated start time
    if ($requestedTime->gt($startTime)) {
        $startTime = $requestedTime->copy();
    }
    
    // Calculate Completion Time (CT)
    // CT = ST + BT
    $completionTime = $startTime->copy()->addMinutes($burstTime);
    
    // Calculate Waiting Time (WT)
    // WT = ST - AT (in minutes)
    $waitingTime = $arrivalTime->diffInMinutes($startTime);
    
    // Calculate Turnaround Time (TAT)
    // TAT = CT - AT (in minutes)
    $turnaroundTime = $arrivalTime->diffInMinutes($completionTime);
    
    $results[] = [
        'id' => $reservation['id'],
        'name' => $reservation['name'],
        'position' => $index + 1,
        'AT' => $arrivalTime->format('H:i:s'),
        'RT' => $requestedTime->format('H:i:s'),
        'BT' => $burstTime,
        'ST' => $startTime->format('H:i:s'),
        'CT' => $completionTime->format('H:i:s'),
        'WT' => $waitingTime,
        'TAT' => $turnaroundTime,
    ];
    
    $previousCompletionTime = $completionTime;
    $totalWaitingTime += $waitingTime;
    $totalTurnaroundTime += $turnaroundTime;
}

// Display results table
echo "┌──────┬──────┬──────────────────────┬──────────┬──────────┬──────┬──────────┬──────────┬───────┬───────┐\n";
echo "│ Pos  │  ID  │       Name           │    AT    │    RT    │  BT  │    ST    │    CT    │   WT  │  TAT  │\n";
echo "├──────┼──────┼──────────────────────┼──────────┼──────────┼──────┼──────────┼──────────┼───────┼───────┤\n";

foreach ($results as $result) {
    echo "│ " . str_pad($result['position'], 4) . " │ ";
    echo str_pad($result['id'], 4) . " │ ";
    echo str_pad($result['name'], 20) . " │ ";
    echo str_pad($result['AT'], 8) . " │ ";
    echo str_pad($result['RT'], 8) . " │ ";
    echo str_pad($result['BT'] . 'm', 4) . " │ ";
    echo str_pad($result['ST'], 8) . " │ ";
    echo str_pad($result['CT'], 8) . " │ ";
    echo str_pad($result['WT'] . 'm', 5) . " │ ";
    echo str_pad($result['TAT'] . 'm', 5) . " │\n";
}

echo "└──────┴──────┴──────────────────────┴──────────┴──────────┴──────┴──────────┴──────────┴───────┴───────┘\n\n";

// Display statistics
$count = count($results);
$avgWaitingTime = round($totalWaitingTime / $count, 2);
$avgTurnaroundTime = round($totalTurnaroundTime / $count, 2);

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                         STATISTICS                             ║\n";
echo "╠════════════════════════════════════════════════════════════════╣\n";
echo "║  Total Processed          : " . str_pad($count . " reservations", 39) . "║\n";
echo "║  Average Waiting Time     : " . str_pad($avgWaitingTime . " minutes", 39) . "║\n";
echo "║  Average Turnaround Time  : " . str_pad($avgTurnaroundTime . " minutes", 39) . "║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                           LEGEND                               ║\n";
echo "╠════════════════════════════════════════════════════════════════╣\n";
echo "║  AT  = Arrival Time (when request was submitted)              ║\n";
echo "║  RT  = Requested Time (user's preferred visit time)           ║\n";
echo "║  BT  = Burst Time (duration in minutes)                       ║\n";
echo "║  ST  = Start Time (actual visit start time)                   ║\n";
echo "║  CT  = Completion Time (visit end time)                       ║\n";
echo "║  WT  = Waiting Time (ST - AT in minutes)                      ║\n";
echo "║  TAT = Turnaround Time (CT - AT in minutes)                   ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

echo "✅ FCFS Algorithm Test Complete!\n";
