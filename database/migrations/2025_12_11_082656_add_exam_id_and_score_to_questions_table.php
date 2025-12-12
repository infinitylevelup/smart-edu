<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // آزمونی که سوال به آن تعلق دارد
            $table->unsignedBigInteger('exam_id')->nullable()->after('creator_id');

            // نمره سوال (امتیاز)
            $table->unsignedInteger('score')->default(1)->after('difficulty');

            $table->foreign('exam_id')
                ->references('id')->on('exams')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->dropColumn(['exam_id', 'score']);
        });
    }
};
