<?php
/**
 * Smart AI Report Generator
 * فایل‌های پیدا نشده: اول اسم و مسیر، بعد خطای Not Found
 */

// ==================== CONFIGURATION ====================
define('SHOW_FILE_CONTENT', true);    // آیا محتوای فایل رو نشون بدیم؟
define('MAX_FILE_SIZE', 50000);       // حداکثر سایز فایل برای نمایش کامل (بایت)
define('TRUNCATE_LENGTH', 3000);      // اگر فایل بزرگه، چند کاراکتر نشون بدیم

// ==================== HELPER FUNCTIONS ====================
function findAlternativePaths($originalPath) {
    $attempts = [];

    // ۱. مسیر نسبی از دایرکتوری جاری
    $attempts[] = './' . $originalPath;

    // ۲. مسیر کامل از root پروژه
    $attempts[] = getcwd() . '/' . $originalPath;

    // ۳. حذف public/ اگر وجود داشت
    if (strpos($originalPath, 'public/') === 0) {
        $attempts[] = substr($originalPath, 7);
    }

    // ۴. اضافه کردن public/ اگر وجود نداشت
    elseif (strpos($originalPath, 'public/') === false) {
        $attempts[] = 'public/' . $originalPath;
    }

    // ۵. حذف resources/ اگر وجود داشت
    if (strpos($originalPath, 'resources/') === 0) {
        $attempts[] = substr($originalPath, 10);
    }

    // فقط مسیرهایی که واقعاً وجود دارند برگردان
    $validAttempts = [];
    foreach ($attempts as $attempt) {
        if (file_exists($attempt) && !in_array($attempt, $validAttempts)) {
            $validAttempts[] = $attempt;
        }
    }

    return $validAttempts;
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * تشخیص اینکه یک خط واقعاً مسیر فایل هست یا نه.
 * جلوی این رو می‌گیره که تیترهایی مثل "Generated: ..." وارد چک فایل بشن.
 */
function isLikelyPath(string $s): bool
{
    // باید حداقل یکی از نشانه‌های path را داشته باشد
    if (!preg_match('/[\/\\\\]/', $s)) return false;

    // اگر خط شبیه "Key: Value" بود ولی پسوند فایل نداشت → متادیتاست
    if (strpos($s, ':') !== false && !preg_match('/\.[a-z0-9]{1,6}$/i', $s)) {
        return false;
    }

    return true;
}

// ==================== MAIN CODE ====================
echo "🤖 SMART AI REPORT GENERATOR\n";
echo str_repeat("=", 50) . "\n";

// بررسی آرگومان
if ($argc < 2) {
    echo "❌ Usage: php smart-ai-report.php <input_file>\n";
    echo "Example: php smart-ai-report.php file_list.txt\n";
    exit(1);
}

$inputFile = $argv[1];
$outputFile = 'AI_REPORT_' . date('Y-m-d_His') . '.txt';

echo "📥 Input: " . basename($inputFile) . "\n";
echo "📤 Output: $outputFile\n\n";

// بررسی وجود فایل ورودی
if (!file_exists($inputFile)) {
    echo "❌ ERROR: Input file not found: $inputFile\n";
    echo "💡 Create a text file with one file path per line.\n";
    exit(1);
}

// خواندن فایل ورودی
$lines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    echo "❌ ERROR: Cannot read input file.\n";
    exit(1);
}

echo "📊 Found " . count($lines) . " lines in input file.\n";

// پردازش خطوط
$validFiles = [];
$invalidFiles = [];
$commentLines = 0;

foreach ($lines as $lineNumber => $line) {
    $raw = trim($line);

    // خطوط خالی
    if ($raw === '') {
        continue;
    }

    // خطوط کامنت
    if ($raw[0] === '#' || substr($raw, 0, 2) === '//') {
        $commentLines++;
        continue;
    }

    // حذف bullet های markdown (-,*,+) و backtick و کوتیشن
    $cleanPath = preg_replace('/^\s*[-*+]\s*/', '', $raw);
    $cleanPath = trim($cleanPath, " \t\n\r\0\x0B`\"'");
    $cleanPath = preg_replace('/\s+/', ' ', $cleanPath);

    // اگر شبیه مسیر نبود (مثلاً Generated: ...)، ردش کن
    if (!isLikelyPath($cleanPath)) {
        continue;
    }

    // چک کردن وجود فایل
    if (file_exists($cleanPath)) {
        $validFiles[] = [
            'path' => $cleanPath,
            'original_line' => $line,
            'line_number' => $lineNumber + 1
        ];
    } else {
        $invalidFiles[] = [
            'path' => $cleanPath,
            'original_line' => $line,
            'line_number' => $lineNumber + 1,
            'attempted_paths' => findAlternativePaths($cleanPath)
        ];
    }
}

echo "✅ Valid files: " . count($validFiles) . "\n";
echo "❌ Not found: " . count($invalidFiles) . "\n";
echo "💬 Comments: $commentLines\n\n";

// ==================== GENERATE REPORT ====================
$report = "";

// هدر گزارش
$report .= str_repeat("═", 80) . "\n";
$report .= "🤖 AI ASSISTANT REPORT - SMART EDU SYSTEM\n";
$report .= str_repeat("═", 80) . "\n";
$report .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$report .= "Project: " . basename(getcwd()) . "\n";
$report .= "Input file: " . basename($inputFile) . "\n";
$report .= "Total entries: " . count($lines) . "\n";
$report .= "Valid files: " . count($validFiles) . "\n";
$report .= "Not found: " . count($invalidFiles) . "\n";
$report .= str_repeat("═", 80) . "\n\n";

