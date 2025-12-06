<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->string('id');
            $table->string('attempt_id');
            $table->string('question_id');
            $table->longText('answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};