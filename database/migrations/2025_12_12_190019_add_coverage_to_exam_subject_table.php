<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_subject', function (Blueprint $table) {
            $table->json('coverage')->nullable()->after('question_count');
        });
    }

    public function down(): void
    {
        Schema::table('exam_subject', function (Blueprint $table) {
            $table->dropColumn('coverage');
        });
    }
};
