<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name', 150);
            $table->string('email', 190)->unique()->nullable(); // بهتر است unique باشد
            $table->string('phone', 30)->unique()->nullable(); // بهتر است unique باشد
            $table->string('password', 255);
            $table->string('status', 30)->default('active'); // مقدار پیش‌فرض
            $table->boolean('is_active')->default(true); // ✅ اضافه شد
            $table->string('selected_role', 30)->nullable(); // برای سازگاری با کد قدیم

            $table->rememberToken(); // اگر نیاز به remember_token دارید
            $table->timestamps();
            $table->softDeletes(); // اگر می‌خواهید حذف نرم داشته باشید
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
