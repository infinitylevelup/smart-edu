<?php

namespace App\Http\Controllers\Diagnosis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    protected $root;

    public function __construct()
    {
        $this->root = base_path();
    }

    // =================== Dashboard ===================

public function dashboard()
{
    try {
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $stats = $this->collectStats();

        $environment = app()->environment();
        $debugMode = config('app.debug') ? 'فعال' : 'غیرفعال';
        $timezone = config('app.timezone');
        $locale = config('app.locale');
        $cacheDriver = config('cache.default');
        $sessionDriver = config('session.driver');
        $databaseDriver = config('database.default');

        return view('diagnosis.dashboard', compact(
            'phpVersion',
            'laravelVersion',
            'stats',
            'environment',
            'debugMode',
            'timezone',
            'locale',
            'cacheDriver',
            'sessionDriver',
            'databaseDriver'
        ));
    } catch (\Exception $e) {
        // اگر خطایی رخ داد، بدون متغیر merge نمایش بده
        $phpVersion = PHP_VERSION;
        $laravelVersion = app()->version();
        $stats = ['totalFiles' => 0, 'totalSize' => 0];

        return view('diagnosis.dashboard', compact(
            'phpVersion',
            'laravelVersion',
            'stats'
        ));
    }
}
protected function collectStats(): array
{
    $totalFiles = 0;
    $phpFiles = 0;
    $jsFiles = 0;
    $cssFiles = 0;
    $bladeFiles = 0;
    $totalSize = 0;

    $root = $this->root;

    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            continue;
        }

        $path = $file->getPathname();

        // حذف پوشه‌های غیرضروری
        $excludedDirs = ['vendor', 'node_modules', '.git', 'storage'];
        foreach ($excludedDirs as $dir) {
            if (strpos($path, DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR) !== false) {
                continue 2;
            }
        }

        $totalFiles++;
        $totalSize += $file->getSize();

        // تشخیص نوع فایل
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $filename = basename($path);

        if ($extension === 'php') {
            if (strpos($filename, '.blade.php') !== false) {
                $bladeFiles++;
            } else {
                $phpFiles++;
            }
        } elseif ($extension === 'js') {
            $jsFiles++;
        } elseif ($extension === 'css') {
            $cssFiles++;
        }
    }

    return [
        'totalFiles' => $totalFiles,
        'phpFiles' => $phpFiles,
        'bladeFiles' => $bladeFiles,
        'jsFiles' => $jsFiles,
        'cssFiles' => $cssFiles,
        'totalSize' => $totalSize,
        'formattedSize' => $this->formatBytes($totalSize),
    ];
}

