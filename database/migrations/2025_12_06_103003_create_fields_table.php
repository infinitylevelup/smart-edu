<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->string('id');
            $table->string('branch_id');
            $table->string('slug', 100);
            $table->string('name_fa', 150);
            $table->string('icon', 50)->nullable();
            $table->string('sort_order');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};