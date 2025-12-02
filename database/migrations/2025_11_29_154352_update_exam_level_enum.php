<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {

            // اگر ستون level وجود دارد و enum/set است:
            $table->enum('level', ['easy', 'average', 'hard', 'tough'])
                  ->default('average')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {

            // بازگشت به حالت قبلی (اگر قبلاً tough نداشتی)
            $table->enum('level', ['easy', 'average', 'hard'])
                  ->default('average')
                  ->change();
        });
    }
};
