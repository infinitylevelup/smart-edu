<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_daily_logs', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('student_id');
            $table->date('log_date');
            $table->string('study_minutes');
            $table->unsignedTinyInteger('mood')->nullable();
            $table->unsignedTinyInteger('stress_level')->nullable();
            $table->decimal('sleep_hours', 4, 1)->nullable();
            $table->text('free_text')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_daily_logs');
    }
};
