<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("subjects", function (Blueprint $table) {

            if (!Schema::hasColumn("subjects", "section_id")) {
                $table->char("section_id", 36)
                    ->after("hours")
                    ->index();
            }

            if (!Schema::hasColumn("subjects", "sort_order")) {
                $table->unsignedInteger("sort_order")
                    ->default(0)
                    ->after("description");
            }
        });
    }

    public function down(): void
    {
        Schema::table("subjects", function (Blueprint $table) {

            if (Schema::hasColumn("subjects", "sort_order")) {
                $table->dropColumn("sort_order");
            }

            if (Schema::hasColumn("subjects", "section_id")) {
                $table->dropIndex(["section_id"]);
                $table->dropColumn("section_id");
            }
        });
    }
};
