<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn("subjects", "slug")) {
                $table->string("slug", 255)->after("title_fa")->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn("subjects", "slug")) {
                $table->dropUnique(["slug"]);
                $table->dropColumn("slug");
            }
        });
    }
};
