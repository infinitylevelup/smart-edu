<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_assessments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->enum('assessment_type', ['diagnostic','weekly','exam_result','practice']);

            $table->decimal('score_percent', 5, 2)->default(0);
            $table->decimal('mastery_level', 5, 2)->default(0);

            $table->longText('weaknesses')->nullable();
            $table->longText('strengths')->nullable();

            $table->dateTime('taken_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_assessments');
    }
};
