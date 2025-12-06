<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_types', function (Blueprint $table) {
            $table->string('id');
            $table->string('slug', 100);
            $table->string('name_fa', 150);
            $table->string('coefficient');
            $table->decimal('weight_percent', 6, 2);
            $table->string('default_question_count');
            $table->string('color', 30)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('sort_order');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_types');
    }
};