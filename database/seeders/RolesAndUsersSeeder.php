<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // اگر می‌خواهید کاربران قبلی پاک شوند:
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'name' => 'مدیر سیستم',
                'email' => 'admin@smart-edu.test',
                'role' => 'admin',
                'password' => '12345678',
            ],
            [
                'name' => 'دانش آموز نمونه',
                'email' => 'student@smart-edu.test',
                'role' => 'student',
                'password' => '12345678',
            ],
            [
                'name' => 'اولیاء نمونه',
                'email' => 'parent@smart-edu.test',
                'role' => 'parent',
                'password' => '12345678',
            ],
            [
                'name' => 'معلم نمونه',
                'email' => 'teacher@smart-edu.test',
                'role' => 'teacher',
                'password' => '12345678',
            ],
            [
                'name' => 'مشاور نمونه',
                'email' => 'counselor@smart-edu.test',
                'role' => 'counselor',
                'password' => '12345678',
            ],
        ];

        foreach ($users as $u) {
            User::query()->create([
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => $u['role'],
                'password' => Hash::make($u['password']),
            ]);
        }
    }
}
