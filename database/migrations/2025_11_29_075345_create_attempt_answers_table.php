<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول attempt_answers:
 * - جواب هر سؤال در یک attempt را جدا نگه می‌دارد
 * - برای تحلیل نتایج، پروفایل دانش‌آموز، و گزارش معلم حیاتی است
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            // attempt مربوطه
            $table->foreignId('attempt_id')
                  ->constrained('attempts')
                  ->cascadeOnDelete();

            // سوال مربوطه
            $table->foreignId('question_id')
                  ->constrained('questions')
                  ->cascadeOnDelete();

            /**
             * answer:
             * پاسخ دانش‌آموز برای این سؤال (JSON برای پوشش همه نوع‌ها)
             * - MCQ: "a"
             * - True/False: true/false
             * - FillBlank: ["تهران","ایران"]
             * - Essay: "متن تشریحی..."
             */
            $table->json('answer')->nullable();

            /**
             * نمره‌دهی اتوماتیک/دستی:
             * is_correct   -> برای سوالات auto-graded (mcq/tf/fill_blank)
             * score_awarded -> نمره این سؤال (essay را معلم می‌دهد)
             */
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('score_awarded')->default(0);

            /**
             * فیدبک و نمره‌دهی تشریحی توسط معلم
             */
            $table->text('teacher_feedback')->nullable();
            $table->foreignId('graded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('graded_at')->nullable();

            $table->timestamps();

            // جلوگیری از ثبت جواب تکراری برای یک سؤال در یک attempt
            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
