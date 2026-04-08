<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use App\Mail\ReservationApproved;
use Illuminate\Support\Facades\Mail;

// Get latest confirmed reservation
$reservation = Reservation::where('status', 'confirmed')->orderBy('id', 'desc')->first();

if (!$reservation) {
    echo "❌ Tidak ada reservasi confirmed untuk ditest\n";
    exit(1);
}

$testEmail = 'loginbuat312@gmail.com';

echo "=== Testing Email Send ===\n\n";
echo "📧 Target Email: {$testEmail}\n";
echo "📋 Reservation Details:\n";
echo "  - ID: {$reservation->id}\n";
echo "  - Name: {$reservation->full_name}\n";
echo "  - Date: {$reservation->reservation_date}\n";
echo "  - Status: {$reservation->status}\n\n";

echo "⏳ Mengirim email...\n\n";

$startTime = microtime(true);

try {
    Mail::to($testEmail)->send(new ReservationApproved($reservation));
    
    $duration = round(microtime(true) - $startTime, 2);
    
    echo "✅ EMAIL BERHASIL DIKIRIM!\n\n";
    echo "📊 Details:\n";
    echo "  - Dikirim ke: {$testEmail}\n";
    echo "  - Waktu: {$duration} detik\n";
    echo "  - Subject: Reservasi Perpustakaan Disetujui - {$reservation->full_name}\n\n";
    
    echo "📬 Silakan cek:\n";
    echo "  1. Inbox: {$testEmail}\n";
    echo "  2. Folder SPAM/JUNK (penting!)\n";
    echo "  3. Tunggu 1-2 menit untuk email masuk\n\n";
    
    echo "📧 Email berisi:\n";
    echo "  ✓ Detail reservasi lengkap\n";
    echo "  ✓ QR Code verifikasi\n";
    echo "  ✓ Kode verifikasi\n";
    echo "  ✓ Informasi penting\n";
    
} catch (\Exception $e) {
    $duration = round(microtime(true) - $startTime, 2);
    
    echo "❌ EMAIL GAGAL DIKIRIM!\n\n";
    echo "⚠️ Error: " . $e->getMessage() . "\n\n";
    
    if (str_contains($e->getMessage(), '421') || str_contains($e->getMessage(), 'too many connections')) {
        echo "🔴 PENYEBAB: Gmail SMTP Connection Limit\n\n";
        echo "💡 SOLUSI:\n";
        echo "  1. Tunggu 10-15 menit\n";
        echo "  2. Atau gunakan Mailtrap untuk testing\n";
        echo "  3. Atau setup email queue\n\n";
        echo "📖 Lihat: EMAIL_TROUBLESHOOTING.md\n";
    } elseif (str_contains($e->getMessage(), '535') || str_contains($e->getMessage(), 'authentication')) {
        echo "🔴 PENYEBAB: Gmail Authentication Failed\n\n";
        echo "💡 SOLUSI:\n";
        echo "  1. Pastikan App Password benar di .env\n";
        echo "  2. Regenerate App Password di Google Account\n";
        echo "  3. Aktifkan 2-Factor Authentication\n";
    } else {
        echo "🔴 PENYEBAB: Error lainnya\n\n";
        echo "Stack Trace:\n";
        echo $e->getTraceAsString();
    }
    
    echo "\n\nWaktu: {$duration} detik\n";
}
