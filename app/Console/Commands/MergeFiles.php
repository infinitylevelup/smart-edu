<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MergeFiles extends Command
{
    protected $signature = 'merge:files
                            {input : مسیر فایل حاوی لیست مسیر فایل‌ها}
                            {--output=merged.txt : نام فایل خروجی}
                            {--separator= : جداکننده بین فایل‌ها (پیش‌فرض: خط تیره)}';

    protected $description = 'یکپارچه‌سازی فایل‌ها بر اساس لیست مسیرها';

    public function handle()
    {
        $inputFile = $this->argument('input');
        $outputFile = $this->option('output');
        $separator = $this->option('separator') ?: str_repeat('-', 60) . PHP_EOL;

        // بررسی وجود فایل ورودی
        if (!File::exists($inputFile)) {
            $this->error("❌ فایل ورودی '{$inputFile}' یافت نشد!");
            return 1;
        }

        // خواندن لیست فایل‌ها
        $files = File::lines($inputFile)
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => !empty($line) && !str_starts_with($line, '#'))
            ->values()
            ->toArray();

        if (empty($files)) {
            $this->error("❌ هیچ فایلی در لیست ورودی یافت نشد!");
            return 1;
        }

        $this->info("📋 تعداد فایل‌های در لیست: " . count($files));

        $mergedContent = '';
        $foundCount = 0;
        $notFoundFiles = [];

        foreach ($files as $index => $filePath) {
            $fileNumber = $index + 1;
            $this->line("🔍 در حال پردازش فایل {$fileNumber}/" . count($files) . ": {$filePath}");

            if (File::exists($filePath)) {
                $foundCount++;

                // اطلاعات فایل
                $fileSize = File::size($filePath);
                $modifiedTime = File::lastModified($filePath);
                $fileContent = File::get($filePath);

                // اضافه کردن هدر فایل
                $mergedContent .= "📁 فایل: {$filePath}" . PHP_EOL;
                $mergedContent .= "📏 حجم: " . $this->formatBytes($fileSize) . PHP_EOL;
                $mergedContent .= "🕒 آخرین تغییر: " . date('Y-m-d H:i:s', $modifiedTime) . PHP_EOL;
                $mergedContent .= str_repeat("═", 50) . PHP_EOL;

                // اضافه کردن محتوای فایل
                $mergedContent .= $fileContent . PHP_EOL;

                // اضافه کردن جداکننده (به جز برای آخرین فایل)
                if ($index < count($files) - 1) {
                    $mergedContent .= PHP_EOL . $separator . PHP_EOL;
                }

                $this->info("   ✅ افزوده شد (" . $this->formatBytes($fileSize) . ")");
            } else {
                $notFoundFiles[] = $filePath;
                $mergedContent .= "❌ فایل یافت نشد: {$filePath}" . PHP_EOL;
                $mergedContent .= str_repeat("═", 50) . PHP_EOL;
                $mergedContent .= "[FILE NOT FOUND: {$filePath}]" . PHP_EOL;

                if ($index < count($files) - 1) {
                    $mergedContent .= PHP_EOL . $separator . PHP_EOL;
                }

                $this->error("   ❌ یافت نشد");
            }
        }

        // اضافه کردن هدر اصلی
        $header = $this->createHeader($files, $foundCount, count($notFoundFiles));
        $mergedContent = $header . PHP_EOL . $mergedContent;

        // اضافه کردن فوتر
        $footer = $this->createFooter($foundCount, count($files));
        $mergedContent .= PHP_EOL . $footer;

        // ذخیره فایل خروجی
        File::put($outputFile, $mergedContent);

        // نمایش نتیجه
        $this->newLine();
        $this->info("🎉 عملیات یکپارچه‌سازی با موفقیت انجام شد!");
        $this->info("📊 آمار:");
        $this->info("   ✅ فایل‌های یافت شده: {$foundCount}");
        $this->info("   ❌ فایل‌های یافت نشده: " . count($notFoundFiles));
        $this->info("   📁 فایل خروجی: " . realpath($outputFile));
        $this->info("   📏 حجم خروجی: " . $this->formatBytes(File::size($outputFile)));

        // نمایش فایل‌های یافت نشده
        if (!empty($notFoundFiles)) {
            $this->newLine();
            $this->warn("⚠️  فایل‌های یافت نشده:");
            foreach ($notFoundFiles as $file) {
                $this->line("   - {$file}");
            }
        }

        // پیشنهاد مشاهده فایل خروجی
        $this->newLine();
        $this->line("برای مشاهده فایل خروجی:");
        $this->line("  📄 type {$outputFile}");
        $this->line("  📄 notepad {$outputFile}");

        return 0;
    }

    /**
     * ایجاد هدر فایل خروجی
     */
    private function createHeader(array $files, int $found, int $notFound): string
    {
        $header = str_repeat("=", 60) . PHP_EOL;
        $header .= "📦 فایل یکپارچه‌سازی شده" . PHP_EOL;
        $header .= str_repeat("=", 60) . PHP_EOL;
        $header .= "📅 تاریخ ایجاد: " . now()->toJalali()->format('Y/m/d H:i:s') . PHP_EOL;
        $header .= "📋 تعداد فایل‌های درخواستی: " . count($files) . PHP_EOL;
        $header .= "✅ فایل‌های یافت شده: {$found}" . PHP_EOL;
        $header .= "❌ فایل‌های یافت نشده: {$notFound}" . PHP_EOL;
        $header .= str_repeat("=", 60) . PHP_EOL . PHP_EOL;

        $header .= "📁 لیست فایل‌ها:" . PHP_EOL;
        foreach ($files as $index => $file) {
            $status = File::exists($file) ? "✅" : "❌";
            $header .= "  {$status} " . ($index + 1) . ". {$file}" . PHP_EOL;
        }

        $header .= str_repeat("=", 60) . PHP_EOL . PHP_EOL;

        return $header;
    }

    /**
     * ایجاد فوتر فایل خروجی
     */
    private function createFooter(int $found, int $total): string
    {
        $footer = str_repeat("=", 60) . PHP_EOL;
        $footer .= "📊 جمع‌بندی نهایی" . PHP_EOL;
        $footer .= str_repeat("=", 60) . PHP_EOL;
        $footer .= "✅ فایل‌های یافت شده: {$found}" . PHP_EOL;
        $footer .= "📋 فایل‌های کل: {$total}" . PHP_EOL;
        $footer .= "📈 درصد موفقیت: " . round(($found / $total) * 100, 1) . "%" . PHP_EOL;
        $footer .= "🏁 پایان فایل یکپارچه‌سازی" . PHP_EOL;
        $footer .= str_repeat("=", 60);

        return $footer;
    }

    /**
     * فرمت‌بندی سایز فایل
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
