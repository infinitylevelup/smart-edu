<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_path_steps', function (Blueprint $table) {
            $table->string('id');
            $table->string('learning_path_id');
            $table->enum('step_type', ['topic','subject','content','exercise','exam','counseling_task']);
            $table->string('order_index');
            $table->string('section_id')->nullable();
            $table->string('grade_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('field_id')->nullable();
            $table->string('subfield_id')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('topic_id')->nullable();
            $table->string('content_id')->nullable();
            $table->string('exam_id')->nullable();
            $table->string('counseling_task_id')->nullable();
            $table->string('estimated_minutes')->nullable();
            $table->decimal('required_mastery', 5, 2);
            $table->enum('status', ['locked','current','done','skipped']);
            $table->date('due_date')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_steps');
    }
};