<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
        public function run(): void
        {
            \App\Models\User::factory()->count(10)->student()->create();
            \App\Models\User::factory()->count(5)->teacher()->create();
        }

}
