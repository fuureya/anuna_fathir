<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;
use App\Models\MobileLibrarySchedule;

echo "=== Checking Reservations & Schedules ===\n\n";

// Count confirmed reservations
$confirmedCount = Reservation::where('status', 'confirmed')->count();
echo "✅ Total Reservations (confirmed): {$confirmedCount}\n";

// Count generated schedules
$schedulesCount = MobileLibrarySchedule::count();
echo "✅ Total Schedules Generated: {$schedulesCount}\n\n";

// Show confirmed reservations
echo "📋 Confirmed Reservations:\n";
$confirmed = Reservation::where('status', 'confirmed')
    ->orderBy('reservation_date')
    ->orderBy('visit_time')
    ->get(['id', 'full_name', 'reservation_date', 'visit_time']);

if ($confirmed->count() > 0) {
    foreach ($confirmed as $r) {
        echo "  - ID {$r->id}: {$r->full_name} | Date: {$r->reservation_date} | Time: {$r->visit_time}\n";
    }
} else {
    echo "  ❌ Tidak ada reservasi confirmed\n";
}

// Show generated schedules
echo "\n📅 Generated Schedules:\n";
$schedules = MobileLibrarySchedule::with('reservation')
    ->orderBy('scheduled_date')
    ->orderBy('start_time')
    ->get();

if ($schedules->count() > 0) {
    foreach ($schedules as $s) {
        $resName = $s->reservation ? $s->reservation->full_name : 'N/A';
        echo "  - Schedule ID {$s->id}: Reservation #{$s->reservation_id} ({$resName}) | Date: {$s->scheduled_date} | Time: {$s->start_time} - {$s->end_time}\n";
    }
} else {
    echo "  ❌ Tidak ada jadwal yang sudah di-generate\n";
    echo "\n💡 Cara generate jadwal:\n";
    echo "  1. Buka http://localhost:8000/admin/schedule\n";
    echo "  2. Klik tombol 'Generate Jadwal'\n";
    echo "  3. Preview jadwal yang akan di-generate\n";
    echo "  4. Klik 'Simpan Jadwal' untuk commit ke database\n";
}

echo "\n";
