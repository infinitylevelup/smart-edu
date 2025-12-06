<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->string('id');
            $table->string('teacher_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('subject_type_id')->nullable();
            $table->enum('classroom_type', ['single','comprehensive']);
            $table->string('join_code', 20);
            $table->boolean('is_active');
            $table->longText('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};