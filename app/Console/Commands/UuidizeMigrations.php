<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UuidizeMigrations extends Command
{
    protected $signature = 'smartedu:uuidize
                            {--fix : Apply fixes directly to migration files}
                            {--backup : Create backup copies before fixing}';

    protected $description = 'Scan (and optionally fix) migrations to convert all ids/FKs to UUID consistently.';

    public function handle()
    {
        $path = database_path('migrations');
        $files = File::files($path);

        $fix = (bool) $this->option('fix');
        $backup = (bool) $this->option('backup');

        $report = [];
        $changedFiles = 0;

        foreach ($files as $file) {
            $content  = File::get($file->getPathname());
            $original = $content;

            $fileReport = [];

            // ---------------------------------------------------
            // 1) id primary: $table->string('id');  => uuid('id')->primary();
            // ---------------------------------------------------
            if (
                preg_match('/->string\(\s*[\'"]id[\'"]\s*\)(?!->primary\(\))/', $content) ||
                preg_match('/\$table->string\(\s*[\'"]id[\'"]\s*\)\s*;/', $content)
            ) {
                $fileReport[] = "Found string('id') (maybe without primary)";
                if ($fix) {
                    $content = preg_replace(
                        '/\$table->string\(\s*[\'"]id[\'"]\s*\)\s*;(.*)?/m',
                        "\$table->uuid('id')->primary();$1",
                        $content
                    );
                }
            }

            // ---------------------------------------------------
            // 2) $table->string('id')->primary();  => uuid('id')->primary();
            // ---------------------------------------------------
            if (preg_match('/\$table->string\(\s*[\'"]id[\'"]\s*\)->primary\(\)\s*;/', $content)) {
                $fileReport[] = "Found string('id')->primary()";
                if ($fix) {
                    $content = preg_replace(
                        '/\$table->string\(\s*[\'"]id[\'"]\s*\)->primary\(\)\s*;/m',
                        "\$table->uuid('id')->primary();",
                        $content
                    );
                }
            }

            // ---------------------------------------------------
            // 3) FK columns: string('xxx_id') -> uuid('xxx_id')
            // ---------------------------------------------------
            if (preg_match_all('/\$table->string\(\s*[\'"]([a-z_]*_id)[\'"]\s*\)(->nullable\(\))?\s*;/m', $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $col = $m[1];
                    $nullable = $m[2] ?? '';

                    // ignore non-fk-ish special cases
                    if (in_array($col, ['join_code', 'code', 'token'])) {
                        continue;
                    }

                    $fileReport[] = "Found string FK: {$col}";

                    if ($fix) {
                        $content = preg_replace(
                            '/\$table->string\(\s*[\'"]' . preg_quote($col, '/') . '[\'"]\s*\)' . preg_quote($nullable, '/') . '\s*;/m',
                            "\$table->uuid('{$col}'){$nullable};",
                            $content
                        );
                    }
                }
            }

            // ---------------------------------------------------
            // 4) Duplicate user_id (uuid + string)
            // ---------------------------------------------------
            if (substr_count($content, "user_id") >= 2) {
                if (preg_match('/\$table->uuid\(\s*[\'"]user_id[\'"]\s*\).*?\n.*?\$table->string\(\s*[\'"]user_id[\'"]\s*\)\s*;/s', $content)) {
                    $fileReport[] = "Duplicate user_id detected (uuid + string)";
                    if ($fix) {
                        $content = preg_replace(
                            '/^\s*\$table->string\(\s*[\'"]user_id[\'"]\s*\)\s*;.*\n/m',
                            '',
                            $content
                        );
                    }
                }
            }

            // ---------------------------------------------------
            // Save if changed
            // ---------------------------------------------------
            if ($fix && $content !== $original) {
                if ($backup) {
                    $backupPath = $file->getPathname() . '.bak.' . now()->format('Ymd_His');
                    File::put($backupPath, $original);
                }
                File::put($file->getPathname(), $content);
                $changedFiles++;
            }

            if (!empty($fileReport)) {
                $report[] = [
                    'file' => $file->getFilename(),
                    'issues' => $fileReport,
                ];
            }
        }

        // ---------------------------------------------------
        // Print report
        // ---------------------------------------------------
        if (empty($report)) {
            $this->info("✅ All migrations look UUID-consistent.");
            return 0;
        }

        $this->warn("Found UUID consistency issues:\n");
        foreach ($report as $r) {
            $this->line("📄 " . $r['file']);
            foreach ($r['issues'] as $issue) {
                $this->line("   - " . $issue);
            }
            $this->line("");
        }

        if ($fix) {
            $this->info("✅ Fix applied. Changed files: {$changedFiles}");
        } else {
            $this->info("Run again with --fix (and optionally --backup) to auto-apply changes:");
            $this->line("php artisan smartedu:uuidize --fix --backup");
        }

        return 0;
    }
}
