<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->string('id');
            $table->string('teacher_id');
            $table->string('classroom_id')->nullable();
            $table->enum('exam_type', ['public','class_single','class_comprehensive']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->string('duration_minutes');
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('subject_type_id');
            $table->string('total_questions')->nullable();
            $table->longText('coefficients')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('shuffle_questions');
            $table->boolean('shuffle_options');
            $table->boolean('ai_assisted');
            $table->string('ai_session_id')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};