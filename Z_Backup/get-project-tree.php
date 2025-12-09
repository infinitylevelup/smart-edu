<?php
// get-project-tree.php
echo "🚀 Starting project tree generator...\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . getcwd() . "\n\n";

// فایل خروجی
$outputFile = 'PROJECT_TREE_' . date('Y-m-d_His') . '.txt';

// پوشه‌های نادیده گرفته شود
$ignoreDirs = ['vendor', 'node_modules', '.git', 'storage/framework', 'storage/logs', 'bootstrap/cache'];
$ignoreFiles = ['.env', 'composer.lock', 'package-lock.json'];

// تابع برای بررسی ignore
function shouldIgnore($path, $ignoreList) {
    foreach ($ignoreList as $ignore) {
        if (strpos($path, $ignore) !== false) {
            return true;
        }
    }
    return false;
}

// تابع اسکن دایرکتوری
function scanDirectory($dir, $prefix = '', &$output, $ignoreDirs, $ignoreFiles, $level = 0) {
    if ($level > 5) return; // عمق محدود
    
    $items = @scandir($dir);
    if (!$items) {
        $output[] = $prefix . "❌ Cannot read: " . basename($dir);
        return;
    }
    
    // مرتب‌سازی
    sort($items);
    
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        $relativePath = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $path);
        
        // چک ignore
        if (shouldIgnore($relativePath, $ignoreDirs) || shouldIgnore($relativePath, $ignoreFiles)) {
            continue;
        }
        
        $indent = str_repeat('│   ', $level);
        
        if (is_dir($path)) {
            $output[] = $prefix . $indent . "📁 " . $item . "/";
            
            // فقط پوشه‌های مهم لاراول رو کامل اسکن کن
            $importantDirs = ['app', 'config', 'database', 'resources', 'routes', 'public', 'tests'];
            if (in_array($item, $importantDirs) || $level < 2) {
                scanDirectory($path, $prefix, $output, $ignoreDirs, $ignoreFiles, $level + 1);
            }
        } else {
            $icon = getFileIcon($item);
            $output[] = $prefix . $indent . $icon . " " . $item;
            
            // محتوای فایل‌های مهم رو اضافه کن
            if (isImportantFile($item)) {
                addFilePreview($path, $output, $level + 1);
            }
        }
    }
}

// آیکون فایل
function getFileIcon($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $icons = [
        'php' => '🐘',
        'js' => '📜',
        'vue' => '⚡',
        'css' => '🎨',
        'blade.php' => '🔸',
        'json' => '📋',
        'md' => '📝',
        'sql' => '🗃️',
    ];
    return $icons[$ext] ?? '📄';
}

// فایل‌های مهم
function isImportantFile($filename) {
    $important = [
        'composer.json',
        'package.json',
        '.env.example',
        'web.php',
        'api.php',
        'kernel.php',
        'app.php',
        'database.php',
        'User.php',
        'HomeController.php',
    ];
    
    foreach ($important as $imp) {
        if (strpos($filename, $imp) !== false) {
            return true;
        }
    }
    return false;
}

// پیش‌نمایش فایل
function addFilePreview($filepath, &$output, $level) {
    if (!file_exists($filepath) || filesize($filepath) > 10000) {
        return;
    }
    
    $content = file_get_contents($filepath);
    $lines = explode("\n", $content);
    $lines = array_slice($lines, 0, 15); // 15 خط اول
    
    $indent = str_repeat('│   ', $level);
    $output[] = $indent . "┌───── FILE CONTENT ─────";
    
    $lineNum = 1;
    foreach ($lines as $line) {
        if (trim($line) !== '') {
            $output[] = $indent . "│ " . str_pad($lineNum, 3, ' ', STR_PAD_LEFT) . " │ " . substr($line, 0, 100);
            $lineNum++;
        }
        if ($lineNum > 10) break; // حداکثر 10 خط
    }
    
    $output[] = $indent . "└────────────────────────";
}

// شروع اسکن
$output = [];
$output[] = "=" . str_repeat("=", 70);
$output[] = "LARAVEL PROJECT TREE REPORT";
$output[] = "Generated: " . date('Y-m-d H:i:s');
$output[] = "Project: " . basename(getcwd());
$output[] = "=" . str_repeat("=", 70);
$output[] = "";

// اطلاعات اولیه
if (file_exists('artisan')) {
    $output[] = "✅ Laravel Project Detected";
    $output[] = "";
    
    // خواندن composer.json
    if (file_exists('composer.json')) {
        $composer = json_decode(file_get_contents('composer.json'), true);
        if ($composer) {
            $output[] = "📦 Composer Info:";
            $output[] = "  Name: " . ($composer['name'] ?? 'N/A');
            $output[] = "  Laravel: " . ($composer['require']['laravel/framework'] ?? 'N/A');
            $output[] = "  PHP: " . ($composer['require']['php'] ?? 'N/A');
            $output[] = "";
        }
    }
}

$output[] = "📁 PROJECT STRUCTURE:";
$output[] = "";

// اسکن دایرکتوری اصلی
scanDirectory(getcwd(), '', $output, $ignoreDirs, $ignoreFiles, 0);

// آمار
$output[] = "";
$output[] = "📊 STATISTICS:";
$output[] = "Generated files: " . count(glob('*.txt'));
$output[] = "Date: " . date('Y-m-d H:i:s');
$output[] = "";
$output[] = "💡 TIP: Share this file with AI assistant for code analysis";

// ذخیره در فایل
file_put_contents($outputFile, implode(PHP_EOL, $output));

// نمایش در کنسول
echo implode(PHP_EOL, $output);
echo "\n\n✅ SUCCESS: Report saved to: " . $outputFile . "\n";

// کپی به کلیپ‌بورد در ویندوز
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $clipCommand = 'echo ' . escapeshellarg(implode(PHP_EOL, $output)) . ' | clip';
    shell_exec($clipCommand);
    echo "📋 Also copied to clipboard!\n";
}