<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("subjects", function (Blueprint $table) {

            if (!Schema::hasColumn("subjects", "slug")) {
                $table->string("slug", 255)->after("title_fa")->unique();
            }

            if (!Schema::hasColumn("subjects", "code")) {
                $table->string("code", 50)->nullable()->after("slug");
            }

            if (!Schema::hasColumn("subjects", "hours")) {
                $table->unsignedInteger("hours")->nullable()->default(0)->after("code");
            }

            if (!Schema::hasColumn("subjects", "sort_order")) {
                $table->unsignedInteger("sort_order")->nullable()->default(0)->after("hours");
            }

            if (!Schema::hasColumn("subjects", "is_active")) {
                $table->boolean("is_active")->default(1)->after("sort_order");
            }

        });
    }

    public function down(): void
    {
        Schema::table("subjects", function (Blueprint $table) {

            if (Schema::hasColumn("subjects", "is_active")) {
                $table->dropColumn("is_active");
            }

            if (Schema::hasColumn("subjects", "sort_order")) {
                $table->dropColumn("sort_order");
            }

            if (Schema::hasColumn("subjects", "hours")) {
                $table->dropColumn("hours");
            }

            if (Schema::hasColumn("subjects", "code")) {
                $table->dropColumn("code");
            }

            if (Schema::hasColumn("subjects", "slug")) {
                $table->dropUnique(["slug"]);
                $table->dropColumn("slug");
            }

        });
    }
};
