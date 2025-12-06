<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id');
            $table->string('creator_id');
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('subject_id');
            $table->string('topic_id')->nullable();
            $table->unsignedTinyInteger('difficulty');
            $table->enum('question_type', ['mcq','descriptive','true_false','matching','short_answer','other']);
            $table->longText('content');
            $table->longText('options')->nullable();
            $table->longText('correct_answer');
            $table->longText('explanation')->nullable();
            $table->string('ai_generated_by_session_id')->nullable();
            $table->decimal('ai_confidence', 5, 2)->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};