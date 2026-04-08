<?php
// Script untuk hapus semua data reservasi
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Reservation;

// Hapus semua reservasi
$count = Reservation::count();
echo "Found {$count} reservations\n";

// Hapus dengan delete() bukan truncate() karena ada foreign key
Reservation::query()->delete();

echo "✅ All {$count} reservations deleted successfully!\n";
