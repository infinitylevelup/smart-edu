<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id');
            $table->string('slug', 50);
            $table->string('name_fa', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};