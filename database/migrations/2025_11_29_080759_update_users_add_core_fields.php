<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // اگر role داری، بعدش قرار بده
            $table->boolean('is_active')->default(true)->after('role');

            $table->timestamp('role_selected_at')->nullable()->after('is_active');

            $table->timestamp('last_login_at')->nullable()->after('role_selected_at');

            // برای سازگاری با auth session، اگر نداری اضافه می‌کنیم
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active','role_selected_at','last_login_at']);

            // remember_token رو اگر می‌خوای نگه داری، حذف نکن
            // $table->dropColumn('remember_token');
        });
    }
};
