<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_subject', function (Blueprint $table) {
            $table->string('exam_id');
            $table->string('subject_id');
            $table->string('question_count')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_subject');
    }
};