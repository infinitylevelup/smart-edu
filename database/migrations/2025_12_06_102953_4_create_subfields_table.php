<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subfields', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id')->primary();   // ✅ خیلی مهم

            // اگر field_id داری (که باید داشته باشی):
            $table->string('field_id');
            $table->foreign('field_id')
                ->references('id')->on('fields')
                ->cascadeOnDelete();

            // بقیه ستون‌های خودت...
            // $table->string('slug', 100)->unique();
            // $table->string('name_fa', 150);
            // $table->unsignedInteger('sort_order')->default(0);
            // $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('subfields');
    }
};
