<?php
// api.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Function to get all drives in Windows
function getSystemDrives() {
    $drives = [];

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Method 1: Use wmic command
        exec('wmic logicaldisk get caption,drivetype', $output);

        foreach ($output as $line) {
            if (preg_match('/^([A-Z]):\s+([0-9])/', $line, $matches)) {
                $drive = $matches[1] . ':';
                $type = (int)$matches[2];

                // Drive types: 2=Removable, 3=Fixed, 4=Network, 5=CD-ROM
                $typeNames = [
                    2 => 'Removable',
                    3 => 'Local Disk',
                    4 => 'Network Drive',
                    5 => 'CD/DVD'
                ];

                $typeName = $typeNames[$type] ?? 'Unknown';

                try {
                    $free = @disk_free_space($drive);
                    $total = @disk_total_space($drive);
                    $used = $total - $free;

                    $drives[] = [
                        'name' => $drive . ' (' . $typeName . ')',
                        'type' => 'drive',
                        'path' => $drive . '\\',
                        'children' => [],
                        'expanded' => false,
                        'size' => $total,
                        'free' => $free,
                        'used' => $used,
                        'icon' => 'hdd',
                        'accessible' => $free !== false,
                        'drive_type' => $typeName
                    ];
                } catch (Exception $e) {
                    // Drive not accessible
                }
            }
        }

        // Method 2: Fallback to range A-Z
        if (empty($drives)) {
            foreach (range('A', 'Z') as $drive) {
                $drivePath = $drive . ':\\';
                if (is_dir($drivePath)) {
                    try {
                        $free = @disk_free_space($drivePath);
                        $total = @disk_total_space($drivePath);

                        $drives[] = [
                            'name' => $drivePath,
                            'type' => 'drive',
                            'path' => $drivePath,
                            'children' => [],
                            'expanded' => false,
                            'size' => $total,
                            'free' => $free,
                            'icon' => 'hdd',
                            'accessible' => $free !== false
                        ];
                    } catch (Exception $e) {
                        // Drive not accessible
                    }
                }
            }
        }
    } else {
        // Linux/Mac
        $drives[] = [
            'name' => '/ (Root Directory)',
            'type' => 'drive',
            'path' => '/',
            'children' => [],
            'expanded' => false,
            'size' => @disk_total_space('/') ?: 0,
            'free' => @disk_free_space('/') ?: 0,
            'icon' => 'hdd',
            'accessible' => true
        ];

        // Add home directory
        $home = getenv('HOME');
        if ($home && is_dir($home)) {
            $drives[] = [
                'name' => '~ (Home Directory)',
                'type' => 'special',
                'path' => $home,
                'children' => [],
                'expanded' => false,
                'size' => 0,
                'icon' => 'home',
                'accessible' => true
            ];
        }
    }

    return $drives;
}

// Function to get special folders (Windows)
function getSpecialFolders() {
    $folders = [];

    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $userProfile = getenv('USERPROFILE');

        $specialPaths = [
            'Desktop' => $userProfile . '\\Desktop',
            'Documents' => $userProfile . '\\Documents',
            'Downloads' => $userProfile . '\\Downloads',
            'Pictures' => $userProfile . '\\Pictures',
            'Music' => $userProfile . '\\Music',
            'Videos' => $userProfile . '\\Videos'
        ];

        foreach ($specialPaths as $name => $path) {
            if (is_dir($path)) {
                $folders[] = [
                    'name' => $name,
                    'type' => 'special',
                    'path' => $path,
                    'children' => [],
                    'expanded' => false,
                    'size' => 0,
                    'icon' => strtolower($name),
                    'accessible' => true
                ];
            }
        }
    }

    return $folders;
}

// Function to get full tree structure
function getFullTree() {
    $tree = [];

    // Add "This PC" on Windows
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $tree[] = [
            'name' => 'This PC',
            'type' => 'system',
            'path' => 'ThisPC',
            'children' => array_merge(getSystemDrives(), getSpecialFolders()),
            'expanded' => true,
            'size' => 0,
            'icon' => 'pc',
            'accessible' => true
        ];
    } else {
        // Linux/Mac - just show drives
        $tree = array_merge(getSystemDrives(), [
            [
                'name' => 'Current Directory',
                'type' => 'special',
                'path' => '.',
                'children' => [],
                'expanded' => false,
                'size' => 0,
                'icon' => 'folder',
                'accessible' => true
            ]
        ]);
    }

    return $tree;
}

