<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id')->primary();   // ✅ خیلی مهم

            $table->string('title_fa', 200);
            $table->string('code', 50)->nullable();
            $table->unsignedTinyInteger('hours')->nullable();

            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id')->nullable();      // اگر optionalه بهتره nullable
            $table->string('subject_type_id')->nullable();  // optional

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // اگر این FKها رو داری، نوعشون باید با جداول مقصد یکی باشه
            $table->foreign('grade_id')->references('id')->on('grades')->restrictOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->restrictOnDelete();
            $table->foreign('field_id')->references('id')->on('fields')->restrictOnDelete();
            $table->foreign('subfield_id')->references('id')->on('subfields')->nullOnDelete();
            $table->foreign('subject_type_id')->references('id')->on('subject_types')->nullOnDelete();

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
