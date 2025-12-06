<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_feedback', function (Blueprint $table) {
            $table->string('id');
            $table->string('session_id');
            $table->string('message_id')->nullable();
            $table->string('user_id');
            $table->unsignedTinyInteger('rating');
            $table->text('feedback_text')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_feedback');
    }
};