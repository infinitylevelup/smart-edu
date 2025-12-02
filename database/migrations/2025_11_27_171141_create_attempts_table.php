<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول attempts:
 * - هر Attempt یعنی یک بار شرکت دانش‌آموز در یک آزمون
 * - ساختار قبلی شما حفظ شد (نام جدول attempts و ایده‌ی answers JSON)
 * - ستون‌های جدید برای پایداری و گزارش‌گیری اضافه شد
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();

            // آزمونی که attempt مربوط به آن است
            $table->foreignId('exam_id')
                  ->constrained('exams')
                  ->cascadeOnDelete();

            // دانش‌آموزی که attempt را انجام می‌دهد
            $table->foreignId('student_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            /**
             * answers (legacy):
             * - قبلاً هم داشتید و نگه‌اش می‌داریم برای سازگاری
             * - ساختار پیشنهادی:
             *   {
             *     "question_id_1": answer,
             *     "question_id_2": answer,
             *     ...
             *   }
             * - مثال MCQ: {"12":"a"}
             * - مثال True/False: {"13": true}
             * - مثال FillBlank: {"14":["تهران","ایران"]}
             * - مثال Essay: {"15":"متن تشریحی دانش‌آموز"}
             */
            $table->json('answers')->nullable();

            /**
             * وضعیت attempt:
             * in_progress  = شروع شده ولی هنوز ارسال نشده
             * submitted    = دانش‌آموز ارسال کرده
             * graded       = معلم (تشریحی‌ها) نمره داده
             */
            $table->enum('status', ['in_progress', 'submitted', 'graded'])
                  ->default('in_progress');

            /**
             * نمره‌ها:
             * score_obtained = نمره کسب‌شده
             * score_total    = نمره کل آزمون در زمان attempt (snapshot)
             * percent        = درصد (برای UI راحت‌تر)
             */
            $table->unsignedInteger('score_obtained')->default(0);
            $table->unsignedInteger('score_total')->default(0);

            $table->decimal('percent', 5, 2)->default(0);

            // زمان شروع/پایان attempt
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            /**
             * meta:
             * برای اطلاعات آینده (مثلاً زمان مصرفی، دستگاه، ip، ...)
             * وجودش کمک می‌کند بعداً بدون مگریشن‌های سنگین توسعه بدهیم
             */
            $table->json('meta')->nullable();

            $table->timestamps();

            // ایندکس برای گزارش‌گیری سریع
            $table->index(['exam_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
