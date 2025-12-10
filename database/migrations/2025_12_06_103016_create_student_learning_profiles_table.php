<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->longText('preferred_style')->nullable();
            $table->unsignedTinyInteger('pace_level')->default(1);
            $table->string('study_time_per_day')->nullable();

            $table->json('goals_json')->nullable();
            $table->text('counselor_notes')->nullable();
            $table->text('ai_summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_profiles');
    }
};
