<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // 1. بررسی کن ستون exam_id وجود دارد
            if (Schema::hasColumn('questions', 'exam_id')) {
                // 2. سعی کن foreign key را حذف کن
                $table->dropForeign(['exam_id']);
            }
        });
        
        // 3. دوباره بررسی کن و ستون را حذف کن
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'exam_id')) {
                $table->dropColumn('exam_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // اضافه کردن ستون exam_id
            if (!Schema::hasColumn('questions', 'exam_id')) {
                $table->unsignedBigInteger('exam_id')->nullable()->after('creator_id');
                
                // فقط اگر جدول exams وجود دارد foreign key ایجاد کن
                if (Schema::hasTable('exams')) {
                    $table->foreign('exam_id')
                        ->references('id')->on('exams')
                        ->onDelete('set null');
                }
            }
        });
    }
};