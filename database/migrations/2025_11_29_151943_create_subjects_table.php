<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            // هر درس متعلق به یک معلم
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('title');            // نام درس
            $table->string('grade_level')->nullable(); // پایه/سطح (اختیاری)
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['teacher_id','title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
