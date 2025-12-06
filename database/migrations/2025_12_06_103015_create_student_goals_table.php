<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_goals', function (Blueprint $table) {
            $table->string('id');
            $table->string('student_id');
            $table->enum('goal_type', ['academic','psychological','career']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->date('target_date')->nullable();
            $table->enum('status', ['active','done','paused']);
            $table->unsignedTinyInteger('priority');
            $table->string('related_subject_id')->nullable();
            $table->string('related_topic_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_goals');
    }
};