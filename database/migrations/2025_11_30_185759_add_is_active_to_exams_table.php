<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // بعد از is_published گذاشتم، اگر اون ستون رو نداری جای مناسب‌تر انتخاب کن
            $table->boolean('is_active')->default(true)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
