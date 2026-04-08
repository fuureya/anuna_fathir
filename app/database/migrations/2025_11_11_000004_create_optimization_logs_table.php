<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('optimization_logs', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->dateTime('optimized_at')->useCurrent();
            $table->text('before_optimization')->nullable();
            $table->text('after_optimization')->nullable();
            $table->decimal('total_distance', 10, 2)->nullable();
            $table->text('reservations')->nullable();
            $table->enum('status', ['success','failure'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('optimization_logs');
    }
};
