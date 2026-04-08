<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {

            // Tambah arrival_time jika belum ada
            if (!Schema::hasColumn('reservations', 'arrival_time')) {
                $table->timestamp('arrival_time')
                      ->nullable()
                      ->after('email');
            }

            // Tambah burst_time jika belum ada
            if (!Schema::hasColumn('reservations', 'burst_time')) {
                $table->integer('burst_time')
                      ->default(120)
                      ->after('arrival_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'arrival_time')) {
                $table->dropColumn('arrival_time');
            }

            if (Schema::hasColumn('reservations', 'burst_time')) {
                $table->dropColumn('burst_time');
            }
        });
    }
};

