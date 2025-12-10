<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['slug' => 'admin',     'name_fa' => 'مدیر سیستم'],
            ['slug' => 'teacher',   'name_fa' => 'معلم'],
            ['slug' => 'student',   'name_fa' => 'دانش‌آموز'],
            ['slug' => 'counselor', 'name_fa' => 'مشاور'],
        ];

        foreach ($roles as $r) {
            Role::updateOrCreate(
                ['slug' => $r['slug']],
                [
                    // اگر قبلاً وجود داشت uuid همون قبلی بمونه
                    'uuid'      => Role::where('slug', $r['slug'])->value('uuid')
                                   ?? Str::uuid()->toString(),

                    'name'      => $r['name_fa'],
                    'slug'      => $r['slug'],
                    'is_active' => true,
                ]
            );
        }
    }
}
