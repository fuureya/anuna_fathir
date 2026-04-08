<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing FCFS metric columns to reservations table
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // arrival_time - when reservation request was submitted
            if (!Schema::hasColumn('reservations', 'arrival_time')) {
                $table->timestamp('arrival_time')->nullable()->after('email');
            }

            // burst_time - duration in minutes (default 120 = 2 hours)
            if (!Schema::hasColumn('reservations', 'burst_time')) {
                $table->integer('burst_time')->default(120)->nullable()->after('arrival_time');
            }

            // start_time - calculated start time by FCFS
            if (!Schema::hasColumn('reservations', 'start_time')) {
                $table->timestamp('start_time')->nullable()->after('burst_time');
            }

            // completion_time - calculated completion time (start_time + burst_time)
            if (!Schema::hasColumn('reservations', 'completion_time')) {
                $table->timestamp('completion_time')->nullable()->after('start_time');
            }

            // waiting_time - time waited in queue (minutes)
            if (!Schema::hasColumn('reservations', 'waiting_time')) {
                $table->integer('waiting_time')->nullable()->after('completion_time');
            }

            // turnaround_time - total time from arrival to completion (minutes)
            if (!Schema::hasColumn('reservations', 'turnaround_time')) {
                $table->integer('turnaround_time')->nullable()->after('waiting_time');
            }

            // queue_position - position in FCFS queue
            if (!Schema::hasColumn('reservations', 'queue_position')) {
                $table->integer('queue_position')->nullable()->after('turnaround_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $columns = [
                'arrival_time',
                'burst_time', 
                'start_time',
                'completion_time',
                'waiting_time',
                'turnaround_time',
                'queue_position'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('reservations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
