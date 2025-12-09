<?php
/**
 * AI Report Generator - خواندن از فایل متنی
 */

// بررسی آرگومان‌ها
if ($argc < 2) {
    echo "❌ Usage: php generate-ai-report.php <input_file>\n";
    echo "Example: php generate-ai-report.php file_list.txt\n";
    exit(1);
}

$inputFile = $argv[1];
$outputFile = 'AI_REPORT_' . date('Y-m-d_His') . '.txt';

echo "🤖 AI Report Generator\n";
echo "=====================\n";
echo "Input: {$inputFile}\n";
echo "Output: {$outputFile}\n\n";

// بررسی وجود فایل ورودی
if (!file_exists($inputFile)) {
    echo "❌ Error: Input file not found: {$inputFile}\n";
    exit(1);
}

// خواندن فایل ورودی
$lines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    echo "❌ Error: Cannot read input file.\n";
    exit(1);
}

echo "📖 Reading " . count($lines) . " lines from input file...\n";

// فیلتر کردن خطوط
$filesToProcess = [];
foreach ($lines as $line) {
    $line = trim($line);
    
    // نادیده گرفتن کامنت‌ها و خطوط خالی
    if (empty($line) || $line[0] === '#') {
        continue;
    }
    
    // حذف markdown formatting
    $line = str_replace(['- `', '`'], '', $line);
    $line = str_replace(['* ', '- '], '', $line);
    
    $filesToProcess[] = $line;
}

echo "📋 Found " . count($filesToProcess) . " files to process.\n\n";

// شروع ایجاد گزارش
$report = "=" . str_repeat("=", 70) . "\n";
$report .= "🤖 AI ASSISTANT REPORT\n";
$report .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$report .= "Input file: " . basename($inputFile) . "\n";
$report .= "Project: " . basename(getcwd()) . "\n";
$report .= "Total Files: " . count($filesToProcess) . "\n";
$report .= "=" . str_repeat("=", 70) . "\n\n";

$processedCount = 0;
$notFoundCount = 0;

foreach ($filesToProcess as $index => $filepath) {
    $fileNumber = $index + 1;
    
    echo "[" . str_pad($fileNumber, 3) . "/" . count($filesToProcess) . "] ";
    
    if (file_exists($filepath)) {
        echo "✓ " . basename($filepath) . "\n";
        $processedCount++;
        
        // افزودن فایل به گزارش
        $report .= addFileToReport($filepath, $fileNumber);
    } else {
        echo "✗ " . $filepath . " (NOT FOUND)\n";
        $notFoundCount++;
        
        $report .= "\n" . str_repeat("-", 70) . "\n";
        $report .= "❌ FILE NOT FOUND: " . $filepath . "\n";
        $report .= str_repeat("-", 70) . "\n\n";
    }
}

// پایان گزارش
$report .= "\n" . str_repeat("=", 70) . "\n";
$report .= "📊 REPORT SUMMARY\n";
$report .= str_repeat("-", 30) . "\n";
$report .= "Total files in list: " . count($filesToProcess) . "\n";
$report .= "Successfully processed: {$processedCount}\n";
$report .= "Not found: {$notFoundCount}\n";
$report .= "Generated on: " . date('Y-m-d H:i:s') . "\n";
$report .= str_repeat("=", 70) . "\n";

// ذخیره گزارش
file_put_contents($outputFile, $report);

// همچنین یک نسخه مارک‌داون
$markdownFile = str_replace('.txt', '.md', $outputFile);
file_put_contents($markdownFile, convertToMarkdown($report));

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ REPORT GENERATED SUCCESSFULLY!\n";
echo str_repeat("=", 50) . "\n\n";

echo "📊 STATISTICS:\n";
echo "Files processed: {$processedCount}\n";
echo "Files not found: {$notFoundCount}\n\n";

echo "📁 OUTPUT FILES:\n";
echo "1. {$outputFile} (Full report)\n";
echo "2. {$markdownFile} (Markdown version)\n\n";

echo "💡 Share '{$outputFile}' with AI assistant.\n";

// اگر ویندوز هست، به کلیپ‌بورد کپی کن
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $compactSummary = "Report generated: {$outputFile}\n";
    $compactSummary .= "Files: {$processedCount}/" . count($filesToProcess) . "\n";
    exec('echo ' . escapeshellarg($compactSummary) . ' | clip');
    echo "📋 Summary copied to clipboard!\n";
}

// ========== توابع کمکی ==========

function addFileToReport($filepath, $fileNumber) {
    $content = "\n" . str_repeat("-", 70) . "\n";
    $content .= "📄 FILE {$fileNumber}: " . basename($filepath) . "\n";
    $content .= "📍 PATH: " . $filepath . "\n";
    
    if (file_exists($filepath)) {
        $size = filesize($filepath);
        $content .= "📏 SIZE: " . number_format($size) . " bytes\n";
        $content .= "🕒 MODIFIED: " . date('Y-m-d H:i:s', filemtime($filepath)) . "\n";
        $content .= str_repeat("-", 70) . "\n\n";
        
        $fileContent = file_get_contents($filepath);
        
        // محدود کردن فایل‌های بزرگ
        if ($size > 50000) { // 50KB
            $content .= "⚠️  LARGE FILE - SHOWING FIRST 2000 CHARACTERS\n";
            $content .= str_repeat(".", 50) . "\n\n";
            $fileContent = substr($fileContent, 0, 2000) . "\n\n... [CONTENT TRUNCATED] ...\n";
        }
        
        $content .= $fileContent;
    }
    
    $content .= "\n" . str_repeat(".", 70) . "\n";
    
    return $content;
}

function convertToMarkdown($content) {
    $lines = explode("\n", $content);
    $md = "# AI Assistant Report\n\n";
    
    $inCodeBlock = false;
    
    foreach ($lines as $line) {
        // جداکننده‌ها
        if (strpos($line, '═') !== false || (strlen($line) > 10 && $line[0] === '=')) {
            $md .= "---\n";
        }
        // هدر فایل
        elseif (preg_match('/^📄 FILE (\d+): (.+)$/', $line, $matches)) {
            $md .= "\n## 📄 {$matches[1]}. {$matches[2]}\n\n";
        }
        // مسیر فایل
        elseif (preg_match('/^📍 PATH: (.+)$/', $line, $matches)) {
            $md .= "**Path:** `{$matches[1]}`  \n";
        }
        // سایز فایل
        elseif (preg_match('/^📏 SIZE: (.+)$/', $line, $matches)) {
            $md .= "**Size:** {$matches[1]}  \n";
        }
        // تاریخ تغییر
        elseif (preg_match('/^🕒 MODIFIED: (.+)$/', $line, $matches)) {
            $md .= "**Modified:** {$matches[1]}\n\n";
        }
        // هشدار فایل بزرگ
        elseif (strpos($line, '⚠️  LARGE FILE') !== false) {
            $md .= "> **Note:** Large file - content truncated\n\n";
        }
        // خطوط عادی
        else {
            $md .= $line . "\n";
        }
    }
    
    return $md;
}