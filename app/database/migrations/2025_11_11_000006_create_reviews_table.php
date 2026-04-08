<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->text('service_quality');
            $table->text('book_availability');
            $table->text('book_collection');
            $table->timestamp('created_at')->useCurrent();
            $table->enum('service_quality_sentiment', ['Positif','Negatif','Netral'])->nullable();
            $table->enum('book_availability_sentiment', ['Positif','Negatif','Netral'])->nullable();
            $table->enum('book_collection_sentiment', ['Positif','Negatif','Netral'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
