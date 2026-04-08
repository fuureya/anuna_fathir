<?php
/**
 * Script untuk fix FCFS metrics pada reservasi yang sudah confirmed tapi belum diproses
 * 
 * Masalah: Reservasi lama tidak memiliki arrival_time sehingga tidak terproses FCFS
 * Solusi: Set arrival_time untuk reservasi lama dan reprocess FCFS
 * 
 * Jalankan: php fix_fcfs_metrics.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Reservation;
use App\Services\FCFSScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║              Fix FCFS Metrics untuk Reservasi Lama           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// 0. Cek apakah kolom FCFS sudah ada di database
echo "📌 Langkah 0: Cek kolom FCFS di database...\n";

$requiredColumns = ['arrival_time', 'burst_time', 'start_time', 'completion_time', 'waiting_time', 'turnaround_time', 'queue_position'];
$missingColumns = [];

foreach ($requiredColumns as $column) {
    if (!Schema::hasColumn('reservations', $column)) {
        $missingColumns[] = $column;
    }
}

if (count($missingColumns) > 0) {
    echo "   ⚠️  Kolom berikut belum ada di tabel reservations:\n";
    foreach ($missingColumns as $col) {
        echo "      - {$col}\n";
    }
    echo "\n   🔧 Jalankan migration terlebih dahulu:\n";
    echo "      php artisan migrate\n\n";
    echo "   Atau jalankan migration spesifik:\n";
    echo "      php artisan migrate --path=database/migrations/2026_01_11_000001_add_fcfs_columns_to_reservations_table.php\n\n";
    exit(1);
}

echo "   ✓ Semua kolom FCFS sudah ada di database\n";

// 1. Fix reservasi yang tidak punya arrival_time
echo "\n📌 Langkah 1: Cek reservasi tanpa arrival_time...\n";

$noArrivalTime = Reservation::whereNull('arrival_time')->get();

if ($noArrivalTime->count() > 0) {
    echo "   Ditemukan {$noArrivalTime->count()} reservasi tanpa arrival_time\n";
    
    foreach ($noArrivalTime as $r) {
        // Set arrival_time dari created_at, atau fallback ke reservation_date
        $arrivalTime = $r->created_at ?? Carbon::parse($r->reservation_date)->startOfDay();
        $r->arrival_time = $arrivalTime;
        
        // Set burst_time jika belum ada
        if (!$r->burst_time) {
            $r->burst_time = $r->duration_minutes ?? 120;
        }
        
        $r->save();
        echo "   ✓ Fixed reservation #{$r->id} - {$r->full_name}\n";
    }
} else {
    echo "   ✓ Semua reservasi sudah memiliki arrival_time\n";
}

// 2. Proses ulang FCFS untuk semua tanggal yang ada reservasi confirmed
echo "\n📌 Langkah 2: Proses ulang FCFS untuk reservasi confirmed...\n";

$dates = Reservation::where('status', 'confirmed')
    ->whereNull('start_time')
    ->pluck('reservation_date')
    ->unique();

if ($dates->count() > 0) {
    echo "   Ditemukan {$dates->count()} tanggal dengan reservasi belum diproses FCFS\n\n";
    
    $fcfsScheduler = new FCFSScheduler();
    
    foreach ($dates as $date) {
        $dateStr = Carbon::parse($date)->format('Y-m-d');
        echo "   📅 Processing date: {$dateStr}...\n";
        
        try {
            $result = $fcfsScheduler->processQueue($dateStr);
            echo "      ✓ Processed {$result['processed']} reservations\n";
            echo "      📊 Avg WT: {$result['avg_waiting_time']}m, Avg TAT: {$result['avg_turnaround_time']}m\n";
        } catch (Exception $e) {
            echo "      ✗ Error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "   ✓ Tidak ada reservasi confirmed yang perlu diproses\n";
}

// 3. Tampilkan hasil akhir
echo "\n╔════════════════════════════════════════════════════════════════╗\n";
echo "║                         Hasil Akhir                           ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

$confirmed = Reservation::where('status', 'confirmed')->get();
$withMetrics = $confirmed->whereNotNull('start_time')->count();
$withoutMetrics = $confirmed->whereNull('start_time')->count();

echo "📊 Status FCFS Metrics:\n";
echo "   - Reservasi confirmed total: {$confirmed->count()}\n";
echo "   - Dengan FCFS metrics: {$withMetrics}\n";
echo "   - Tanpa FCFS metrics: {$withoutMetrics}\n\n";

if ($withoutMetrics > 0) {
    echo "⚠️  Masih ada {$withoutMetrics} reservasi tanpa FCFS metrics.\n";
    echo "   Ini mungkin karena belum ada visit_time yang di-set.\n";
} else {
    echo "✅ Semua reservasi confirmed sudah memiliki FCFS metrics!\n";
}

echo "\n";
