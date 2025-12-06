<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_artifacts', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('session_id');
            $table->enum('artifact_type', ['content','question','plan','report','rubric','other']);
            $table->string('title', 250)->nullable();
            $table->longText('body')->nullable();
            $table->string('linked_table', 100)->nullable();
            $table->string('linked_id')->nullable();
            $table->enum('status', ['draft','approved','published','rejected']);
            $table->string('reviewer_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_artifacts');
    }
};
