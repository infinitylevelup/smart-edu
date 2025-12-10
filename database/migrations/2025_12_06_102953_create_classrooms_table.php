<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();

            $table->foreignId('section_id')->constrained('sections')->restrictOnDelete();
            $table->foreignId('grade_id')->constrained('grades')->restrictOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->restrictOnDelete();
            $table->foreignId('field_id')->constrained('fields')->restrictOnDelete();
            $table->foreignId('subfield_id')->nullable()->constrained('subfields')->nullOnDelete();
            $table->foreignId('subject_type_id')->nullable()->constrained('subject_types')->nullOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete(); // منتقل از add_

            $table->enum('classroom_type', ['single', 'comprehensive']);
            $table->string('join_code', 20)->unique();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
