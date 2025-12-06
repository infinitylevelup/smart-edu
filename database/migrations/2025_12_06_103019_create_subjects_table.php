<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->string('id');
            $table->string('title_fa', 200);
            $table->string('code', 50)->nullable();
            $table->unsignedTinyInteger('hours')->nullable();
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('subject_type_id');
            $table->text('description')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};