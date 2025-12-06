<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_profiles', function (Blueprint $table) {
            $table->string('id');
            $table->string('student_id');
            $table->longText('preferred_style')->nullable();
            $table->unsignedTinyInteger('pace_level');
            $table->string('study_time_per_day')->nullable();
            $table->longText('goals_json')->nullable();
            $table->text('counselor_notes')->nullable();
            $table->text('ai_summary')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_profiles');
    }
};