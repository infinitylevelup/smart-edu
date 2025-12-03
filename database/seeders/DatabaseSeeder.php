<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) roles first
       // $this->call([
      ///      RoleSeeder::class,
      //  ]);

        $this->call([
            TechUsageExamsSeeder::class,
        ]);


        // 2) then users
        User::factory()->count(10)->student()->create();
        User::factory()->count(5)->teacher()->create();
    }
}
