<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // lebih idiomatik daripada integer + autoIncrement
            $table->string('full_name', 100);
            $table->string('category', 100)->nullable();
            $table->string('occupation', 100);
            $table->string('address', 255);
            $table->string('phone_number', 20);
            $table->string('request_letter', 255)->nullable();
            $table->enum('gender', ['Perempuan','Laki-laki']);
            $table->date('reservation_date');
            $table->time('visit_time')->nullable();
            $table->dateTime('visit_start')->nullable();
            $table->dateTime('visit_end')->nullable();

            // Pilih SATU definisi
            $table->integer('duration_minutes')->default(120);

            $table->string('status', 20)->default('pending');
            $table->string('email', 100)->nullable();

            // FCFS fields
            $table->timestamp('arrival_time')->nullable();
            $table->integer('burst_time')->default(120);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
