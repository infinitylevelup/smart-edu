<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'title' => 'مدیر سیستم'],
            ['name' => 'teacher', 'title' => 'معلم'],
            ['name' => 'student', 'title' => 'دانش‌آموز'],
            ['name' => 'counselor', 'title' => 'مشاور'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r['name']], $r);
        }
    }
}
