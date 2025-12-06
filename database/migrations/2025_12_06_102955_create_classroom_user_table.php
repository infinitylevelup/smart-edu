<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ✅ FKها UUID
            $table->uuid('classroom_id');
            $table->uuid('user_id');

            $table->foreign('classroom_id')
                ->references('id')->on('classrooms')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            // ✅ جلوگیری از ثبت تکراری یک کاربر در یک کلاس
            $table->primary(['classroom_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_user');
    }
};