// Function to get directory contents
function getDirectoryContents($path) {
    $result = [];

    // Handle special paths
    if ($path === 'ThisPC') {
        return array_merge(getSystemDrives(), getSpecialFolders());
    }

    // Check if path exists and is accessible
    if (!file_exists($path) || !is_dir($path)) {
        return ['error' => 'مسیر یافت نشد یا قابل دسترسی نیست: ' . $path];
    }

    // Check if we have permission to read the directory
    if (!is_readable($path)) {
        return ['error' => 'دسترسی خواندن به مسیر وجود ندارد: ' . $path];
    }

    $excluded = ['.', '..', '$RECYCLE.BIN', 'System Volume Information', 'pagefile.sys', 'hiberfil.sys'];

    try {
        $items = scandir($path);
        if ($items === false) {
            return ['error' => 'خطا در خواندن محتوای پوشه'];
        }

        foreach ($items as $item) {
            if (in_array(strtolower($item), array_map('strtolower', $excluded))) {
                continue;
            }

            $fullPath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $item;

            // Skip if path is too long (Windows limitation)
            if (strlen($fullPath) > 260) {
                continue;
            }

            try {
                if (is_dir($fullPath)) {
                    // Check if we can access this directory
                    $test = @scandir($fullPath);
                    $isAccessible = $test !== false;
                    $hasChildren = $isAccessible && count($test) > 2;

                    $result[] = [
                        'name' => $item,
                        'type' => 'folder',
                        'path' => $fullPath,
                        'children' => $hasChildren ? [] : null,
                        'expanded' => false,
                        'size' => 0,
                        'icon' => $isAccessible ? 'folder' : 'folder-lock',
                        'accessible' => $isAccessible
                    ];
                } else {
                    // It's a file
                    $size = @filesize($fullPath) ?: 0;
                    $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));

                    // Only show files under 50MB for performance
                    if ($size < 50 * 1024 * 1024) {
                        $result[] = [
                            'name' => $item,
                            'type' => 'file',
                            'path' => $fullPath,
                            'size' => $size,
                            'extension' => $ext,
                            'icon' => 'file',
                            'accessible' => true
                        ];
                    }
                }
            } catch (Exception $e) {
                // Skip this item if there's an error
                continue;
            }
        }

        // Sort: folders first, then files
        usort($result, function($a, $b) {
            if ($a['type'] === $b['type']) {
                return strnatcasecmp($a['name'], $b['name']);
            }
            return $a['type'] === 'folder' ? -1 : 1;
        });

    } catch (Exception $e) {
        return ['error' => 'خطای سیستمی: ' . $e->getMessage()];
    }

    return $result;
}

