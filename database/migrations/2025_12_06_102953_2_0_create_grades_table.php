<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ✅ PK UUID
            $table->uuid('id')->primary();

            // ✅ FK به sections (UUID)
            $table->uuid('section_id');
            $table->foreign('section_id')
                ->references('id')->on('sections')
                ->cascadeOnDelete();

            // ✅ اطلاعات پایه
            $table->string('value', 50);      // مثل "10", "11", "12" یا "7", "8", ...
            $table->string('name_fa', 150);   // مثل "دهم", "یازدهم"
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
