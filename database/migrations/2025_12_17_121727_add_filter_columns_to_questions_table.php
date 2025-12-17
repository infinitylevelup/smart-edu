<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // ستون‌های ضروری برای فیلتر
            if (!Schema::hasColumn('questions', 'module_label')) {
                $table->string('module_label')->nullable()->comment('ماژول/فصل')->after('subject_id');
            }
            
            if (!Schema::hasColumn('questions', 'chapter_id')) {
                $table->unsignedBigInteger('chapter_id')->nullable()->comment('شناسه فصل')->after('module_label');
            }
            
            if (!Schema::hasColumn('questions', 'page_number')) {
                $table->integer('page_number')->nullable()->comment('شماره صفحه')->after('chapter_id');
            }
            
            if (!Schema::hasColumn('questions', 'difficulty')) {
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable()->comment('سختی')->after('score');
            }
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $columns = ['module_label', 'chapter_id', 'page_number', 'difficulty'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('questions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};