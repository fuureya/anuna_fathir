<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mobile_library_schedule')) {
            return;
        }

        Schema::create('mobile_library_schedule', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->unsignedInteger('reservation_id')->nullable()->index();
            $table->date('scheduled_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedInteger('vehicle_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('mobile_library_schedule')) {
            Schema::dropIfExists('mobile_library_schedule');
        }
    }
};
