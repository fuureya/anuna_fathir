<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // no-op: foreign key will be added in a later migration that handles unsigned/signed mismatch
        return;
    }

    public function down(): void
    {
        Schema::table('mobile_library_schedule', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
        });
    }
};
