<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {

            // started_at
            if (!Schema::hasColumn('attempts', 'started_at')) {
                $table->timestamp('started_at')
                      ->nullable()
                      ->after('student_id');
            }

            // finished_at
            if (!Schema::hasColumn('attempts', 'finished_at')) {
                $table->timestamp('finished_at')
                      ->nullable()
                      ->after('started_at');
            }

            // score
            if (!Schema::hasColumn('attempts', 'score')) {
                $table->decimal('score', 8, 2)
                      ->default(0)
                      ->after('status');
            }

            // percent
            if (!Schema::hasColumn('attempts', 'percent')) {
                $table->decimal('percent', 5, 2)
                      ->default(0)
                      ->after('score');
            }
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

            if (Schema::hasColumn('attempts', 'finished_at')) {
                $table->dropColumn('finished_at');
            }

            if (Schema::hasColumn('attempts', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};
