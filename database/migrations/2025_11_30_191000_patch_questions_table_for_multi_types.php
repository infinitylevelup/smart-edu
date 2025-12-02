<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            // -----------------------------
            // Add missing multi-type columns
            // -----------------------------

            if (!Schema::hasColumn('questions', 'type')) {
                $table->enum('type', ['mcq','true_false','fill_blank','essay'])
                      ->default('mcq')
                      ->after('question_text');
            }

            if (!Schema::hasColumn('questions', 'score')) {
                $table->unsignedInteger('score')
                      ->default(1)
                      ->after('type');
            }

            if (!Schema::hasColumn('questions', 'explanation')) {
                $table->text('explanation')
                      ->nullable()
                      ->after('score');
            }

            if (!Schema::hasColumn('questions', 'options')) {
                $table->json('options')
                      ->nullable()
                      ->after('explanation');
            }

            if (!Schema::hasColumn('questions', 'correct_answer')) {
                $table->json('correct_answer')
                      ->nullable()
                      ->after('options');
            }

            if (!Schema::hasColumn('questions', 'correct_tf')) {
                $table->boolean('correct_tf')
                      ->nullable()
                      ->after('correct_answer');
            }

            if (!Schema::hasColumn('questions', 'is_active')) {
                $table->boolean('is_active')
                      ->default(1)
                      ->after('correct_tf');
            }
        });

        // -----------------------------
        // Make legacy MCQ columns nullable
        // (Separate Schema::table because of change())
        // -----------------------------
        Schema::table('questions', function (Blueprint $table) {

            // برای change نیاز به doctrine/dbal داری
            if (Schema::hasColumn('questions', 'option_a')) {
                $table->string('option_a')->nullable()->change();
            }
            if (Schema::hasColumn('questions', 'option_b')) {
                $table->string('option_b')->nullable()->change();
            }
            if (Schema::hasColumn('questions', 'option_c')) {
                $table->string('option_c')->nullable()->change();
            }
            if (Schema::hasColumn('questions', 'option_d')) {
                $table->string('option_d')->nullable()->change();
            }
            if (Schema::hasColumn('questions', 'correct_option')) {
                $table->enum('correct_option', ['a','b','c','d'])->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {

            // برگشت legacy ها به حالت اجباری
            if (Schema::hasColumn('questions', 'option_a')) {
                $table->string('option_a')->nullable(false)->change();
            }
            if (Schema::hasColumn('questions', 'option_b')) {
                $table->string('option_b')->nullable(false)->change();
            }
            if (Schema::hasColumn('questions', 'option_c')) {
                $table->string('option_c')->nullable(false)->change();
            }
            if (Schema::hasColumn('questions', 'option_d')) {
                $table->string('option_d')->nullable(false)->change();
            }
            if (Schema::hasColumn('questions', 'correct_option')) {
                $table->enum('correct_option', ['a','b','c','d'])->nullable(false)->change();
            }

            // حذف ستون‌های جدید فقط اگر وجود دارند
            $columnsToDrop = [];
            foreach (['type','score','explanation','options','correct_answer','correct_tf','is_active'] as $col) {
                if (Schema::hasColumn('questions', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (count($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
