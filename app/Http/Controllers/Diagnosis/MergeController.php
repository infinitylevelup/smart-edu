<?php

namespace App\Http\Controllers\Diagnosis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MergeController extends Controller
{
    protected $root;

    public function __construct()
    {
        $this->root = base_path();
    }

    public function index()
    {
        // فایل‌های اخیراً ادغام شده
        $recentFiles = Session::get('recent_merge_files', []);

        // پریست‌های موجود
        $presets = [
            'controller_views' => [
                'name' => 'کنترلر + ویوها',
                'description' => 'همه کنترلرها و ویوهای مرتبط',
                'icon' => '🔄'
            ],
            'full_mvc' => [
                'name' => 'MVC کامل',
                'description' => 'Model + View + Controller',
                'icon' => '🎭'
            ],
            'api_files' => [
                'name' => 'فایل‌های API',
                'description' => 'routes/api.php + کنترلرهای API',
                'icon' => '🌐'
            ],
            'config_files' => [
                'name' => 'تنظیمات پروژه',
                'description' => 'همه فایل‌های config',
                'icon' => '⚙️'
            ],
            'error_files' => [
                'name' => 'فایل‌های خطا',
                'description' => 'لاگ‌ها و صفحه‌های خطا',
                'icon' => '🐛'
            ]
        ];

        return view('diagnosis.merge', compact('recentFiles', 'presets'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // داده‌های نمونه برای تست
        $sampleFiles = [
            ['path' => 'app/Http/Controllers/Controller.php', 'name' => 'Controller.php', 'icon' => '🐘'],
            ['path' => 'routes/web.php', 'name' => 'web.php', 'icon' => '📄'],
            ['path' => 'resources/views/welcome.blade.php', 'name' => 'welcome.blade.php', 'icon' => '🔪'],
            ['path' => 'config/app.php', 'name' => 'app.php', 'icon' => '⚙️'],
            ['path' => 'app/Models/User.php', 'name' => 'User.php', 'icon' => '👤'],
        ];

        $filtered = array_filter($sampleFiles, function($file) use ($query) {
            return stripos($file['name'], $query) !== false ||
                   stripos($file['path'], $query) !== false;
        });

        return response()->json(array_values($filtered));
    }

    public function preview(Request $request)
    {
        $selectedFiles = $request->input('files', []);
        $merged = '';
        $totalSize = 0;

        foreach ($selectedFiles as $filePath) {
            $fullPath = $this->root . '/' . $filePath;

            if (!file_exists($fullPath)) {
                // اگر فایل وجود ندارد، محتوای نمونه نشان بده
                $content = "// محتوای فایل: {$filePath}\n// این یک محتوای نمونه است\n\n";
                $totalSize += strlen($content);

                $merged .= "==================== FILE: {$filePath} ====================\n";
                $merged .= $content . "\n";
            } else {
                $content = file_get_contents($fullPath);
                $totalSize += strlen($content);

                $merged .= "==================== FILE: {$filePath} ====================\n";
                $merged .= $content . "\n\n";
            }
        }

        // ذخیره فایل‌های اخیر
        $recent = Session::get('recent_merge_files', []);
        foreach ($selectedFiles as $file) {
            if (!in_array($file, $recent)) {
                array_unshift($recent, $file);
            }
        }
        $recent = array_slice($recent, 0, 10);
        Session::put('recent_merge_files', $recent);

        // فرمت کردن حجم
        $formattedSize = $this->formatSize($totalSize);

        return response()->json([
            'content' => $merged,
            'totalSize' => $formattedSize,
            'fileCount' => count($selectedFiles)
        ]);
    }

    public function download(Request $request)
    {
        $selectedFiles = $request->input('files', []);
        $merged = '';

        foreach ($selectedFiles as $filePath) {
            $fullPath = $this->root . '/' . $filePath;

            if (!file_exists($fullPath)) {
                $content = "// محتوای فایل: {$filePath}\n// این یک محتوای نمونه است\n\n";
            } else {
                $content = file_get_contents($fullPath);
            }

            $merged .= "==================== FILE: {$filePath} ====================\n";
            $merged .= $content . "\n\n";
        }

        $filename = 'merged-' . date('Y-m-d-His') . '.txt';

        return response($merged)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function preset($preset)
    {
        $presetFiles = [];

        switch ($preset) {
            case 'controller_views':
                $presetFiles = ['app/Http/Controllers/Controller.php', 'resources/views/welcome.blade.php'];
                break;
            case 'full_mvc':
                $presetFiles = ['app/Models/User.php', 'app/Http/Controllers/Controller.php', 'resources/views/welcome.blade.php'];
                break;
            case 'api_files':
                $presetFiles = ['routes/api.php'];
                break;
            case 'config_files':
                $presetFiles = ['config/app.php', 'config/database.php'];
                break;
            case 'error_files':
                $presetFiles = ['storage/logs/laravel.log'];
                break;
            default:
                $presetFiles = ['app/Http/Controllers/Controller.php'];
        }

        // فقط فایل‌هایی که واقعاً وجود دارند برگردان
        $existingFiles = array_filter($presetFiles, function($file) {
            return file_exists($this->root . '/' . $file);
        });

        return response()->json($existingFiles);
    }

    public function clearRecent()
    {
        Session::forget('recent_merge_files');
        return response()->json(['success' => true]);
    }

    private function formatSize($bytes)
    {
        if ($bytes == 0) return "0 B";

        $k = 1024;
        $sizes = ["B", "KB", "MB", "GB"];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . " " . $sizes[$i];
    }
}
