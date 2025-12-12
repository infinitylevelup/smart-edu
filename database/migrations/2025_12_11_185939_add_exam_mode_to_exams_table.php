<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('exam_mode')
                  ->default('single_subject')
                  ->after('exam_type');

            // درس اصلی امتحان تک‌درس
            $table->unsignedBigInteger('primary_subject_id')
                  ->nullable()
                  ->after('subject_type_id');

            // اگر لازم شد از رابطه استفاده می‌کنیم
            $table->foreign('primary_subject_id')
                  ->references('id')->on('subjects')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['primary_subject_id']);
            $table->dropColumn(['exam_mode', 'primary_subject_id']);
        });
    }
};
