<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_goals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('goal_type', ['academic','psychological','career']);
            $table->string('title', 250);
            $table->text('description')->nullable();
            $table->date('target_date')->nullable();

            $table->enum('status', ['active','done','paused'])->default('active');
            $table->unsignedTinyInteger('priority')->default(1);

            $table->foreignId('related_subject_id')->nullable()
                ->constrained('subjects')->nullOnDelete();

            $table->foreignId('related_topic_id')->nullable()
                ->constrained('topics')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_goals');
    }
};
