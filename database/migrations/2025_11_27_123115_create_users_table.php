<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // شماره موبایل (یونیک)
            $table->string('phone', 11)->unique();

            // نقش کاربر (در اولین ورود انتخاب می‌شود)
            $table->enum('role', ['student', 'teacher', 'admin'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
