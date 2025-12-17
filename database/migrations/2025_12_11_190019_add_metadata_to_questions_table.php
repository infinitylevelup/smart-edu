<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
{
    Schema::table('questions', function (Blueprint $table) {

        if (!Schema::hasColumn('questions', 'subject_id')) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('exam_id');
        }

        if (!Schema::hasColumn('questions', 'difficulty')) {
            $table->unsignedTinyInteger('difficulty')->default(1);
        }

        if (!Schema::hasColumn('questions', 'importance')) {
            $table->unsignedTinyInteger('importance')->default(1);
        }

    });
}


    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropColumn([
                'subject_id',
                'module_label',
                'page_number',
                'difficulty',
                'hint',
                'solution',
                'resource_links',
                'topic_id',
            ]);
        });
    }
};
