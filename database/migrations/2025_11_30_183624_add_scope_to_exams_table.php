<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {

            // نوع/دامنه آزمون: free یا classroom
            if (!Schema::hasColumn('exams', 'scope')) {
                $table->enum('scope', ['free', 'classroom'])
                      ->default('classroom')
                      ->after('classroom_id');
            }

            // اگر می‌خوای مطمئن باشی کلاس nullable است:
            // (تو مایگریشن‌های شما nullable هست ولی این هم safe است)
            $table->foreignId('classroom_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'scope')) {
                $table->dropColumn('scope');
            }
        });
    }
};
