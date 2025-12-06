<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id')->primary();          // ✅ مهم
            $table->string('slug', 100)->unique();    // بهتره یکتا باشه
            $table->string('name_fa', 150);

            $table->unsignedInteger('sort_order')->default(0); // قبلاً string بود

            $table->boolean('is_active')->default(true);       // قبلاً بدون default بود

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
