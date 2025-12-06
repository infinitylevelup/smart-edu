<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->string('id');
            $table->string('student_id');
            $table->enum('path_type', ['academic','counseling','mixed']);
            $table->string('title', 250);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active','completed','archived']);
            $table->enum('generated_by', ['ai','counselor','teacher','system']);
            $table->longText('metadata')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};