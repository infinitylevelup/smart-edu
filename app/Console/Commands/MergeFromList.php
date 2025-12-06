<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MergeFromList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merge:list
                            {list-file : Path to file containing list of files}
                            {--o|output= : Output filename (default: list-file-name_merged.txt)}
                            {--p|project= : Project name for header}
                            {--no-comments : Ignore comment lines}
                            {--no-wildcard : Disable wildcard support}
                            {--group : Group files by type}
                            {--skip-missing : Skip missing files without error}
                            {--details : Show detailed information}'; // Changed from --verbose to --details

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge files from a list file with missing file handling';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $listFile = $this->argument('list-file');

        if (!file_exists($listFile)) {
            $this->error("❌ List file '{$listFile}' not found!");
            return Command::FAILURE;
        }

        // Read list file
        $files = $this->readListFile($listFile);

        if (empty($files)) {
            $this->error("❌ No valid files found in the list!");
            return Command::FAILURE;
        }

        // Determine output filename
        $outputFile = $this->getOutputFileName($listFile);

        $this->info("📁 Starting file merge from list");
        $this->line("📋 List file: " . realpath($listFile));
        $this->line("🔍 Total items in list: " . count($files));
        $this->line("💾 Output file: " . $outputFile);
        $this->line(str_repeat("─", 60));

        // Display file list
        $this->displayFileList($files);

        // Merge files
        $result = $this->mergeFilesFromList($files, $outputFile, $listFile);

        // Display result
        $this->displayResult($result, $outputFile);

        return $result['success'] ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * Read list file
     */
    private function readListFile(string $listFile): array
    {
        $content = file_get_contents($listFile);
        $lines = explode("\n", $content);

        $files = [];
        $currentSection = 'General';
        $skipComments = $this->option('no-comments');

        foreach ($lines as $line) {
            $line = trim($line);

            // Empty line
            if (empty($line)) {
                continue;
            }

            // Comment (starts with # or //)
            if (!$skipComments && (str_starts_with($line, '#') || str_starts_with($line, '//'))) {
                // Detect section title
                if (preg_match('/^#+\s*(.+)/', $line, $matches)) {
                    $currentSection = trim($matches[1]);
                }
                continue;
            }

            // Add file to list
            $files[] = [
                'pattern' => $line,
                'section' => $currentSection,
                'is_wildcard' => str_contains($line, '*') && !$this->option('no-wildcard')
            ];
        }

        return $files;
    }

    /**
     * Display file list
     */
    private function displayFileList(array $files): void
    {
        $this->info("📋 Files found:");

        $groupedFiles = [];
        $missingFiles = [];

        foreach ($files as $file) {
            $matchedFiles = $this->expandPattern($file['pattern'], $file['is_wildcard']);

            if (empty($matchedFiles)) {
                $missingFiles[] = $file['pattern'];
            }

            foreach ($matchedFiles as $matchedFile) {
                $groupedFiles[$file['section']][] = $matchedFile;
            }
        }

        $totalFiles = 0;

        // Display found files
        foreach ($groupedFiles as $section => $sectionFiles) {
            $this->line("\n📁 {$section} (" . count($sectionFiles) . " files):");
            $totalFiles += count($sectionFiles);

            foreach ($sectionFiles as $index => $file) {
                $status = '✅';
                $this->line("  {$status} " . ($index + 1) . ". " .
                    str_replace(base_path() . '/', '', $file));
            }
        }

        // Display missing files
        if (!empty($missingFiles) && $this->option('details')) {
            $this->line("\n⚠️  Missing files:");
            foreach ($missingFiles as $index => $pattern) {
                $this->line("  ❌ " . ($index + 1) . ". " . $pattern);
            }
        }

        $this->line("\n📊 Total found: {$totalFiles} files");
        $this->line("❌ Missing: " . count($missingFiles) . " patterns");
    }

    /**
     * Expand pattern (wildcard support)
     */
    private function expandPattern(string $pattern, bool $isWildcard): array
    {
        if (!$isWildcard) {
            return [ $pattern ];
        }

        $files = glob($pattern, GLOB_BRACE);
        return $files ?: [];
    }

    /**
     * Merge files from list
     */
    private function mergeFilesFromList(array $files, string $outputFile, string $listFile): array
    {
        $content = $this->createHeader($listFile);

        $processed = 0;
        $totalFound = 0;
        $totalMissing = 0;
        $errors = [];
        $missingFiles = [];
        $groupedContent = [];

        $groupByType = $this->option('group');
        $skipMissing = $this->option('skip-missing');
        $showDetails = $this->option('details');

        foreach ($files as $fileInfo) {
            $matchedFiles = $this->expandPattern($fileInfo['pattern'], $fileInfo['is_wildcard']);

            if (empty($matchedFiles)) {
                $totalMissing++;
                $missingFiles[] = $fileInfo['pattern'];

                if (!$skipMissing) {
                    $content .= $this->createMissingFileHeader($fileInfo['pattern'], $fileInfo['section']);
                }
                continue;
            }

            foreach ($matchedFiles as $file) {
                $totalFound++;

                if (!file_exists($file)) {
                    $totalMissing++;
                    $missingFiles[] = $file;

                    if (!$skipMissing) {
                        $content .= $this->createMissingFileHeader($file, $fileInfo['section']);
                    }
                    continue;
                }

                if (!is_readable($file)) {
                    $errors[] = "Cannot read file: {$file}";
                    $content .= $this->createErrorHeader($file, "File is not readable");
                    continue;
                }

                try {
                    $fileContent = File::get($file);
                    $fileHeader = $this->createFileHeader($file, $fileInfo['section']);

                    if ($groupByType) {
                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                        $groupKey = $this->getFileGroup($extension, $fileInfo['section']);

                        if (!isset($groupedContent[$groupKey])) {
                            $groupedContent[$groupKey] = '';
                        }

                        $groupedContent[$groupKey] .= $fileHeader . $fileContent . "\n\n" . str_repeat("─", 70) . "\n\n";
                    } else {
                        $content .= $fileHeader . $fileContent . "\n\n" . str_repeat("═", 80) . "\n\n";
                    }

                    $processed++;

                    // Show progress
                    if ($showDetails) {
                        $this->output->write("  📄 Processing file {$totalFound}: " . basename($file) . "\r");
                    }

                } catch (\Exception $e) {
                    $errors[] = "Error reading file {$file}: " . $e->getMessage();
                    $content .= $this->createErrorHeader($file, $e->getMessage());
                }
            }
        }

        if ($showDetails) {
            $this->newLine();
        }

        // If grouping is active, organize content
        if ($groupByType && !empty($groupedContent)) {
            ksort($groupedContent);
            foreach ($groupedContent as $group => $groupContent) {
                $content .= "📁 " . strtoupper($group) . "\n" . str_repeat("=", 60) . "\n\n";
                $content .= $groupContent;
            }
        }

        // Add summary
        $content .= $this->createSummary($processed, $totalFound, $totalMissing, $errors, $missingFiles, $listFile);

        // Save file
        try {
            File::put($outputFile, $content);
            return [
                'success' => true,
                'processed' => $processed,
                'total_found' => $totalFound,
                'total_missing' => $totalMissing,
                'missing_files' => $missingFiles,
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
     * Create header for report
     */
    private function createHeader(string $listFile): string
    {
        $projectName = $this->option('project') ?: config('app.name', 'Laravel Project');
        $listFileName = basename($listFile);

        $header = "📦 " . strtoupper($projectName) . "\n";
        $header .= "📋 Merged from list file: {$listFileName}\n";
        $header .= "👤 User: " . get_current_user() . "\n";
        $header .= "🖥️  System: " . php_uname('s') . " " . php_uname('r') . "\n";
        $header .= "📅 Generated: " . now()->format('Y/m/d - H:i:s') . "\n";
        $header .= str_repeat("✨", 30) . "\n\n";

        return $header;
    }

    /**
     * Create header for each file
     */
    private function createFileHeader(string $file, string $section): string
    {
        $relativePath = str_replace(base_path() . '/', '', $file);
        $fileName = basename($file);
        $fileSize = filesize($file);
        $lastModified = date('Y/m/d H:i:s', filemtime($file));
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $header = "📄 File: {$fileName}\n";
        $header .= "📍 Path: {$relativePath}\n";
        $header .= "📁 Section: {$section}\n";
        $header .= "📊 Size: " . $this->formatBytes($fileSize) . "\n";
        $header .= "🔤 Type: .{$extension}\n";
        $header .= "🕒 Last modified: {$lastModified}\n";
        $header .= str_repeat("─", 60) . "\n\n";

        return $header;
    }

    /**
     * Create header for missing file
     */
    private function createMissingFileHeader(string $filePattern, string $section): string
    {
        $header = "❌ MISSING FILE\n";
        $header .= "📍 Pattern: {$filePattern}\n";
        $header .= "📁 Section: {$section}\n";
        $header .= "⚠️  Status: File does not exist\n";
        $header .= str_repeat("─", 60) . "\n";
        $header .= "This file was listed but could not be found in the system.\n";
        $header .= "Please check if the file exists or the path is correct.\n";
        $header .= str_repeat("─", 60) . "\n\n";

        return $header;
    }

    /**
     * Create error header
     */
    private function createErrorHeader(string $file, string $errorMessage): string
    {
        $header = "⚠️  ERROR READING FILE\n";
        $header .= "📍 File: {$file}\n";
        $header .= "❌ Error: {$errorMessage}\n";
        $header .= str_repeat("─", 60) . "\n\n";

        return $header;
    }

    /**
     * Group files
     */
    private function getFileGroup(string $extension, string $section): string
    {
        $groups = [
            'php' => ['controller', 'model', 'service', 'request', 'migration', 'seeder'],
            'blade.php' => ['view', 'layout', 'template'],
            'js' => ['javascript', 'script'],
            'css' => ['stylesheet', 'style'],
            'scss' => ['stylesheet', 'sass'],
            'json' => ['config', 'setting'],
            'sql' => ['database', 'schema'],
            'md' => ['documentation', 'readme']
        ];

        foreach ($groups as $group => $keywords) {
            if ($extension === $group) {
                return $group;
            }
            foreach ($keywords as $keyword) {
                if (stripos($section, $keyword) !== false) {
                    return $group;
                }
            }
        }

        return 'other';
    }

    /**
     * Create summary report
     */
    private function createSummary(
        int $processed,
        int $totalFound,
        int $totalMissing,
        array $errors,
        array $missingFiles,
        string $listFile
    ): string {
        $successRate = $totalFound > 0 ? round(($processed / $totalFound) * 100, 2) : 0;

        $summary = "\n" . str_repeat("=", 70) . "\n";
        $summary .= "📊 FINAL SUMMARY\n";
        $summary .= str_repeat("-", 70) . "\n";
        $summary .= "✅ Files processed: {$processed} of {$totalFound}\n";
        $summary .= "❌ Missing files: {$totalMissing}\n";
        $summary .= "📈 Success rate: {$successRate}%\n";
        $summary .= "⏱️  Completion time: " . now()->format('H:i:s') . "\n";
        $summary .= "📋 List file: " . basename($listFile) . "\n";

        if (!empty($missingFiles)) {
            $summary .= "\n⚠️  MISSING FILES (" . count($missingFiles) . "):\n";
            foreach ($missingFiles as $index => $file) {
                $summary .= "  " . ($index + 1) . ". {$file}\n";
            }
        }

        if (!empty($errors)) {
            $summary .= "\n❌ ERRORS (" . count($errors) . "):\n";
            foreach ($errors as $index => $error) {
                $summary .= "  " . ($index + 1) . ". {$error}\n";
            }
        } else if (empty($missingFiles)) {
            $summary .= "\n🎉 All files were processed successfully!\n";
        } else {
            $summary .= "\n⚠️  Some files were missing but operation completed.\n";
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
            $this->line("📊 File size: " . $this->formatBytes(filesize($outputFile)));
            $this->line("📈 Files processed: " . $result['processed'] . " of " . $result['total_found']);
            $this->line("❌ Missing files: " . $result['total_missing']);

            if ($result['total_missing'] > 0 && $this->option('details')) {
                $this->warn("⚠️  Missing files:");
                foreach ($result['missing_files'] as $file) {
                    $this->line("   • " . $file);
                }
            }

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
    private function getOutputFileName(string $listFile): string
    {
        if ($this->option('output')) {
            return $this->option('output');
        }

        $baseName = pathinfo($listFile, PATHINFO_FILENAME);
        $timestamp = date('Ymd_His');

        return "{$baseName}_merged_{$timestamp}.txt";
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
