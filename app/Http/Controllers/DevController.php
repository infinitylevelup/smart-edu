<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class DevController extends Controller
{
    public function index()
    {
        return view('dev.console-pro');
    }
    
    public function runCommand(Request $request)
    {



        $command = $request->input('command');

    $command = $request->input('command');
    
    // 🔴 اضافه کردن این خط برای دیباگ
    \Log::info("Dev Console Command: {$command}");
    
    if ($command === 'ping') {
        return response()->json([
            'success' => true,
            'message' => '🏓 پونگ! سرور پاسخ می‌دهد',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'user' => auth()->user()->name
        ]);
    }
    






        $params = $request->input('params', []);
        
        Log::info('🎮 کنسول توسعه - دستور اجرا شد', [
            'command' => $command,
            'user' => Auth::id(),
            'params' => $params
        ]);
        
        $startTime = microtime(true);
        $result = ['success' => true, 'execution_time' => 0];
        
        try {
            switch ($command) {
                // 🔧 دستورات سیستم
                case 'ping':
                    $result['message'] = '🏓 پونگ! سرور پاسخ می‌دهد';
                    $result['timestamp'] = now()->format('Y-m-d H:i:s');
                    $result['user'] = Auth::user()->name;
                    break;
                    
                case 'system_info':
                    $result['data'] = [
                        'php_version' => PHP_VERSION,
                        'laravel_version' => app()->version(),
                        'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                        'database' => config('database.default'),
                        'timezone' => config('app.timezone'),
                        'environment' => app()->environment(),
                    ];
                    $result['message'] = 'اطلاعات سیستم';
                    break;
                    
                // 📊 دستورات دیتابیس
                case 'db_stats':
                    $result['data'] = [
                        'exams' => Exam::count(),
                        'users' => User::count(),
                        'classrooms' => Classroom::count(),
                        'exams_today' => Exam::whereDate('created_at', today())->count(),
                    ];
                    $result['message'] = 'آمار دیتابیس';
                    break;
                    
                case 'last_exams':
                    $limit = $params['limit'] ?? 5;
                    $result['data'] = Exam::with('teacher')
                        ->latest()
                        ->limit($limit)
                        ->get(['id', 'title', 'exam_type', 'teacher_id', 'created_at', 'updated_at'])
                        ->map(function($exam) {
                            return [
                                'id' => $exam->id,
                                'title' => $exam->title,
                                'type' => $exam->exam_type,
                                'type_fa' => $exam->type_fa ?? 'نامشخص',
                                'teacher' => $exam->teacher->name ?? 'نامشخص',
                                'created' => $exam->created_at->diffForHumans(),
                                'updated' => $exam->updated_at->diffForHumans(),
                            ];
                        });
                    $result['message'] = "آخرین {$limit} آزمون";
                    break;
                    
                // 🔄 دستورات آپدیت/تست
                case 'test_exam_update':
                    $examId = $params['exam_id'] ?? Exam::first()?->id;
                    
                    if (!$examId) {
                        throw new \Exception('آزمونی برای تست یافت نشد');
                    }
                    
                    $exam = Exam::find($examId);
                    $oldTitle = $exam->title;
                    $oldType = $exam->exam_type;
                    
                    // آپدیت تستی
                    $exam->update([
                        'title' => $oldTitle . ' [آزمایش آپدیت ' . now()->format('H:i:s') . ']',
                        'updated_at' => now()
                    ]);
                    
                    $exam->refresh();
                    
                    $result['data'] = [
                        'exam_id' => $exam->id,
                        'before' => [
                            'title' => $oldTitle,
                            'type' => $oldType,
                            'updated_at' => $exam->getOriginal('updated_at')
                        ],
                        'after' => [
                            'title' => $exam->title,
                            'type' => $exam->exam_type,
                            'updated_at' => $exam->updated_at->format('Y-m-d H:i:s')
                        ],
                        'changed' => $oldTitle !== $exam->title
                    ];
                    $result['message'] = 'تست آپدیت انجام شد';
                    break;
                    
                case 'create_test_exam':
                    $type = $params['type'] ?? 'public';
                    $title = $params['title'] ?? ('آزمون تستی ' . now()->format('H:i:s'));
                    
                    $exam = Exam::create([
                        'teacher_id' => Auth::id(),
                        'user_id' => Auth::id(),
                        'title' => $title,
                        'exam_type' => $type,
                        'duration_minutes' => 60,
                        'is_active' => true,
                        'is_published' => false,
                    ]);
                    
                    $result['data'] = [
                        'id' => $exam->id,
                        'title' => $exam->title,
                        'type' => $exam->exam_type,
                        'type_fa' => $exam->type_fa ?? 'نامشخص',
                        'created_at' => $exam->created_at->format('Y-m-d H:i:s'),
                        'edit_url' => route('teacher.exams.edit', $exam)
                    ];
                    $result['message'] = 'آزمون تستی ایجاد شد';
                    break;
                    
                case 'clear_test_data':
                    $count = Exam::where('teacher_id', Auth::id())
                        ->where('title', 'like', '%تست%')
                        ->orWhere('title', 'like', '%آزمایش%')
                        ->delete();
                        
                    $result['message'] = "{$count} آزمون تستی حذف شد";
                    break;
                    
                // 🛠 دستورات Artisan
                case 'artisan':
                    $artisanCommand = $params['cmd'] ?? 'route:list';
                    $output = [];
                    
                    Artisan::call($artisanCommand, [], $outputBuffer = null);
                    $output = Artisan::output();
                    
                    $result['data'] = [
                        'command' => $artisanCommand,
                        'output' => $output
                    ];
                    $result['message'] = 'دستور Artisan اجرا شد';
                    break;
                    
                case 'migrate_status':
                    Artisan::call('migrate:status', [], $outputBuffer = null);
                    $output = Artisan::output();
                    
                    $result['data'] = ['output' => $output];
                    $result['message'] = 'وضعیت میگریشن‌ها';
                    break;
                    
                // 📝 دستورات سفارشی
                case 'custom_query':
                    $query = $params['query'] ?? 'select 1+1 as result';
                    
                    if (stripos($query, 'delete') !== false || stripos($query, 'drop') !== false) {
                        throw new \Exception('دستورات حذف مجاز نیستند');
                    }
                    
                    $results = DB::select($query);
                    $result['data'] = [
                        'query' => $query,
                        'results' => $results,
                        'count' => count($results)
                    ];
                    $result['message'] = 'کوئری اجرا شد';
                    break;
                    
                default:
                    throw new \Exception("دستور '{$command}' شناخته نشد");
            }
            
        } catch (\Exception $e) {
            $result = [
                'success' => false,
                'message' => '❌ خطا: ' . $e->getMessage(),
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ];
            Log::error('کنسول توسعه - خطا', $result);
        }
        
        $result['execution_time'] = round(microtime(true) - $startTime, 3);
        $result['timestamp'] = now()->format('Y-m-d H:i:s');
        
        return response()->json($result);
    }
    
    public function getExamsList()
    {
        $exams = Exam::where('teacher_id', Auth::id())
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get(['id', 'title', 'exam_type', 'created_at']);
            
        return response()->json(['exams' => $exams]);
    }
    
    public function downloadLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return response()->json(['error' => 'فایل لاگ یافت نشد'], 404);
        }
        
        return response()->download($logFile, 'laravel-log-' . date('Y-m-d') . '.log');
    }
}