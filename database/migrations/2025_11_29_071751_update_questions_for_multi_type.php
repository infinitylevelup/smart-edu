<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | 1) Legacy MCQ columns -> nullable
            |--------------------------------------------------------------------------
            | چون دیتابیس قبلی فقط چهارگزینه‌ای بوده و این ستون‌ها NOT NULL هستند،
            | برای ذخیره‌ی true_false / fill_blank / essay باید nullable شوند.
            | داده‌های قبلی حفظ می‌شوند.
            */
            $table->string('option_a')->nullable()->change();
            $table->string('option_b')->nullable()->change();
            $table->string('option_c')->nullable()->change();
            $table->string('option_d')->nullable()->change();
            $table->enum('correct_option', ['a','b','c','d'])->nullable()->change();

            /*
            |--------------------------------------------------------------------------
            | 2) Multi-type columns (NEW)
            |--------------------------------------------------------------------------
            */
            if (!Schema::hasColumn('questions', 'type')) {
                $table->enum('type', ['mcq','true_false','fill_blank','essay'])
                      ->default('mcq')
                      ->after('question_text');
            }

            if (!Schema::hasColumn('questions', 'score')) {
                $table->unsignedInteger('score')->default(1)->after('type');
            }

            if (!Schema::hasColumn('questions', 'options')) {
                $table->json('options')->nullable()->after('score'); // برای MCQ مدرن (اختیاری)
            }

            if (!Schema::hasColumn('questions', 'correct_answer')) {
                $table->json('correct_answer')->nullable()->after('options'); // برای fill_blank
            }

            if (!Schema::hasColumn('questions', 'correct_tf')) {
                $table->boolean('correct_tf')->nullable()->after('correct_answer'); // برای true_false
            }

            if (!Schema::hasColumn('questions', 'explanation')) {
                $table->text('explanation')->nullable()->after('correct_tf'); // توضیح/راهنما
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            // ستون‌های جدید حذف شوند
            $drop = [];
            foreach (['type','score','options','correct_answer','correct_tf','explanation'] as $col) {
                if (Schema::hasColumn('questions', $col)) $drop[] = $col;
            }
            if (count($drop)) $table->dropColumn($drop);

            // Legacy به حالت NOT NULL برمی‌گردد (اگر خواستی rollback بزنی)
            $table->string('option_a')->nullable(false)->change();
            $table->string('option_b')->nullable(false)->change();
            $table->string('option_c')->nullable(false)->change();
            $table->string('option_d')->nullable(false)->change();
            $table->enum('correct_option', ['a','b','c','d'])->nullable(false)->change();
        });
    }
};
//
