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
        Schema::create('bus_tracking', function (Blueprint $table) {
            $table->id();
            $table->date('tracking_date');
            $table->integer('current_reservation_id')->nullable();
            $table->enum('bus_status', ['idle', 'on_the_way', 'arrived', 'serving', 'finished'])->default('idle');
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->integer('updated_by')->nullable(); // admin user id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_tracking');
    }
};
