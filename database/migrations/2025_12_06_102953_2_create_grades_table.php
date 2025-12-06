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

            $table->string('id')->primary();  // ✅ حتماً

            // بقیه ستون‌های خودت...
            // مثال:
            // $table->string('slug', 100)->unique();
            // $table->string('name_fa', 150);
            // $table->string('section_id');  // چون section_id هم string است
            // $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
