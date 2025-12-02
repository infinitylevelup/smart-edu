<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ExportMigrationsToSingleFile extends Command
{
    protected $signature = 'migrations:export-one
                            {--output=storage/migrations_all_in_one.txt : Output file path}';

    protected $description = 'Read all migration files in database/migrations in order and save them into one file with smart headers.';

    public function handle()
    {
        $migrationsPath = database_path('migrations');
        $outputPath = base_path($this->option('output'));

        if (!is_dir($migrationsPath)) {
            $this->error("Migrations directory not found: {$migrationsPath}");
            return Command::FAILURE;
        }

        $files = collect(glob($migrationsPath . DIRECTORY_SEPARATOR . '*.php'))
            ->sort() // sort by filename => chronological
            ->values();

        if ($files->isEmpty()) {
            $this->warn("No migration files found.");
            return Command::SUCCESS;
        }

        $out = [];
        $out[] = "============================================================";
        $out[] = " ALL MIGRATIONS - SINGLE FILE EXPORT";
        $out[] = " Generated at: " . now();
        $out[] = " Source path: {$migrationsPath}";
        $out[] = "============================================================\n";

        foreach ($files as $index => $filePath) {
            $fileName = basename($filePath);
            $content = file_get_contents($filePath);

            $smartDesc = $this->makeSmartDescription($fileName, $content);

            $out[] = "############################################################";
            $out[] = "# MIGRATION (" . ($index + 1) . "): {$fileName}";
            $out[] = "# توضیح هوشمند: {$smartDesc}";
            $out[] = "############################################################\n";
            $out[] = $content . "\n";
            $out[] = "-------------------- پایان مایگریشن: {$fileName} --------------------";
            $out[] = "متوجه شدیم ✅\n\n";
        }

        file_put_contents($outputPath, implode("\n", $out));

        $this->info("Done! Exported " . $files->count() . " migrations to:");
        $this->line($outputPath);

        return Command::SUCCESS;
    }

    /**
     * Create a smart Persian description based on filename + content.
     */
    private function makeSmartDescription(string $fileName, string $content): string
    {
        $name = Str::lower($fileName);

        // Base guess from filename
        $desc = [];

        if (Str::contains($name, 'create_')) $desc[] = "ایجاد جدول/ساختار جدید";
        if (Str::contains($name, 'add_')) $desc[] = "افزودن ستون/رابطه جدید";
        if (Str::contains($name, 'update_') || Str::contains($name, 'modify_')) $desc[] = "به‌روزرسانی/تغییر ساختار موجود";
        if (Str::contains($name, 'enum')) $desc[] = "تنظیم یا تغییر enum";
        if (Str::contains($name, 'nullable')) $desc[] = "nullable کردن ستون";
        if (Str::contains($name, 'foreign') || Str::contains($content, 'foreignId')) $desc[] = "تعریف کلید خارجی";
        if (Str::contains($name, 'pivot') || Str::contains($name, 'student') && Str::contains($name, 'classroom')) $desc[] = "جدول واسط/ارتباط چندبه‌چند";

        // Add hints from content
        if (Str::contains($content, "Schema::create")) $desc[] = "این مایگریشن جدول می‌سازد";
        if (Str::contains($content, "Schema::table")) $desc[] = "این مایگریشن جدول موجود را تغییر می‌دهد";
        if (Str::contains($content, "->constrained")) $desc[] = "دارای constraint روی FK";
        if (Str::contains($content, "->change()")) $desc[] = "تغییر روی ستون قبلی اعمال می‌کند";
        if (Str::contains($content, "dropColumn")) $desc[] = "rollback ستون‌ها را حذف می‌کند";

        if (empty($desc)) {
            return "مایگریشن عمومی (نیاز به بررسی دستی)";
        }

        // Unique + join
        $desc = array_values(array_unique($desc));
        return implode(" | ", $desc);
    }
}
