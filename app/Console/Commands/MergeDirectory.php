<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class MergeDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merge:dir
                            {directory : Directory path to scan (relative or absolute)}
                            {--o|output= : Output filename}
                            {--p|project= : Project name for header}
                            {--extensions= : Comma-separated list of extensions to include (e.g., php,blade.php,js)}
                            {--exclude= : Comma-separated list of directories/files to exclude}
                            {--depth=0 : Maximum depth (0 for unlimited)}
                            {--no-subdirs : Do not scan subdirectories}
                            {--pattern= : Pattern to match in filenames (e.g., *classroom*)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge all files from a directory and its subdirectories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = $this->argument('directory');

        // Convert relative path to absolute if needed
        if (!str_starts_with($directory, '/') && !str_starts_with($directory, 'C:\\')) {
            $directory = base_path($directory);
        }

        if (!is_dir($directory)) {
            $this->error("❌ Directory '{$directory}' not found!");
            $this->line("💡 Make sure the directory exists and path is correct.");
            return Command::FAILURE;
        }

        // Get all files
        $files = $this->scanDirectory($directory);

        if (empty($files)) {
            $this->error("❌ No files found in directory '{$directory}'!");
            return Command::FAILURE;
        }

        // Determine output filename
        $outputFile = $this->getOutputFileName($directory);

        $this->info("📁 Scanning directory for files");
        $this->line("📍 Directory: " . realpath($directory));
        $this->line("🔍 Files found: " . count($files));
        $this->line("💾 Output file: " . $outputFile);
        $this->line(str_repeat("─", 60));

        // Display file list
        $this->displayFileList($files, $directory);

        // Ask for confirmation if many files
        if (count($files) > 50) {
            if (!$this->confirm("⚠️  Found " . count($files) . " files. Continue merging?", true)) {
                $this->info("Operation cancelled.");
                return Command::SUCCESS;
            }
        }

        // Merge files
        $result = $this->mergeFilesFromDirectory($files, $outputFile, $directory);

        // Display result
        $this->displayResult($result, $outputFile);

        return $result['success'] ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Scan directory for files
     */
    private function scanDirectory(string $directory): array
    {
        $exclude = $this->option('exclude') ? explode(',', $this->option('exclude')) : [];
        $extensions = $this->option('extensions') ? explode(',', $this->option('extensions')) : [];
        $maxDepth = (int) $this->option('depth');
        $noSubdirs = $this->option('no-subdirs');
        $pattern = $this->option('pattern');

        $files = [];

        if ($noSubdirs) {
            // Scan only current directory
            $items = scandir($directory);

            foreach ($items as $item) {
                if ($item === '.' || $item === '..') {
                    continue;
                }

                $fullPath = $directory . '/' . $item;

                if (is_file($fullPath)) {
                    if ($this->shouldIncludeFile($fullPath, $extensions, $pattern, $exclude)) {
                        $files[] = $fullPath;
                    }
                }
            }
        } else {
            // Scan recursively
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            if ($maxDepth > 0) {
                $iterator->setMaxDepth($maxDepth);
            }

            foreach ($iterator as $item) {
                if ($item->isFile()) {
                    $fullPath = $item->getPathname();

                    if ($this->shouldIncludeFile($fullPath, $extensions, $pattern, $exclude)) {
                        $files[] = $fullPath;
                    }
                }
            }
        }

        // Sort files alphabetically
        sort($files);

        return $files;
    }

    /**
     * Check if file should be included
     */
    private function shouldIncludeFile(string $file, array $extensions, ?string $pattern, array $exclude): bool
    {
        // Check if file is in exclude list
        foreach ($exclude as $excluded) {
            $excluded = trim($excluded);
            if (str_contains($file, $excluded)) {
                return false;
            }
        }

        // Check extensions
        if (!empty($extensions)) {
            $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
            $hasValidExtension = false;

            foreach ($extensions as $ext) {
                $ext = trim($ext);
                if ($fileExtension === $ext ||
                    ($ext === 'blade.php' && str_ends_with($file, '.blade.php'))) {
                    $hasValidExtension = true;
                    break;
                }
            }

            if (!$hasValidExtension) {
                return false;
            }
        }

        // Check pattern
        if ($pattern) {
            $filename = basename($file);
            if (!fnmatch($pattern, $filename)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Display file list
     */
    private function displayFileList(array $files, string $baseDir): void
    {
        $this->info("📋 Found files:");

        $groupedByExtension = [];
        $totalSize = 0;

        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ($extension === 'php' && str_ends_with($file, '.blade.php')) {
                $extension = 'blade.php';
            }

            if (!isset($groupedByExtension[$extension])) {
                $groupedByExtension[$extension] = [];
            }

            $relativePath = str_replace($baseDir . '/', '', $file);
            $fileSize = filesize($file);
            $totalSize += $fileSize;

            $groupedByExtension[$extension][] = [
                'path' => $relativePath,
                'size' => $fileSize
            ];
        }

        // Sort extensions alphabetically
        ksort($groupedByExtension);

        foreach ($groupedByExtension as $extension => $extensionFiles) {
            $this->line("\n🔤 .{$extension} (" . count($extensionFiles) . " files):");

            foreach ($extensionFiles as $index => $fileInfo) {
                $sizeFormatted = $this->formatBytes($fileInfo['size']);
                $this->line("  " . ($index + 1) . ". {$fileInfo['path']} ({$sizeFormatted})");
            }
        }

        $this->line("\n📊 Total: " . count($files) . " files, " . $this->formatBytes($totalSize));
    }

    /**
     * Merge files from directory
     */
    private function mergeFilesFromDirectory(array $files, string $outputFile, string $directory): array
    {
        $projectName = $this->option('project') ?: config('app.name', 'Laravel Project');

        $content = "📦 " . strtoupper($projectName) . "\n";
        $content .= "📁 Directory: " . str_replace(base_path() . '/', '', realpath($directory)) . "\n";
        $content .= "👤 User: " . get_current_user() . "\n";
        $content .= "📅 Generated: " . now()->format('Y/m/d - H:i:s') . "\n";
        $content .= "📊 Total files: " . count($files) . "\n";
        $content .= str_repeat("✨", 30) . "\n\n";

        $processed = 0;
        $totalSize = 0;
        $errors = [];

        $progressBar = $this->output->createProgressBar(count($files));
        $progressBar->setFormat(" %current%/%max% [%bar%] %percent:3s%%\n %message%");
        $progressBar->setMessage('Starting...');
        $progressBar->start();

        foreach ($files as $index => $file) {
            $fileNumber = $index + 1;
            $relativePath = str_replace($directory . '/', '', $file);

            try {
                $fileContent = File::get($file);
                $fileSize = filesize($file);
                $totalSize += $fileSize;

                // Create file header
                $content .= $this->createFileHeader($fileNumber, $relativePath, $file, $fileSize);

                // Add file content
                $content .= $fileContent;

                // Add separator
                if ($fileNumber < count($files)) {
                    $content .= "\n\n" . str_repeat("═", 80) . "\n\n";
                }

                $processed++;

                // Update progress bar
                $progressBar->setMessage("Processing: " . basename($file));
                $progressBar->advance();

            } catch (\Exception $e) {
                $errors[] = "Error reading {$relativePath}: " . $e->getMessage();
                $content .= $this->createErrorHeader($fileNumber, $relativePath, $e->getMessage());
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Add summary
        $content .= $this->createSummary($processed, count($files), $totalSize, $errors, $directory);

        // Save file
        try {
            File::put($outputFile, $content);
            return [
                'success' => true,
                'processed' => $processed,
                'total_files' => count($files),
                'total_size' => $totalSize,
                'errors' => $errors,
                'output_file' => $outputFile
            ];
        } catch (\Exception $e) {
            $errors[] = "Error saving output file: " . $e->getMessage();
            return [
                'success' => false,
                'errors' => $errors
            ];
        }
    }

    /**
     * Create file header
     */
    private function createFileHeader(int $number, string $relativePath, string $fullPath, int $size): string
    {
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $lastModified = date('Y/m/d H:i:s', filemtime($fullPath));

        $header = "📄 File {$number}: " . basename($fullPath) . "\n";
        $header .= "📍 Path: {$relativePath}\n";
        $header .= "📊 Size: " . $this->formatBytes($size) . "\n";
        $header .= "🔤 Type: .{$extension}\n";
        $header .= "🕒 Last modified: {$lastModified}\n";
        $header .= str_repeat("─", 60) . "\n\n";

        return $header;
    }

    /**
     * Create error header
     */
    private function createErrorHeader(int $number, string $relativePath, string $error): string
    {
        $header = "❌ ERROR - File {$number}\n";
        $header .= "📍 Path: {$relativePath}\n";
        $header .= "⚠️  Error: {$error}\n";
        $header .= str_repeat("─", 60) . "\n\n";

        return $header;
    }

    /**
     * Create summary
     */
    private function createSummary(int $processed, int $total, int $totalSize, array $errors, string $directory): string
    {
        $successRate = $total > 0 ? round(($processed / $total) * 100, 2) : 0;

        $summary = "\n" . str_repeat("=", 70) . "\n";
        $summary .= "📊 DIRECTORY SCAN SUMMARY\n";
        $summary .= str_repeat("-", 70) . "\n";
        $summary .= "📍 Directory: " . str_replace(base_path() . '/', '', realpath($directory)) . "\n";
        $summary .= "📁 Total files found: {$total}\n";
        $summary .= "✅ Files processed: {$processed}\n";
        $summary .= "💾 Total size: " . $this->formatBytes($totalSize) . "\n";
        $summary .= "📈 Success rate: {$successRate}%\n";
        $summary .= "⏱️  Completion time: " . now()->format('H:i:s') . "\n";

        if (!empty($errors)) {
            $summary .= "\n⚠️  ERRORS (" . count($errors) . "):\n";
            foreach ($errors as $index => $error) {
                $summary .= "  " . ($index + 1) . ". {$error}\n";
            }
        } else {
            $summary .= "\n🎉 All files were processed successfully!\n";
        }

        $summary .= str_repeat("=", 70) . "\n";
        $summary .= "🏁 End of report - " . now()->format('Y/m/d') . "\n";

        return $summary;
    }

    /**
     * Display result
     */
    private function displayResult(array $result, string $outputFile): void
    {
        $this->newLine();
        $this->line(str_repeat("✨", 25));

        if ($result['success']) {
            $this->info("✅ Operation completed successfully!");
            $this->line("📁 Output file: " . realpath($outputFile));
            $this->line("📊 Output size: " . $this->formatBytes(filesize($outputFile)));
            $this->line("📈 Files processed: " . $result['processed'] . " of " . $result['total_files']);
            $this->line("💾 Total source size: " . $this->formatBytes($result['total_size']));

            if (!empty($result['errors'])) {
                $this->warn("⚠️  Errors: " . count($result['errors']));
                foreach ($result['errors'] as $error) {
                    $this->line("   • " . $error);
                }
            }
        } else {
            $this->error("❌ Operation failed!");
            foreach ($result['errors'] as $error) {
                $this->line("   • " . $error);
            }
        }

        $this->line(str_repeat("✨", 25));
    }

    /**
     * Determine output filename
     */
    private function getOutputFileName(string $directory): string
    {
        if ($this->option('output')) {
            return $this->option('output');
        }

        $dirName = basename($directory);
        $timestamp = date('Ymd_His');

        return "{$dirName}_merged_{$timestamp}.txt";
    }

    /**
     * Format file size
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
