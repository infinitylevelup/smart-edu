<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');                 // نام کلاس
            $table->string('subject')->nullable();   // درس
            $table->string('grade')->nullable();     // پایه
            $table->text('description')->nullable(); // توضیح

            $table->string('join_code', 10)->unique(); // کد ورود دانش‌آموز
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
