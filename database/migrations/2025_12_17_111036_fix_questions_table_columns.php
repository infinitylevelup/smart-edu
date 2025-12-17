<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // فقط ستون‌هایی که وجود ندارند را اضافه کن
            
            // بررسی existence قبل از اضافه کردن
            if (!Schema::hasColumn('questions', 'module_label')) {
                $table->string('module_label')->nullable()->after('subject_id');
            }
            
            if (!Schema::hasColumn('questions', 'chapter_id')) {
                $table->unsignedBigInteger('chapter_id')->nullable()->after('module_label');
            }
            
            if (!Schema::hasColumn('questions', 'page_number')) {
                $table->integer('page_number')->nullable()->after('chapter_id');
            }
            
            if (!Schema::hasColumn('questions', 'difficulty')) {
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable()->after('score');
            }
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            // ستون‌ها را فقط اگر وجود دارند حذف کن
            if (Schema::hasColumn('questions', 'module_label')) {
                $table->dropColumn('module_label');
            }
            
            if (Schema::hasColumn('questions', 'chapter_id')) {
                $table->dropColumn('chapter_id');
            }
            
            if (Schema::hasColumn('questions', 'page_number')) {
                $table->dropColumn('page_number');
            }
            
            if (Schema::hasColumn('questions', 'difficulty')) {
                $table->dropColumn('difficulty');
            }
        });
    }
};