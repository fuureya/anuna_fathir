<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Reservation;

echo "=== Debug My Reservations Logic ===\n\n";

// Get first user
$user = User::first();
if (!$user) {
    echo "❌ No user found in database\n";
    exit(1);
}

echo "✅ Test User:\n";
echo "  - ID: {$user->id}\n";
echo "  - Email: {$user->email}\n";
echo "  - Phone: " . ($user->phone ?? 'NULL') . "\n\n";

// Get all reservations
$allReservations = Reservation::all();
echo "📊 Total Reservations: " . $allReservations->count() . "\n\n";

echo "All Reservations:\n";
foreach ($allReservations as $r) {
    echo "  - ID {$r->id}: {$r->full_name} | Email: {$r->email} | Phone: {$r->phone_number} | Status: {$r->status}\n";
}

// Test query logic
echo "\n=== Testing Query Logic ===\n";

$query = Reservation::query()
    ->where(function($q) use ($user) {
        if ($user && $user->email) {
            $q->where('email', $user->email)
              ->orWhere('phone_number', $user->phone ?? '');
        }
    })
    ->orderByDesc('reservation_date')
    ->orderByDesc('id');

$myReservations = $query->get();

echo "✅ Reservations found for user {$user->email}: " . $myReservations->count() . "\n\n";

if ($myReservations->count() > 0) {
    echo "Matching Reservations:\n";
    foreach ($myReservations as $r) {
        echo "  - ID {$r->id}: {$r->full_name} | Email: {$r->email} | Phone: {$r->phone_number}\n";
    }
} else {
    echo "❌ No reservations found!\n\n";
    echo "Possible reasons:\n";
    echo "  1. User email '{$user->email}' doesn't match any reservation email\n";
    echo "  2. User phone doesn't match any reservation phone_number\n";
    echo "\nTo fix: Make sure reservation email matches user email when creating reservation\n";
}
