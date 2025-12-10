<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_artifacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('session_id')
                ->constrained('ai_sessions')
                ->cascadeOnDelete();

            $table->enum('artifact_type', ['content','question','plan','report','rubric','other']);
            $table->string('title', 250)->nullable();
            $table->longText('body')->nullable();

            $table->string('linked_table', 100)->nullable();
            $table->unsignedBigInteger('linked_id')->nullable();

            $table->enum('status', ['draft','approved','published','rejected'])
                ->default('draft');

            $table->foreignId('reviewer_id')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_artifacts');
    }
};
