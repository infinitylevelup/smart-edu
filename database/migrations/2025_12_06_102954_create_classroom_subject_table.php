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

            $table->string('classroom_id'); // همون نوع classrooms.id
            $table->string('subject_id');   // همون نوع subjects.id

            $table->foreign('classroom_id')
                ->references('id')->on('classrooms')
                ->cascadeOnDelete();

            $table->foreign('subject_id')
                ->references('id')->on('subjects')
                ->cascadeOnDelete();

            $table->primary(['classroom_id', 'subject_id']);
        });



    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_subject');
    }
};
