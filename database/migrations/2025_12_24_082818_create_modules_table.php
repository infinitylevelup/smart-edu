<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->string('title', 255);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
