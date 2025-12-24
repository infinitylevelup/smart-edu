<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateEduTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // اول جداول وابسته (children) سپس والدها (parents)
        $tables = [
            'competencies',
            'modules',
            'subjects',
            'subject_types',
            'subfields',
            'fields',
            'branches',
            'grades',
            'sections',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
