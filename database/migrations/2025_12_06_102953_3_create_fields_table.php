<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('id')->primary();

            $table->string('branch_id');
            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->string('sort_order')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('branch_id')
                ->references('id')->on('branches')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
