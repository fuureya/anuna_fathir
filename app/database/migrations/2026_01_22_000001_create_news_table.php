<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Ringkasan singkat
            $table->longText('content');
            $table->string('image')->nullable(); // Gambar utama berita
            $table->enum('category', ['berita', 'agenda'])->default('berita');
            $table->date('event_date')->nullable(); // Untuk agenda
            $table->time('event_time')->nullable(); // Untuk agenda
            $table->string('event_location')->nullable(); // Untuk agenda
            $table->boolean('is_featured')->default(false); // Berita utama
            $table->boolean('is_published')->default(true);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
