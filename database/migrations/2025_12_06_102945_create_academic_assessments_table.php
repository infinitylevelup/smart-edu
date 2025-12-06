<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_assessments', function (Blueprint $table) {
            $table->string('id');
            $table->string('student_id');
            $table->string('section_id')->nullable();
            $table->string('grade_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('field_id')->nullable();
            $table->string('subfield_id')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('topic_id')->nullable();
            $table->enum('assessment_type', ['diagnostic','weekly','exam_result','practice']);
            $table->decimal('score_percent', 5, 2);
            $table->decimal('mastery_level', 5, 2);
            $table->longText('weaknesses')->nullable();
            $table->longText('strengths')->nullable();
            $table->dateTime('taken_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_assessments');
    }
};