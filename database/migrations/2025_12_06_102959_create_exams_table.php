<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')->nullable()
                ->constrained('classrooms')
                ->nullOnDelete();

            $table->enum('exam_type', ['public','class_single','class_comprehensive'])
                ->default('public');

            $table->string('title', 250);
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('duration_minutes');

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_type_id')->nullable()->constrained('subject_types')->nullOnDelete();

            $table->unsignedSmallInteger('total_questions')->nullable();
            $table->longText('coefficients')->nullable();

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->boolean('ai_assisted')->default(false);

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_session_id')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
