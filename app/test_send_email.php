<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use App\Mail\ReservationApproved;
use Illuminate\Support\Facades\Mail;

// Get latest reservation
$reservation = Reservation::orderBy('id', 'desc')->first();

if (!$reservation) {
    echo "Tidak ada reservasi untuk ditest\n";
    exit(1);
}

echo "Testing email send to: {$reservation->email}\n";
echo "Reservation ID: {$reservation->id}\n";
echo "Full Name: {$reservation->full_name}\n\n";

try {
    Mail::to($reservation->email)->send(new ReservationApproved($reservation));
    echo "✅ Email berhasil dikirim!\n";
    echo "Cek inbox di: {$reservation->email}\n";
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
