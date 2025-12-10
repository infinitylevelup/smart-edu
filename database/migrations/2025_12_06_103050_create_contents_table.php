<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('type', ['video','pdf','article','link','quiz','other']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->text('file_path')->nullable();
            $table->text('url')->nullable();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->enum('access_level', ['public','classroom','private'])->default('public');

            $table->foreignId('classroom_id')->nullable()
                ->constrained('classrooms')
                ->nullOnDelete();

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_generated_by_session_id')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
