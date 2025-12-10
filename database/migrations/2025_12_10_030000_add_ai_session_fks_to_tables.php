<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->foreign('ai_generated_by_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('ai_generated_by_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->foreign('ai_session_id')
                ->references('id')->on('ai_sessions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropForeign(['ai_generated_by_session_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['ai_generated_by_session_id']);
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['ai_session_id']);
        });
    }
};
