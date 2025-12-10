<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            $table->text('bio')->nullable();

            $table->foreignId('main_section_id')->nullable()
                ->constrained('sections')->nullOnDelete();

            $table->foreignId('main_branch_id')->nullable()
                ->constrained('branches')->nullOnDelete();

            $table->foreignId('main_field_id')->nullable()
                ->constrained('fields')->nullOnDelete();

            $table->foreignId('main_subfield_id')->nullable()
                ->constrained('subfields')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
