<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
        $table->engine = 'InnoDB';

        $table->string('id')->primary();   // ✅ حتماً

        // ستون‌های خودت...
        // احتمالاً اینا رو داری:
        // $table->string('section_id');
        // $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();

        $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
