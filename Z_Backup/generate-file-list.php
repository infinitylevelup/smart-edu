<?php
/**
 * File List Generator - ایجاد لیست دسته‌بندی شده فایل‌های پروژه
 * Usage: php generate-file-list.php
 */

// ==================== CLASS DEFINITION ====================
class FileListGenerator
{
    private $categories = [
        'Controllers' => ['app/Http/Controllers', '**/*Controller.php'],
        'Models' => ['app/Models', '*.php'],
        'Views' => ['resources/views', '**/*.blade.php'],
        'Routes' => ['routes', '*.php'],
        'Migrations' => ['database/migrations', '*.php'],
        'Seeders' => ['database/seeders', '*.php'],
        'Requests' => ['app/Http/Requests', '*.php'],
        'Middlewares' => ['app/Http/Middleware', '*.php'],
        'Policies' => ['app/Policies', '*.php'],
        'Providers' => ['app/Providers', '*.php'],
        'JavaScript' => ['public/js', '*.js', 'resources/js', '*.js'],
        'CSS' => ['public/css', '*.css', 'resources/css', '*.css'],
        'Config' => ['config', '*.php'],
        'Tests' => ['tests', '*.php'],
        'Blade Components' => ['resources/views/components', '*.php'],
        'Livewire' => ['app/Http/Livewire', '*.php'],
    ];

    private $ignorePatterns = [
        '/vendor/',
        '/node_modules/',
        '/storage/',
        '/bootstrap/cache/',
        '/.git/',
        '.DS_Store',
        'Thumbs.db',
        '.env',
        'composer.lock',
        'package-lock.json',
    ];

    private $foundFiles = [];
    private $outputFile;

    public function __construct()
    {
        $this->outputFile = 'project_file_list_' . date('Y-m-d_His') . '.txt';
        $this->showHeader();
    }

    private function showHeader()
    {
        echo "📁 FILE LIST GENERATOR\n";
        echo "=====================\n";
        echo "This tool scans your Laravel project and creates a categorized file list.\n";
        echo "Output will be saved as: {$this->outputFile}\n\n";
    }

    public function run()
    {
        $this->scanProject();
        $this->generateOutput();
        $this->showStatistics();
    }

    private function scanProject()
    {
        echo "🔍 SCANNING PROJECT...\n";
        echo str_repeat("-", 50) . "\n";

        foreach ($this->categories as $category => $patterns) {
            echo "Scanning: {$category}\n";
            
            if (is_array($patterns)) {
                for ($i = 0; $i < count($patterns); $i += 2) {
                    if (!isset($patterns[$i]) || !isset($patterns[$i + 1])) {
                        continue;
                    }
                    
                    $directory = $patterns[$i];
                    $pattern = $patterns[$i + 1];
                    
                    if (is_dir($directory)) {
                        $this->scanDirectory($directory, $pattern, $category);
                    } else {
                        echo "  Skipped (directory not found): {$directory}\n";
                    }
                }
            }
        }

        // همچنین فایل‌های مهم ریشه
        echo "\nScanning: Root Files\n";
        $rootFiles = [
            'artisan',
            'composer.json',
            'package.json',
            '.env.example',
            'webpack.mix.js',
            'vite.config.js',
            'phpunit.xml',
            'server.php',
            'README.md',
        ];

        foreach ($rootFiles as $file) {
            if (file_exists($file)) {
                $this->foundFiles['Root Files'][] = $file;
            }
        }
    }

    private function scanDirectory($directory, $pattern, $category)
    {
        $files = $this->globRecursive($directory . '/' . $pattern);
        
        foreach ($files as $file) {
            if ($this->shouldIgnore($file)) {
                continue;
            }

            $this->foundFiles[$category][] = $file;
        }

        if (isset($this->foundFiles[$category]) && count($this->foundFiles[$category]) > 0) {
            echo "  Found: " . count($this->foundFiles[$category]) . " files\n";
        } else {
            echo "  No files found\n";
        }
    }

    private function globRecursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        
        if ($files === false) {
            $files = [];
        }
        
