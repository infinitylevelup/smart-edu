<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            // نوع آزمون در UI: آزاد / کلاسی
            $table->string('scope', 20)
                ->default('classroom')
                ->after('exam_type');

            // برچسب نمایشی درس در فرم ادیت (می‌تواند خالی باشد)
            $table->string('subject')->nullable()->after('scope');

            // سطح آزمون: تقویتی / کنکور / المپیاد
            $table->string('level', 30)
                ->default('taghviyati')
                ->after('subject');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['scope', 'subject', 'level']);
        });
    }
};
