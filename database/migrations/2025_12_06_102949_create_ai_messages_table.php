<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->string('id');
            $table->string('session_id');
            $table->enum('sender_type', ['user','ai','system']);
            $table->longText('content');
            $table->string('tokens_in')->nullable();
            $table->string('tokens_out')->nullable();
            $table->longText('safety_flags')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
    }
};