<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {

            // درس سؤال (تعیین‌شده توسط exam_mode)
            $table->unsignedBigInteger('subject_id')
                  ->nullable()
                  ->after('exam_id');

            // اتصال اختیاری
            $table->foreign('subject_id')
                  ->references('id')->on('subjects')
                  ->nullOnDelete();

            // متادیتای آموزشی
            $table->string('module_label')->nullable()->after('subject_id');
            $table->unsignedSmallInteger('page_number')->nullable()->after('module_label');
            $table->string('difficulty')->nullable()->after('page_number');
            $table->text('hint')->nullable()->after('difficulty');
            $table->longText('solution')->nullable()->after('hint');

            // JSON آرایهٔ لینک‌های آموزشی
            $table->json('resource_links')->nullable()->after('solution');

            // اتصال آینده به جدول topics
            $table->unsignedBigInteger('topic_id')->nullable()->after('resource_links');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn([
                'subject_id',
                'module_label',
                'page_number',
                'difficulty',
                'hint',
                'solution',
                'resource_links',
                'topic_id',
            ]);
        });
    }
};
