<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_sessions', function (Blueprint $table) {
            $table->string('id');
            $table->string('ai_agent_id');
            $table->string('user_id');
            $table->enum('context_type', ['teaching','counseling','qna','exam_help','mixed']);
            $table->string('section_id')->nullable();
            $table->string('grade_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('field_id')->nullable();
            $table->string('subfield_id')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('topic_id')->nullable();
            $table->string('classroom_id')->nullable();
            $table->string('exam_id')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->longText('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_sessions');
    }
};