// بخش ۱: فایل‌های پیدا نشده
if (!empty($invalidFiles)) {
    $report .= "❌❌❌ FILES NOT FOUND ❌❌❌\n";
    $report .= str_repeat("▼", 40) . "\n\n";

    foreach ($invalidFiles as $file) {
        $report .= "📛 FILE: " . basename($file['path']) . "\n";
        $report .= "📍 PATH: " . $file['path'] . "\n";
        $report .= "📝 Original line #{$file['line_number']}: {$file['original_line']}\n";

        if (!empty($file['attempted_paths'])) {
            $report .= "🔍 Attempted alternative paths:\n";
            foreach ($file['attempted_paths'] as $attempt) {
                $report .= "   - $attempt\n";
            }
        }

        $report .= "🚫 STATUS: FILE NOT FOUND\n";
        $report .= str_repeat("─", 60) . "\n\n";
    }

    $report .= str_repeat("▲", 40) . "\n\n";
}

// بخش ۲: فایل‌های معتبر
if (!empty($validFiles)) {
    $report .= "✅✅✅ VALID FILES ✅✅✅\n";
    $report .= str_repeat("▼", 40) . "\n\n";

    foreach ($validFiles as $index => $file) {
        $fileNumber = $index + 1;
        $filePath = $file['path'];

        $report .= str_repeat("─", 70) . "\n";
        $report .= "📄 FILE {$fileNumber}: " . basename($filePath) . "\n";
        $report .= "📍 PATH: $filePath\n";
        $report .= "📝 Original line #{$file['line_number']}: {$file['original_line']}\n";

        $fileSize = filesize($filePath);
        $fileModTime = date('Y-m-d H:i:s', filemtime($filePath));

        $report .= "📏 SIZE: " . formatBytes($fileSize) . "\n";
        $report .= "🕒 MODIFIED: $fileModTime\n";
        $report .= str_repeat("─", 70) . "\n\n";

        if (SHOW_FILE_CONTENT) {
            $content = file_get_contents($filePath);

            if ($fileSize > MAX_FILE_SIZE) {
                $report .= "⚠️  LARGE FILE (" . formatBytes($fileSize) . ") - SHOWING FIRST " . number_format(TRUNCATE_LENGTH) . " CHARACTERS\n";
                $report .= str_repeat(".", 50) . "\n\n";
                $content = substr($content, 0, TRUNCATE_LENGTH) . "\n\n... [CONTENT TRUNCATED - " . formatBytes($fileSize) . " TOTAL] ...\n";
            }

            $report .= $content . "\n";
        } else {
            $report .= "[CONTENT HIDDEN - FILE SIZE: " . formatBytes($fileSize) . "]\n";
        }

        $report .= "\n" . str_repeat(".", 70) . "\n\n";
    }

    $report .= str_repeat("▲", 40) . "\n\n";
}

// بخش ۳: خلاصه
$report .= str_repeat("═", 80) . "\n";
$report .= "📊 FINAL SUMMARY\n";
$report .= str_repeat("─", 30) . "\n";
$report .= "Report generated: " . date('Y-m-d H:i:s') . "\n";
$report .= "Input file: " . basename($inputFile) . "\n";
$report .= "Total lines processed: " . count($lines) . "\n";
$report .= "✅ Successfully processed: " . count($validFiles) . " files\n";
$report .= "❌ Not found: " . count($invalidFiles) . " files\n";
$report .= "📁 Report saved to: $outputFile\n";

if (!empty($invalidFiles)) {
    $report .= "\n⚠️  ATTENTION: Some files were not found!\n";
    $report .= "   Check the paths above and update your input file.\n";
}

$report .= str_repeat("═", 80) . "\n";

// ==================== SAVE REPORT ====================
file_put_contents($outputFile, $report);

// نسخه compact برای فایل‌های پیدا نشده
$notFoundFile = 'NOT_FOUND_FILES_' . date('Y-m-d_His') . '.txt';
$notFoundContent = "# Files Not Found Report\n";
$notFoundContent .= "Generated: " . date('Y-m-d H:i:s') . "\n";
$notFoundContent .= "Total not found: " . count($invalidFiles) . "\n\n";

foreach ($invalidFiles as $file) {
    $notFoundContent .= "File: " . basename($file['path']) . "\n";
    $notFoundContent .= "Path: " . $file['path'] . "\n";
    $notFoundContent .= "Line #{$file['line_number']}: {$file['original_line']}\n";
    $notFoundContent .= str_repeat("-", 50) . "\n";
}

file_put_contents($notFoundFile, $notFoundContent);

// ==================== DISPLAY RESULTS ====================
echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ REPORT GENERATED SUCCESSFULLY!\n";
echo str_repeat("=", 50) . "\n\n";

echo "📁 OUTPUT FILES:\n";
echo "1. $outputFile (Full AI report)\n";
echo "2. $notFoundFile (Not found files list)\n\n";

echo "📊 STATISTICS:\n";
echo "- Total input lines: " . count($lines) . "\n";
echo "- Valid files: " . count($validFiles) . "\n";
echo "- Not found: " . count($invalidFiles) . "\n\n";

if (!empty($invalidFiles)) {
    echo "❌ FILES NOT FOUND:\n";
    foreach ($invalidFiles as $i => $file) {
        echo "  " . ($i + 1) . ". " . basename($file['path']) . "\n";
        echo "     Path: " . $file['path'] . "\n";
    }
    echo "\n💡 Check $notFoundFile for details.\n";
}

echo "\n💡 Share '$outputFile' with AI assistant for analysis.\n";
echo "🤖 Thank you for using Smart AI Report Generator!\n";