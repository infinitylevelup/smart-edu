<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->string('id');
            $table->string('user_id');
            $table->string('section_id');
            $table->string('grade_id');
            $table->string('branch_id');
            $table->string('field_id');
            $table->string('subfield_id');
            $table->string('national_code', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};