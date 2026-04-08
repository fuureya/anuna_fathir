<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Reservation;

echo "=== Testing My Reservations with zamanilham57@gmail.com ===\n\n";

$user = User::where('email', 'zamanilham57@gmail.com')->first();

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "✅ User found:\n";
echo "  - Email: {$user->email}\n";
echo "  - Name: {$user->name}\n\n";

// Test the exact query from myReservations() controller
$reservations = Reservation::query()
    ->where(function($q) use ($user) {
        if ($user && $user->email) {
            $q->where('email', $user->email)
              ->orWhere('phone_number', $user->phone ?? '');
        }
    })
    ->orderByDesc('reservation_date')
    ->orderByDesc('id')
    ->get();

echo "📊 Reservations found: " . $reservations->count() . "\n\n";

if ($reservations->count() > 0) {
    echo "✅ SUCCESS! Reservations will appear on 'Reservasi Saya' page:\n\n";
    foreach ($reservations as $r) {
        $statusColor = $r->status === 'confirmed' ? '🟢' : ($r->status === 'pending' ? '🟠' : '🔴');
        echo "{$statusColor} ID {$r->id}: {$r->full_name}\n";
        echo "   Email: {$r->email}\n";
        echo "   Status: " . strtoupper($r->status) . "\n";
        echo "   Date: {$r->reservation_date}\n\n";
    }
    
    echo "\n✅ Test berhasil! Silakan:\n";
    echo "1. Login dengan: zamanilham57@gmail.com\n";
    echo "2. Akses: http://127.0.0.1:8000/reservations/my-reservations\n";
    echo "3. Atau klik 'Reservasi Saya' di dashboard/menu\n";
} else {
    echo "❌ No reservations found. This shouldn't happen!\n";
}
