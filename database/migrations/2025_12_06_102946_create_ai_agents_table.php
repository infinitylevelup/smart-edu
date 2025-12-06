<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_agents', function (Blueprint $table) {
            $table->string('id');
            $table->string('slug', 100);
            $table->string('name_fa', 150);
            $table->enum('role_type', ['tutor','counselor','both']);
            $table->string('model_provider', 50);
            $table->string('model_name', 100);
            $table->longText('system_prompt')->nullable();
            $table->longText('config')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_agents');
    }
};