        $dirs = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);
        if ($dirs === false) {
            $dirs = [];
        }
        
        foreach ($dirs as $dir) {
            $files = array_merge($files, $this->globRecursive($dir . '/' . basename($pattern), $flags));
        }
        
        return $files;
    }

    private function shouldIgnore($filepath)
    {
        foreach ($this->ignorePatterns as $pattern) {
            if (strpos($filepath, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }

    private function generateOutput()
    {
        echo "\n📝 GENERATING FILE LIST...\n";

        $output = "# Laravel Project File List\n";
        $output .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "Project: " . basename(getcwd()) . "\n";
        $output .= "Total Categories: " . count($this->foundFiles) . "\n";
        $output .= "=" . str_repeat("=", 70) . "\n\n";

        $totalFiles = 0;

        foreach ($this->foundFiles as $category => $files) {
            if (empty($files)) {
                continue;
            }

            $output .= "\n" . str_repeat("─", 40) . "\n";
            $output .= "📂 " . strtoupper($category) . " (" . count($files) . " files)\n";
            $output .= str_repeat("─", 40) . "\n\n";

            sort($files);
            foreach ($files as $file) {
                $output .= $file . "\n";
                $totalFiles++;
            }
        }

        // نسخه compact (فقط مسیرها بدون دسته‌بندی)
        $compactOutput = "# Compact File List - For AI Report Generator\n";
        $compactOutput .= "# Use this file as input for generate-report-from-file.php\n";
        $compactOutput .= "# Generated: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($this->foundFiles as $category => $files) {
            foreach ($files as $file) {
                $compactOutput .= $file . "\n";
            }
        }

        // ذخیره فایل اصلی
        $mainFile = $this->outputFile;
        file_put_contents($mainFile, $output);

        // ذخیره نسخه compact برای استفاده در گزارش‌ساز
        $compactFile = 'file_list_compact.txt';
        file_put_contents($compactFile, $compactOutput);

        // همچنین یک نسخه markdown
        $markdownFile = str_replace('.txt', '.md', $mainFile);
        file_put_contents($markdownFile, $this->convertToMarkdown($output));

        echo "✅ Files saved:\n";
        echo "  1. {$mainFile}\n";
        echo "  2. {$markdownFile}\n";
        echo "  3. {$compactFile}\n";
    }

    private function convertToMarkdown($content)
    {
        $lines = explode("\n", $content);
        $md = "# Laravel Project File List\n\n";
        
        foreach ($lines as $line) {
            if (strpos($line, '📂') === 0) {
                $line = str_replace('📂 ', '## ', $line);
                $md .= $line . "\n\n";
            } elseif (strpos($line, '=') === 0 || strpos($line, '─') === 0) {
                continue;
            } elseif (trim($line) === '') {
                $md .= "\n";
            } elseif (strpos($line, '#') === 0) {
                $md .= $line . "\n";
            } else {
                $md .= "- `" . $line . "`\n";
            }
        }
        
        return $md;
    }

    private function showStatistics()
    {
        $totalFiles = 0;
        foreach ($this->foundFiles as $files) {
            $totalFiles += count($files);
        }

        echo "\n" . str_repeat("=", 50) . "\n";
        echo "✅ FILE LIST GENERATED SUCCESSFULLY!\n";
        echo str_repeat("=", 50) . "\n\n";

        echo "📊 STATISTICS:\n";
        echo str_repeat("-", 30) . "\n";
        echo "Total categories: " . count($this->foundFiles) . "\n";
        echo "Total files found: {$totalFiles}\n\n";

        echo "📁 FILES BY CATEGORY:\n";
        echo str_repeat("-", 30) . "\n";
        
        foreach ($this->foundFiles as $category => $files) {
            $count = count($files);
            if ($count > 0) {
                echo str_pad($category, 20) . ": {$count} files\n";
            }
        }

        echo "\n💡 HOW TO USE WITH AI REPORT GENERATOR:\n";
        echo str_repeat("-", 40) . "\n";
        echo "Run this command to create AI report:\n";
        echo "> php generate-report-from-file.php file_list_compact.txt\n";
    }
}

// ==================== MAIN EXECUTION ====================
if (php_sapi_name() === 'cli') {
    try {
        $generator = new FileListGenerator();
        $generator->run();
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "<pre>";
    echo "⚠️ This script must be run from command line.\n";
    echo "Usage: php generate-file-list.php\n";
    echo "</pre>";
}