// Function to get all accessible files (for select all)
function getAllAccessibleFiles($path = '', $depth = 0, $maxDepth = 3) {
    $files = [];

    if ($depth > $maxDepth) {
        return $files;
    }

    try {
        if (empty($path)) {
            // Start from drives
            $drives = getSystemDrives();
            foreach ($drives as $drive) {
                if ($drive['accessible']) {
                    $files = array_merge($files, getAllAccessibleFiles($drive['path'], $depth + 1, $maxDepth));
                }
            }
        } else {
            $items = @scandir($path);
            if ($items === false) {
                return $files;
            }

            $excluded = ['.', '..', '$RECYCLE.BIN', 'System Volume Information'];

            foreach ($items as $item) {
                if (in_array(strtolower($item), array_map('strtolower', $excluded))) {
                    continue;
                }

                $fullPath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $item;

                if (is_dir($fullPath)) {
                    // Recursively scan subdirectory
                    $files = array_merge($files, getAllAccessibleFiles($fullPath, $depth + 1, $maxDepth));
                } else {
                    // It's a file
                    $size = @filesize($fullPath) ?: 0;
                    $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));

                    // Only include files under 10MB and common extensions
                    $allowedExtensions = ['php', 'js', 'css', 'html', 'htm', 'txt', 'md', 'json', 'xml', 'sql', 'env', 'ini', 'log'];

                    if ($size < 10 * 1024 * 1024 && in_array($ext, $allowedExtensions)) {
                        $files[] = [
                            'name' => $item,
                            'type' => 'file',
                            'path' => $fullPath,
                            'size' => $size,
                            'extension' => $ext
                        ];
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Ignore errors
    }

    return $files;
}

// Function to merge selected files
function mergeSelectedFiles($files, $outputFile, $separator) {
    if (empty($files)) {
        return ['success' => false, 'error' => 'هیچ فایلی انتخاب نشده است'];
    }

    // Limit number of files for safety
    if (count($files) > 100) {
        return ['success' => false, 'error' => 'تعداد فایل‌ها نمی‌تواند بیشتر از ۱۰۰ عدد باشد'];
    }

    $content = "📁 فایل یکپارچه شده\n";
    $content .= "📅 تاریخ ایجاد: " . date('Y/m/d H:i:s') . "\n";
    $content .= "📊 تعداد فایل‌های درخواستی: " . count($files) . "\n";
    $content .= str_repeat('═', 60) . "\n\n";

    $found = 0;
    $totalSize = 0;

    foreach ($files as $index => $filePath) {
        if (!file_exists($filePath)) {
            $content .= "❌ [فایل یافت نشد] {$filePath}\n";
            $content .= str_repeat('─', 50) . "\n\n";
            continue;
        }

        if (!is_readable($filePath)) {
            $content .= "⚠️  [عدم دسترسی] {$filePath}\n";
            $content .= str_repeat('─', 50) . "\n\n";
            continue;
        }

        $fileContent = @file_get_contents($filePath);
        if ($fileContent === false) {
            $content .= "⚠️  [خطا در خواندن] {$filePath}\n";
            $content .= str_repeat('─', 50) . "\n\n";
            continue;
        }

        $found++;
        $fileSize = filesize($filePath);
        $totalSize += $fileSize;
        $modified = @filemtime($filePath) ? date('Y/m/d H:i:s', filemtime($filePath)) : 'نامعلوم';

        $content .= "📄 فایل " . ($index + 1) . ": {$filePath}\n";
        $content .= "📏 حجم: " . formatBytes($fileSize) . "\n";
        $content .= "🕒 آخرین تغییر: {$modified}\n";
        $content .= str_repeat('─', 50) . "\n";
        $content .= $fileContent . "\n\n";

        if ($filePath !== end($files)) {
            $content .= $separator;
        }
    }

    $content .= "\n" . str_repeat('═', 60) . "\n";
    $content .= "📊 جمع‌بندی:\n";
    $content .= "✅ فایل‌های یکپارچه شده: {$found} از " . count($files) . "\n";
    $content .= "📦 حجم کل: " . formatBytes($totalSize) . "\n";
    $content .= "🎉 عملیات با موفقیت انجام شد.\n";

    // Save to a safe location
    $safeOutput = 'merged_' . date('Ymd_His') . '_' . basename($outputFile);
    $outputPath = __DIR__ . DIRECTORY_SEPARATOR . $safeOutput;

    if (@file_put_contents($outputPath, $content) === false) {
        return ['success' => false, 'error' => 'خطا در ذخیره فایل خروجی'];
    }

    return [
        'success' => true,
        'filename' => $safeOutput,
        'size' => filesize($outputPath),
        'found' => $found,
        'total_size' => $totalSize
    ];
}

function formatBytes($bytes, $precision = 2) {
    if ($bytes <= 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Handle requests
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? $action;
}

try {
    switch ($action) {
        case 'fulltree':
            echo json_encode(getFullTree());
            break;

        case 'contents':
            $path = $_GET['path'] ?? '';
            echo json_encode(getDirectoryContents($path));
            break;

        case 'allfiles':
            echo json_encode(getAllAccessibleFiles());
            break;

        case 'drives':
            echo json_encode(getSystemDrives());
            break;

        case 'merge':
            $files = $input['files'] ?? [];
            $output = $input['output'] ?? 'merged_output.txt';
            $separator = $input['separator'] ?? "───────\n\n";

            echo json_encode(mergeSelectedFiles($files, $output, $separator));
            break;

        case 'download':
            $file = $_GET['file'] ?? '';
            $safeFile = basename($file);
            $filePath = __DIR__ . DIRECTORY_SEPARATOR . $safeFile;

            if ($safeFile && file_exists($filePath) && strpos($safeFile, 'merged_') === 0) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $safeFile . '"');
                header('Content-Length: ' . filesize($filePath));
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
                readfile($filePath);

                // Delete file after download
                @unlink($filePath);
                exit;
            } else {
                http_response_code(404);
                echo 'File not found';
            }
            break;

        default:
            echo json_encode(['error' => 'عملیات نامعتبر: ' . $action]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'خطای سرور: ' . $e->getMessage()]);
}
?>
