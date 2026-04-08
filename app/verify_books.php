<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Book;
use Illuminate\Support\Facades\DB;

echo "=== Verification: Book Data ===\n\n";

// Count total books
$total = Book::count();
echo "✅ Total Books: {$total}\n\n";

// Count by category
echo "📚 Books by Category:\n";
$categories = DB::table('books')
    ->select('kategori', DB::raw('COUNT(*) as count'))
    ->groupBy('kategori')
    ->get();

foreach ($categories as $cat) {
    echo "  - {$cat->kategori}: {$cat->count} buku\n";
}

echo "\n👥 Books by Audience:\n";
$audiences = DB::table('books')
    ->select('audience_category', DB::raw('COUNT(*) as count'))
    ->groupBy('audience_category')
    ->get();

foreach ($audiences as $aud) {
    echo "  - " . ucfirst($aud->audience_category) . ": {$aud->count} buku\n";
}

// Show latest 10 books
echo "\n📖 Latest 10 Books:\n";
$latest = Book::orderBy('created_at', 'desc')
    ->take(10)
    ->get(['judul', 'penulis', 'kategori', 'jumlah_eksemplar']);

foreach ($latest as $book) {
    echo "  - {$book->judul} by {$book->penulis} ({$book->kategori}) - Stock: {$book->jumlah_eksemplar}\n";
}

echo "\n✅ Seeder berhasil!\n";
