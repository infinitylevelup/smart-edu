<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("otps", function (Blueprint $table) {
            $table->engine = "InnoDB";

            // ✅ UUID primary key
            $table->uuid("id")->primary();

            $table->string("phone", 15)->index();
            $table->string("code", 6);
            $table->string("token", 255)->nullable();

            // ✅ default گذاشتیم تا اگر نفرستی خطا نده
            $table->enum("type", ["login","register","password_reset"])
                  ->default("login");

            $table->unsignedTinyInteger("attempts")->default(0);
            $table->boolean("verified")->default(false);

            $table->timestamp("expires_at");
            $table->timestamp("verified_at")->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("otps");
    }
};
