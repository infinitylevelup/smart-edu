<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // مشکل: ستون difficulty ممکنه از قبل وجود داشته باشه
            if (!Schema::hasColumn('questions', 'difficulty')) {
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable()->after('score');
            }
            
            // بقیه ستون‌ها...
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['difficulty']); // و بقیه ستون‌ها
        });
    }
};