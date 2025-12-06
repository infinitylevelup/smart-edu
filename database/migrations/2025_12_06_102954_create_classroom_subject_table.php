<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_subject', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ✅ FKها UUID
            $table->uuid('classroom_id');
            $table->uuid('subject_id');

            $table->foreign('classroom_id')
                ->references('id')->on('classrooms')
                ->cascadeOnDelete();

            $table->foreign('subject_id')
                ->references('id')->on('subjects')
                ->cascadeOnDelete();

            // ✅ کلید مرکب
            $table->primary(['classroom_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_subject');
    }
};
