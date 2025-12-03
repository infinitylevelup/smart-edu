<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'ریاضی'],
            ['title' => 'فیزیک'],
            ['title' => 'شیمی'],
            ['title' => 'زیست'],
            ['title' => 'ادبیات'],
        ];

        foreach ($items as $it) {
            Subject::firstOrCreate($it);
        }
    }
}
