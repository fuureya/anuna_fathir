<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->integer('buku_id')->primary()->autoIncrement();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->string('tahun_terbit', 4);
            $table->integer('jumlah_eksemplar');
            $table->string('ISBN', 20)->unique();
            $table->string('kategori', 100);
            $table->string('genre')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->text('deskripsi')->nullable();
            $table->string('audience_category', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
