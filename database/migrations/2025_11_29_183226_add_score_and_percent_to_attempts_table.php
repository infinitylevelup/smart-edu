<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {

            // ✅ NEW: نمره‌ی نهایی attempt
            if (!Schema::hasColumn('attempts', 'score')) {
                $table->decimal('score', 8, 2)->default(0)->after('status');
            }

            // ✅ NEW: درصد نهایی attempt
            if (!Schema::hasColumn('attempts', 'percent')) {
                $table->decimal('percent', 5, 2)->default(0)->after('score');
            }

            /*
            |--------------------------------------------------------------------------
            | OLD CODE (kept for rollback)
            |--------------------------------------------------------------------------
            | قبلاً این ستون‌ها وجود نداشتند و Attempt فقط status داشت.
            | حالا برای هماهنگی با منطق grading اضافه شدند.
            */
        });
    }

    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            if (Schema::hasColumn('attempts', 'percent')) {
                $table->dropColumn('percent');
            }
            if (Schema::hasColumn('attempts', 'score')) {
                $table->dropColumn('score');
            }
        });
    }
};
