<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_types', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id')->primary();   // ✅ خیلی مهم

            // ستون‌های خودت...
            // مثال رایج:
            // $table->string('slug', 100)->unique();
            // $table->string('name_fa', 150);
            // $table->unsignedInteger('sort_order')->default(0);
            // $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('subject_types');
    }
};
