<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,          // اول نقش‌ها
            TechUsageExamsSeeder::class,// بعد دیتاهای وابسته
            TaxonomySeeder::class,     // سپس داده‌های طبقه‌بندی
            // بقیه seeders اگر داری
        ]);
    }
}
