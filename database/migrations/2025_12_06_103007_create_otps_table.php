<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->string('id');
            $table->string('phone', 15);
            $table->string('code', 6);
            $table->string('token', 255)->nullable();
            $table->enum('type', ['login','register','password_reset']);
            $table->unsignedTinyInteger('attempts');
            $table->boolean('verified');
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};