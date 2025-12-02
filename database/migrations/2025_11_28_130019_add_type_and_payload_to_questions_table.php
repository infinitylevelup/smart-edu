<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * اضافه کردن ستون‌های لازم برای چند-نوعی شدن سوال‌ها
 *
 * ما فعلاً ستون‌های قدیمی را نگه می‌داریم تا سیستم فعلی (چهارگزینه‌ای)
 * از کار نیفتد. در فازهای بعدی، به‌تدریج کدها را به ستون‌های جدید منتقل می‌کنیم.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            /**
             * نوع سوال:
             * mcq         = چهارگزینه‌ای
             * true_false  = صحیح/غلط
             * fill_blank  = جاخالی
             * essay       = تشریحی
             *
             * پیش‌فرض را mcq می‌گذاریم تا داده‌های قبلی همچنان معتبر باشند.
             */
            $table->enum('type', ['mcq', 'true_false', 'fill_blank', 'essay'])
                  ->default('mcq')
                  ->after('question_text');

            /**
             * options:
             * برای mcq گزینه‌ها در قالب json ذخیره می‌شوند:
             * {"a":"...", "b":"...", "c":"...", "d":"..."}
             *
             * برای بقیه نوع‌ها null می‌ماند.
             */
            $table->json('options')
                  ->nullable()
                  ->after('type');

            /**
             * correct_answer:
             * - mcq: ["a"]
             * - true_false: [true] یا [false]
             * - fill_blank: ["جواب1","جواب2",...]
             * - essay: null (تصحیح دستی)
             */
            $table->json('correct_answer')
                  ->nullable()
                  ->after('options');

            /**
             * explanation:
             * توضیح جواب درست (اختیاری)
             * اگر خواستی بعداً به دانش‌آموز نمایش بدهی، آماده است.
             */
            $table->text('explanation')
                  ->nullable()
                  ->after('correct_answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // فقط ستون‌های جدید را حذف می‌کنیم
            $table->dropColumn(['type', 'options', 'correct_answer', 'explanation']);
        });
    }
};
