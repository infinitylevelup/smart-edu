<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // اضافه کردن ستون‌های مورد نیاز
            $table->string('module_label')->nullable()->after('subject_id');
            $table->unsignedBigInteger('chapter_id')->nullable()->after('module_label');
            $table->integer('page_number')->nullable()->after('chapter_id');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable()->after('score');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['module_label', 'chapter_id', 'page_number', 'difficulty']);
        });
    }
};