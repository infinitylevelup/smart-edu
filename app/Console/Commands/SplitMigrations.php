<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SplitMigrations extends Command
{
    protected $signature = 'smartedu:split-migrations
                            {source : Path to the monolithic migrations file}
                            {--dry-run : Only show what would be created, do not write files}
                            {--prefix=2025_12_06 : Filename prefix date (YYYY_MM_DD)}';

    protected $description = 'Split a monolithic migrations file into separate Laravel migration files';

    public function handle(): int
    {
        $source = $this->argument('source');
        $dryRun = $this->option('dry-run');
        $prefix = $this->option('prefix') ?: date('Y_m_d');

        if (!File::exists($source)) {
            $this->error("Source file not found: {$source}");
            return self::FAILURE;
        }

        $content = File::get($source);

        // همه‌ی migration های ناشناس را پیدا کن
        $pattern = '/return\s+new\s+class\s+extends\s+Migration\s*\{.*?\n\};/s';
        preg_match_all($pattern, $content, $matches);

        $chunks = $matches[0] ?? [];

        if (count($chunks) === 0) {
            $this->error("No migration blocks found in source file.");
            $this->line("Make sure each migration is like: return new class extends Migration { ... };");
            return self::FAILURE;
        }

        $this->info("Found " . count($chunks) . " migration blocks.");

        $migrationsPath = database_path('migrations');

        if (!File::exists($migrationsPath)) {
            File::makeDirectory($migrationsPath, 0755, true);
        }

        $counter = 100000; // برای HHMMSS ساختگی

        foreach ($chunks as $i => $chunk) {

            // اسم جدول را از Schema::create('table_name' ...) حدس بزن
            $tableName = $this->extractTableName($chunk);

            if (!$tableName) {
                $tableName = "unknown_table_" . ($i + 1);
            }

            // نام فایل استاندارد
            // مثال: 2025_12_06_102953_create_users_table.php
            $timePart = substr((string)$counter, 0, 6);
            $filename = "{$prefix}_{$timePart}_create_{$tableName}_table.php";
            $fullPath = $migrationsPath . DIRECTORY_SEPARATOR . $filename;

            // سر فایل PHP را اگر نیست اضافه کن
            $final = $this->normalizeMigrationPhp($chunk);

            $this->line("→ {$filename}");

            if (!$dryRun) {
                File::put($fullPath, $final);
            }

            $counter += 1;
        }

        if ($dryRun) {
            $this->warn("Dry run mode: no files were written.");
        } else {
            $this->info("Done! Files created in: {$migrationsPath}");
        }

        return self::SUCCESS;
    }

    private function extractTableName(string $chunk): ?string
    {
        // Schema::create('users', function ...
        if (preg_match("/Schema::create\(\s*'([^']+)'\s*,/m", $chunk, $m)) {
            return $m[1];
        }

        // Schema::table('users', function ...
        if (preg_match("/Schema::table\(\s*'([^']+)'\s*,/m", $chunk, $m)) {
            return $m[1] . "_alter";
        }

        return null;
    }

    private function normalizeMigrationPhp(string $chunk): string
    {
        $chunk = trim($chunk);

        // اگر use ها و <?php نداشت، اضافه کن
        if (!Str::startsWith($chunk, '<?php')) {
            $header = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

PHP;
            $chunk = $header . "\n" . $chunk;
        }

        return $chunk . "\n";
    }
}
