<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->restrictOnDelete();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();

            $table->unsignedTinyInteger('difficulty')->default(1);

            $table->enum('question_type', [
                'mcq','descriptive','true_false','matching','short_answer','other'
            ])->default('mcq');

            $table->longText('content');
            $table->longText('options')->nullable();
            $table->longText('correct_answer');
            $table->longText('explanation')->nullable();

            // FK بعداً در add_ نهایی
            $table->unsignedBigInteger('ai_generated_by_session_id')->nullable();
            $table->decimal('ai_confidence', 5, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
