<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // اگر بعد از redesign هنوز ستون‌ها موجودند:
            if (!Schema::hasColumn('subjects', 'section_id')) {
                $table->foreignId('section_id')
                    ->after('uuid') // یا جای مناسب
                    ->constrained('sections')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
        });
    }
};
