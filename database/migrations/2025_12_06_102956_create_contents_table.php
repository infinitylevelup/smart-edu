<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->string('id');
            $table->string('creator_id');
            $table->enum('type', ['video','pdf','article','link','quiz','other']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->text('file_path')->nullable();
            $table->text('url')->nullable();
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('subject_id');
            $table->string('topic_id')->nullable();
            $table->enum('access_level', ['public','classroom','private']);
            $table->string('classroom_id')->nullable();
            $table->string('ai_generated_by_session_id')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};