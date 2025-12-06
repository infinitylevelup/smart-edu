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

            // ✅ PK UUID
            $table->uuid('id')->primary();

            // ✅ FK به fields (UUID)
            $table->uuid('field_id');
            $table->foreign('field_id')
                ->references('id')->on('fields')
                ->cascadeOnDelete();

            // ✅ فیلدهای لازم
            $table->string('slug', 100)->unique();
            $table->string('name_fa', 150);
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subfields');
    }
};
