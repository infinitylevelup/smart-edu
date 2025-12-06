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

            $table->string('id')->primary();

            $table->string('teacher_id');
            $table->foreign('teacher_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->string('title', 200);
            $table->text('description')->nullable();

            // این‌ها هم باید با نوع id جدول‌های مقصد یکی باشند.
            // چون در پروژه‌ات قبلاً string بوده، اینجا هم string می‌گذاریم:
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id')->nullable();
            $table->string('subject_type_id')->nullable();

            $table->enum('classroom_type', ['single', 'comprehensive']);

            $table->string('join_code', 20)->unique();
            $table->boolean('is_active')->default(true);

            $table->json('metadata')->nullable();

            $table->timestamps();

            // FKهای ساختاری (اگر جداول مقصد id=string دارند، درست کار می‌کند)
            $table->foreign('section_id')->references('id')->on('sections')->restrictOnDelete();
            $table->foreign('grade_id')->references('id')->on('grades')->restrictOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            $table->foreign('field_id')->references('id')->on('fields')->restrictOnDelete();

            $table->foreign('subfield_id')->references('id')->on('subfields')->nullOnDelete();
            $table->foreign('subject_type_id')->references('id')->on('subject_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
