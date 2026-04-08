<?php
/**
 * Script untuk memperbaiki bug FCFS dimana reservasi yang tidak bentrok 
 * tetap dipindahkan karena algoritma lama menghitung secara sekuensial.
 * 
 * Bug: Reservasi jam 08:30 dipindahkan ke 16:14 padahal tidak bentrok dengan 14:14
 * Fix: Algoritma sekarang mengecek overlap waktu aktual, bukan chain sekuensial
 * 
 * Jalankan: php fix_fcfs_conflict_bug.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Reservation;
use App\Services\FCFSScheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║     Fix FCFS Bug - Reservasi Tidak Bentrok Tapi Dipindahkan   ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Get all dates with confirmed reservations
$dates = Reservation::whereIn('status', ['pending', 'confirmed'])
    ->select('reservation_date')
    ->distinct()
    ->orderBy('reservation_date', 'asc')
    ->pluck('reservation_date');

if ($dates->count() === 0) {
    echo "✓ Tidak ada reservasi yang perlu diproses.\n";
    exit(0);
}

echo "🔍 Ditemukan {$dates->count()} tanggal dengan reservasi\n\n";

$fcfsScheduler = new FCFSScheduler();
$totalFixed = 0;

foreach ($dates as $date) {
    $dateStr = Carbon::parse($date)->format('Y-m-d');
    
    // Get reservations for this date
    $reservations = Reservation::where('reservation_date', $dateStr)
        ->whereIn('status', ['pending', 'confirmed'])
        ->orderBy('id', 'asc')
        ->get();
    
    if ($reservations->isEmpty()) continue;
    
    echo "📅 Tanggal: {$dateStr} ({$reservations->count()} reservasi)\n";
    
    // Show before state
    echo "   SEBELUM:\n";
    foreach ($reservations as $r) {
        $visitTime = $r->visit_time ?? '-';
        $startTime = $r->start_time ? Carbon::parse($r->start_time)->format('H:i') : '-';
        echo "     #{$r->id} {$r->full_name}: Visit={$visitTime}, Start={$startTime}\n";
    }
    
    // Reset FCFS fields so they get recalculated
    DB::beginTransaction();
    try {
        foreach ($reservations as $r) {
            $r->start_time = null;
            $r->completion_time = null;
            $r->waiting_time = null;
            $r->turnaround_time = null;
            $r->queue_position = null;
            $r->save();
        }
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        echo "   ✗ Error saat reset: " . $e->getMessage() . "\n";
        continue;
    }
    
    // Process with fixed algorithm
    try {
        $result = $fcfsScheduler->processQueue($dateStr);
        
        // Reload and show after state
        $reservations = Reservation::where('reservation_date', $dateStr)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('id', 'asc')
            ->get();
        
        echo "   SESUDAH (dengan algoritma yang diperbaiki):\n";
        foreach ($reservations as $r) {
            $visitTime = $r->visit_time ?? '-';
            $startTime = $r->start_time ? Carbon::parse($r->start_time)->format('H:i') : '-';
            $endTime = $r->completion_time ? Carbon::parse($r->completion_time)->format('H:i') : '-';
            
            // Check if start time matches visit time (fixed)
            $match = ($visitTime == $startTime || Carbon::parse($r->visit_time)->format('H:i') == $startTime) ? '✓' : '!';
            echo "     #{$r->id} {$r->full_name}: Visit={$visitTime}, Start={$startTime} - {$endTime} {$match}\n";
        }
        
        echo "   ✓ Diproses {$result['processed']} reservasi\n\n";
        $totalFixed += $result['processed'];
        
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n\n";
    }
}

echo "════════════════════════════════════════════════════════════════\n";
echo "✓ Selesai! Total {$totalFixed} reservasi telah diproses ulang.\n";
echo "\n";
echo "📝 Penjelasan fix:\n";
echo "   Bug lama: Reservasi dichain secara sekuensial berdasarkan arrival_time,\n";
echo "             tanpa memperhatikan apakah waktu reservasi benar-benar bentrok.\n";
echo "   \n";
echo "   Fix: Algoritma sekarang mengecek overlap waktu aktual:\n";
echo "        - Jika jam 08:30-10:30 tidak overlap dengan 14:14-16:14, tidak dipindahkan\n";
echo "        - Reservasi tetap menggunakan waktu yang diminta user\n";
echo "        - Hanya dipindahkan jika benar-benar ada bentrok waktu\n";
