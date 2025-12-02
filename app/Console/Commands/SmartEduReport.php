<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SmartEduReport extends Command
{
    protected $signature = 'smartedu:report {--views} {--routes} {--schema} {--migrations}';
    protected $description = 'Comprehensive Smart-Edu system report (migrations, schema, routes, views).';

    public function handle()
    {
        $this->info("====================================================");
        $this->info(" Smart-Edu Comprehensive Report");
        $this->info(" Generated at: " . now()->format('Y-m-d H:i:s'));
        $this->info("====================================================\n");

        $onlySome = $this->option('views') || $this->option('routes') || $this->option('schema') || $this->option('migrations');

        if (!$onlySome || $this->option('migrations')) {
            $this->reportMigrations();
        }

        if (!$onlySome || $this->option('schema')) {
            $this->reportSchema();
        }

        if (!$onlySome || $this->option('routes')) {
            $this->reportRoutes();
        }

        if (!$onlySome || $this->option('views')) {
            $this->reportViewsTree();
        }

        $this->info("\n✅ Report finished.");
        return Command::SUCCESS;
    }

    // --------------------------------------------------
    // 1) MIGRATIONS
    // --------------------------------------------------
    protected function reportMigrations()
    {
        $this->section("MIGRATIONS STATUS");

        // همه فایل‌های migration
        $files = File::files(database_path('migrations'));
        $fileNames = collect($files)->map(fn($f) => pathinfo($f->getFilename(), PATHINFO_FILENAME))->sort()->values();

        // اجراشده‌ها از جدول migrations
        $ran = collect(DB::table('migrations')->pluck('migration'))->sort()->values();

        $rows = [];
        foreach ($fileNames as $name) {
            $rows[] = [
                'migration' => $name,
                'status' => $ran->contains($name) ? '✅ RAN' : '❌ PENDING'
            ];
        }

        $this->table(['Migration', 'Status'], $rows);
        $this->line("");
    }

    // --------------------------------------------------
    // 2) DB SCHEMA
    // --------------------------------------------------
    protected function reportSchema()
    {
        $this->section("DATABASE SCHEMA SNAPSHOT");

        $tables = [
            'questions',
            'exams',
            'attempts',
            'attempt_answers',
            'classrooms',
            'classroom_student',
        ];

        foreach ($tables as $t) {
            if (!Schema::hasTable($t)) {
                $this->warn("Table '{$t}' not found.");
                continue;
            }

            $this->line("— {$t}");

            $columns = Schema::getColumnListing($t);

            $colRows = [];
            foreach ($columns as $c) {
                $type = DB::getSchemaBuilder()->getColumnType($t, $c);

                $nullable = "unknown";
                try {
                    $colInfo = DB::select("SHOW COLUMNS FROM `$t` WHERE Field = ?", [$c]);
                    $nullable = ($colInfo[0]->Null ?? '') === 'YES' ? 'YES' : 'NO';
                    $default = $colInfo[0]->Default ?? null;
                } catch (\Throwable $e) {
                    $default = null;
                }

                $colRows[] = [
                    'column' => $c,
                    'type' => $type,
                    'nullable' => $nullable,
                    'default' => $default ?? '—'
                ];
            }

            $this->table(['Column', 'Type', 'Nullable', 'Default'], $colRows);
            $this->line("");
        }

        // چک ویژه‌ی questions
        $this->section("QUESTIONS TABLE MULTI-TYPE CHECK");

        $mustHave = ['type','score','options','correct_answer','correct_tf','explanation'];
        $has = [];
        foreach ($mustHave as $col) {
            $has[$col] = Schema::hasColumn('questions', $col) ? '✅ yes' : '❌ no';
        }
        $this->table(['Column','Exists?'], collect($has)->map(fn($v,$k)=>['column'=>$k,'exists'=>$v])->values()->all());

        $this->line("");
    }

    // --------------------------------------------------
    // 3) ROUTES SUMMARY
    // --------------------------------------------------
    protected function reportRoutes()
    {
        $this->section("ROUTES SUMMARY (student/teacher exams)");

        $routes = collect(app('router')->getRoutes())->map(function ($r) {
            return [
                'method' => implode('|', $r->methods()),
                'uri' => $r->uri(),
                'name' => $r->getName(),
                'action' => $r->getActionName(),
            ];
        });

        $student = $routes->filter(fn($r) => Str::startsWith($r['name'] ?? '', 'student.exams') || Str::startsWith($r['name'] ?? '', 'student.attempts'));
        $teacher = $routes->filter(fn($r) => Str::startsWith($r['name'] ?? '', 'teacher.exams'));

        $this->line("Student routes:");
        $this->table(['Method','URI','Name','Action'], $student->values()->all());

        $this->line("\nTeacher routes:");
        $this->table(['Method','URI','Name','Action'], $teacher->values()->all());

        $this->line("");
    }

    // --------------------------------------------------
    // 4) VIEWS TREE
    // --------------------------------------------------
    protected function reportViewsTree()
    {
        $this->section("FRONTEND VIEWS TREE");

        $base = resource_path('views/dashboard');
        if (!File::exists($base)) {
            $this->warn("dashboard views folder not found: {$base}");
            return;
        }

        $tree = $this->scanDirTree($base);

        foreach ($tree as $line) {
            $this->line($line);
        }

        $this->line("");
    }

    protected function scanDirTree($path, $prefix = '')
    {
        $lines = [];
        $items = collect(File::directories($path))->merge(File::files($path))
            ->sortBy(fn($i)=>$i);

        foreach ($items as $item) {
            $name = basename($item);
            if (is_dir($item)) {
                $lines[] = $prefix . "📁 " . $name;
                $lines = array_merge($lines, $this->scanDirTree($item, $prefix . "   "));
            } else {
                $lines[] = $prefix . "📄 " . $name;
            }
        }
        return $lines;
    }

    protected function section($title)
    {
        $this->info("\n==================== {$title} ====================\n");
    }
}
