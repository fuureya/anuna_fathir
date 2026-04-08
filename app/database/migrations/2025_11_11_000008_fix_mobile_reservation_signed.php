<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobile_library_schedule', function (Blueprint $table) {

            // Pastikan kolom FK BENAR
            $table->unsignedBigInteger('reservation_id')->nullable()->change();

            // Tambahkan foreign key
            $table->foreign('reservation_id')
            ->references('id')
            ->on('reservations')
            ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mobile_library_schedule', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
        });
    }
};
