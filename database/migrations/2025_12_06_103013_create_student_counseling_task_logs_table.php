<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_counseling_task_logs', function (Blueprint $table) {
            $table->string('id');
            $table->string('student_id');
            $table->string('counseling_task_id');
            $table->dateTime('done_at');
            $table->unsignedTinyInteger('self_rating')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_counseling_task_logs');
    }
};