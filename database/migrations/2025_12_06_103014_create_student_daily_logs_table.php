<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_daily_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('log_date');
            $table->unsignedSmallInteger('study_minutes')->default(0);

            $table->unsignedTinyInteger('mood')->nullable();
            $table->unsignedTinyInteger('stress_level')->nullable();
            $table->decimal('sleep_hours', 4, 1)->nullable();

            $table->text('free_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_daily_logs');
    }
};
