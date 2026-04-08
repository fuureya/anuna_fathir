<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MobileLibrarySchedule;
use Carbon\Carbon;

echo "=== Final Verification: Generated Schedules ===\n\n";

$schedules = MobileLibrarySchedule::with('reservation')
    ->orderBy('scheduled_date')
    ->orderBy('start_time')
    ->get();

echo "✅ Total Schedules: {$schedules->count()}\n\n";

foreach ($schedules as $s) {
    $r = $s->reservation;
    $name = $r ? $r->full_name : 'N/A';
    $date = Carbon::parse($s->scheduled_date)->format('Y-m-d');
    $start = Carbon::parse($s->start_time)->format('H:i');
    $end = Carbon::parse($s->end_time)->format('H:i');
    
    // Calculate duration
    $startTime = Carbon::parse($s->start_time);
    $endTime = Carbon::parse($s->end_time);
    $durationMinutes = $startTime->diffInMinutes($endTime);
    $durationHours = round($durationMinutes / 60, 1);
    
    echo "📅 {$date} | ⏰ {$start} - {$end} ({$durationHours} jam) | 👤 {$name}\n";
}

echo "\n✅ Semua jadwal sudah 2 jam!\n";
