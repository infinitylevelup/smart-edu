<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            // معلم سازنده آزمون
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // سطح آزمون: taghviyati / konkur / olympiad
            $table->enum('level', ['easy','average','hard','tough'])->default('average');

            // زمان آزمون به دقیقه
            $table->unsignedInteger('duration')->default(15);

            // وضعیت انتشار
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