protected function formatBytes($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

    // =================== Project Structure ===================

    public function structure()
    {
        $items = @scandir($this->root) ?: [];
        $dirs  = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $full = $this->root . DIRECTORY_SEPARATOR . $item;

            if ($item === 'vendor') {
                continue;
            }

            if (is_dir($full)) {
                $dirs[] = $item;
            }
        }

        sort($dirs);

        $laravelKnown = [
            'app', 'bootstrap', 'config', 'database',
            'public', 'resources', 'routes', 'storage',
            'tests', 'vendor',
        ];

        // بررسی وجود خطا یا نتایج ادغام از session
        $error = session('error');
        $mergedText = session('mergedText');
        $mergedFiles = session('mergedFiles');
        $rawPaths = session('rawPaths');

        return view('diagnosis.structure.index', compact(
            'dirs',
            'laravelKnown',
            'error',
            'mergedText',
            'mergedFiles',
            'rawPaths'
        ));
    }

    public function appTree()
    {
        $appPath = base_path('app');

        if (!is_dir($appPath)) {
            $tree = [];
        } else {
            $tree = $this->scanDirTreeWithFiles($appPath);
        }

        return view('diagnosis.structure.app-tree', compact('tree'));
    }

    protected function scanDirTreeWithFiles(string $path): array
    {
        $items = @scandir($path) ?: [];
        $nodes = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $full     = $path . DIRECTORY_SEPARATOR . $item;
            $relative = str_replace($this->root . DIRECTORY_SEPARATOR, '', $full);

            if (is_dir($full)) {
                $nodes[] = [
                    'name'     => $item,
                    'type'     => 'dir',
                    'path'     => $relative,
                    'children' => $this->scanDirTreeWithFiles($full),
                ];
            } else {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (!in_array($ext, ['php', 'js', 'css', 'vue', 'ts', 'scss', 'sass'], true)) {
                    continue;
                }

                $nodes[] = [
                    'name'     => $item,
                    'type'     => 'file',
                    'path'     => $relative,
                    'children' => [],
                ];
            }
        }

        usort($nodes, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $nodes;
    }

    // =================== ادغام دستی فایل‌ها ===================

    public function mergeManual(Request $request)
    {
        $pathsText = $request->input('paths', '');

        // رشتهٔ مسیرها را به خطوط جداگانه تبدیل می‌کنیم
        $lines = preg_split("/\r\n|\n|\r/", $pathsText);
        $merged = '';
        $files  = [];

        foreach ($lines as $line) {
            $rel = trim($line);
            if ($rel === '') {
                continue;
            }

            // نرمال‌سازی
            $rel = str_replace('\\', '/', $rel);
            $rel = ltrim($rel, '/');

            // جلوگیری از ../
            if (strpos($rel, '..') !== false) {
                continue;
            }

            $full = $this->root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);

            if (!is_file($full)) {
                continue;
            }

            $content = @file_get_contents($full);
            if ($content === false) {
                continue;
            }

            $files[] = $rel;

            $merged .= "==================== FILE: " . $rel . " ====================\n";
            $merged .= $content . "\n\n";
        }

        if ($merged === '') {
            return redirect()->route('diagnosis.structure')
                ->with('error', 'هیچ فایل معتبری از روی مسیرهای واردشده پیدا نشد.')
                ->with('rawPaths', $pathsText);
        }

        // ذخیره در session برای نمایش
        return redirect()->route('diagnosis.structure')
            ->with('mergedText', $merged)
            ->with('mergedFiles', $files)
            ->with('rawPaths', $pathsText)
            ->with('success', count($files) . ' فایل با موفقیت ادغام شدند.');
    }

    // =================== File Viewer ساده ===================

    public function viewFile(Request $request)
    {
        $relative = $request->query('path');

        if (!$relative) {
            abort(404, 'مسیر فایل مشخص نشده است.');
        }

        $relative = str_replace('\\', '/', $relative);
        $relative = ltrim($relative, '/');

        if (strpos($relative, '..') !== false) {
            abort(403, 'دسترسی غیرمجاز به مسیر.');
        }

        $full = $this->root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

        if (!is_file($full)) {
            abort(404, 'فایل پیدا نشد: ' . $relative);
        }

        $content = @file_get_contents($full);
        if ($content === false) {
            abort(500, 'خطا در خواندن فایل.');
        }

        $extension = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $sizeBytes = @filesize($full) ?: 0;
        $lastModified = @filemtime($full) ?: null;

        // تقسیم به خطوط و escape
        $lines = preg_split("/\r\n|\n|\r/", $content);
        $highlightedLines = [];

        foreach ($lines as $line) {
            $highlightedLines[] = htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        // تعیین type فایل برای هایلایت
        $fileTypes = [
            'php' => 'php',
            'js' => 'javascript',
            'css' => 'css',
            'blade.php' => 'blade',
            'vue' => 'vue',
            'ts' => 'typescript',
            'scss' => 'scss',
            'sass' => 'sass',
        ];

        $fileType = $fileTypes[$extension] ?? 'text';

        return view('diagnosis.file', [
            'relative'         => $relative,
            'extension'        => $extension,
            'fileType'         => $fileType,
            'highlightedLines' => $highlightedLines,
            'sizeBytes'        => $sizeBytes,
            'lastModified'     => $lastModified,
            'totalLines'       => count($lines),
        ]);
    }

    // =================== سایر صفحات ساده ===================

    public function analysis()
    {
        return view('diagnosis.analysis');
    }

    public function security()
    {
        return view('diagnosis.security');
    }

    public function performance()
    {
        return view('diagnosis.performance');
    }

    // =================== متدهای قدیمی (غیرفعال) ===================

    /**
     * @deprecated - استفاده از mergeManual به جای این متد
     */
    public function fileSelect()
    {
        // این متد دیگر استفاده نمی‌شود
        return redirect()->route('diagnosis.structure');
    }

    /**
     * @deprecated - استفاده از mergeManual به جای این متد
     */
    public function mergeFiles(Request $request)
    {
        // این متد دیگر استفاده نمی‌شود
        return redirect()->route('diagnosis.structure');
    }
}
