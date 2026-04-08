<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('reservation_date');
            }
            if (!Schema::hasColumn('reservations', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('reservations', 'audience_category')) {
                $table->string('audience_category', 50)->nullable()->after('occupation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'latitude')) {
                $table->dropColumn('latitude');
            }
            if (Schema::hasColumn('reservations', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('reservations', 'audience_category')) {
                $table->dropColumn('audience_category');
            }
        });
    }
};
