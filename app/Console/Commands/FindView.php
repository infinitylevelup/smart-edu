<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;

class FindView extends Command
{
    protected $signature = 'view:path {name : نام view} {--s|search : جستجوی خودکار}';
    protected $description = 'پیدا کردن مسیر فایل view';

    public function handle()
    {
        $viewName = $this->argument('name');
        $searchMode = $this->option('search');

        try {
            $path = view()->getFinder()->find($viewName);
            $this->info("✅ View پیدا شد:");
            $this->line("<fg=green>$path</>");
        } catch (\Exception $e) {
            $this->error("❌ View '{$viewName}' پیدا نشد!");

            if ($searchMode) {
                $this->info('🔍 در حال جستجوی view های مشابه...');
                $this->searchSimilarViews($viewName);
            } else {
                $this->line("\nبرای جستجوی خودکار از گزینه --search استفاده کنید:");
                $this->line("php artisan view:path {$viewName} --search");
            }
        }

        return 0;
    }

    protected function searchSimilarViews($viewName)
    {
        $viewsPath = resource_path('views');

        // جستجو به دو صورت
        $searchTerms = [
            "*{$viewName}*.blade.php",
            "*" . str_replace('.', '/', $viewName) . "*.blade.php",
            "*" . last(explode('.', $viewName)) . "*.blade.php"
        ];

        $allFiles = [];
        foreach ($searchTerms as $pattern) {
            $files = glob("{$viewsPath}/{$pattern}", GLOB_BRACE);
            $allFiles = array_merge($allFiles, $files);
        }

        $allFiles = array_unique($allFiles);

        if (count($allFiles) > 0) {
            $this->info("\n📁 فایل‌های مشابه پیدا شد: ");
            foreach ($allFiles as $file) {
                // تبدیل مسیر به نام view
                $relativePath = str_replace([$viewsPath . '/', '.blade.php', '/'], ['', '', '.'], $file);
                $this->line("• <fg=cyan>$relativePath</>");
                $this->line("  <fg=gray>$file</>");
            }

            $this->line("\n💡 می‌توانید از نام view کامل استفاده کنید:");
            foreach ($allFiles as $file) {
                $viewName = $this->pathToViewName($file);
                $this->line("php artisan view:path <fg=yellow>$viewName</>");
            }
        } else {
            $this->error('هیچ فایل مشابهی پیدا نشد!');

            // نمایش view های موجود
            $this->info("\n📋 برخی view های موجود:");
            $this->listAvailableViews();
        }
    }

    protected function pathToViewName($path)
    {
        $viewsPath = resource_path('views') . '/';
        return str_replace(
            ['/', '.blade.php'],
            ['.', ''],
            str_replace($viewsPath, '', $path)
        );
    }

    protected function listAvailableViews()
    {
        // می‌توانید این قسمت را بر اساس نیاز توسعه دهید
        $finder = view()->getFinder();
        if ($finder instanceof FileViewFinder) {
            $views = $finder->getPaths();
            foreach ($views as $path) {
                $files = glob($path . '/**/*.blade.php');
                foreach (array_slice($files, 0, 10) as $file) { // فقط ۱۰ تا نمایش بده
                    $viewName = $this->pathToViewName($file);
                    $this->line("• $viewName");
                }
            }
        }
    }
}
