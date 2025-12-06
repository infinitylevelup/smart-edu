<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// app/Console/Commands/ListViews.php
class ListViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:list {filter? : فیلتر نام view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


public function handle()
{
    $filter = $this->argument('filter');
    $viewsPath = resource_path('views');

    $files = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($viewsPath)
    );

    foreach ($files as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
            $viewName = str_replace(
                [$viewsPath . DIRECTORY_SEPARATOR, '.blade.php', DIRECTORY_SEPARATOR],
                ['', '', '.'],
                $file->getPathname()
            );

            if (!$filter || str_contains($viewName, $filter)) {
                $this->line("• <fg=cyan>$viewName</>");
            }
        }
    }
}




}
