<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login', function (Blueprint $table) {
            $table->integer('id')->primary()->autoIncrement();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('created_at')->useCurrent();
        });

        // foreign key to users.email
        Schema::table('login', function (Blueprint $table) {
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('login', function (Blueprint $table) {
            $table->dropForeign(['email']);
        });
        Schema::dropIfExists('login');
    }
};
