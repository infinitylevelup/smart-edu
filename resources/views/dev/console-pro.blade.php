{{-- resources/views/dev/console-pro.blade.php --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>🚀 کنسول توسعه حرفه‌ای - SmartEdu</title>
    
    <!-- استایل‌ها -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --darker: #0f172a;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .console-header {
            background: linear-gradient(90deg, var(--darker), var(--dark));
            border-bottom: 3px solid var(--primary);
        }
        
        .terminal-output {
            background: #000;
            color: #00ff00;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 14px;
            line-height: 1.5;
            height: 500px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-word;
        }
        
        .command-input {
            background: #111827;
            border: 2px solid #374151;
            color: #00ff00;
            font-family: 'Consolas', monospace;
            border-radius: 8px;
        }
        
        .command-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
        }
        
        .btn-command {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-command:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        .btn-success { background: linear-gradient(135deg, #10b981, #059669); }
        .btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .btn-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .btn-info { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        
        .log-entry {
            padding: 8px 12px;
            border-bottom: 1px solid #374151;
            animation: fadeIn 0.3s ease;
        }
        
        .log-success { border-right: 4px solid var(--success); }
        .log-error { border-right: 4px solid var(--danger); }
        .log-info { border-right: 4px solid var(--primary); }
        .log-warning { border-right: 4px solid var(--warning); }
        
        .timestamp {
            color: #94a3b8;
            font-size: 0.85em;
        }
        
        .command-text {
            color: #60a5fa;
            font-weight: 600;
        }
        
        .result-text {
            color: #d1d5db;
        }
        
        .execution-time {
            color: #fbbf24;
            font-size: 0.9em;
        }
        
        .quick-action {
            cursor: pointer;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.2s;
        }
        
        .quick-action:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }
        
        .quick-action i {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .copy-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .code-block:hover .copy-btn {
            opacity: 1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- هدر -->
        <div class="console-header glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-0">
                        <i class="fas fa-terminal text-primary"></i> 
                        کنسول توسعه حرفه‌ای
                        <small class="text-muted fs-6">SmartEdu Pro v1.0</small>
                    </h1>
                    <div class="text-light mt-2">
                        <span class="badge bg-primary">{{ config('app.env') }}</span>
                        <span class="badge bg-success ms-2">{{ config('app.name') }}</span>
                        <span class="badge bg-info ms-2">Laravel {{ app()->version() }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <button class="btn btn-light me-2" onclick="clearConsole()">
                        <i class="fas fa-eraser"></i> پاک کردن
                    </button>
                    <button class="btn btn-success" onclick="downloadLogs()">
                        <i class="fas fa-download"></i> دانلود لاگ
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- سایدبار دستورات سریع -->
            <div class="col-lg-3">
                <div class="glass-card p-3 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-bolt text-warning"></i> دستورات سریع
                    </h5>
                    
                    <div class="mb-4">
                        <h6 class="text-light mb-2">🔄 سیستم</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-command" onclick="runQuickCommand('ping')">
                                <i class="fas fa-satellite-dish"></i> تست اتصال
                            </button>
                            <button class="btn btn-command btn-info" onclick="runQuickCommand('system_info')">
                                <i class="fas fa-server"></i> اطلاعات سیستم
                            </button>
                            <button class="btn btn-command" onclick="runQuickCommand('db_stats')">
                                <i class="fas fa-database"></i> آمار دیتابیس
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-light mb-2">📊 آزمون‌ها</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-command btn-success" onclick="runQuickCommand('last_exams', {limit: 5})">
                                <i class="fas fa-list"></i> آخرین آزمون‌ها
                            </button>
                            <button class="btn btn-command btn-warning" onclick="runQuickCommand('test_exam_update')">
                                <i class="fas fa-sync-alt"></i> تست آپدیت
                            </button>
                            <div class="input-group mt-2">
                                <select id="examTypeSelect" class="form-select bg-dark text-light">
                                    <option value="public">عمومی</option>
                                    <option value="class_single">کلاسی تک‌درس</option>
                                    <option value="class_comprehensive">کلاسی جامع</option>
                                </select>
                                <button class="btn btn-primary" onclick="createTestExam()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-light mb-2">🛠 Artisan</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-command" onclick="runArtisan('route:list')">
                                <i class="fas fa-route"></i> Route List
                            </button>
                            <button class="btn btn-command btn-info" onclick="runArtisan('migrate:status')">
                                <i class="fas fa-database"></i> Migrate Status
                            </button>
                            <button class="btn btn-command btn-danger" onclick="runQuickCommand('clear_test_data')">
                                <i class="fas fa-trash"></i> پاکسازی تستی
                            </button>
                        </div>
                    </div>
                    
                    <!-- انتخاب آزمون برای تست -->
                    <div class="mb-3">
                        <label class="form-label">انتخاب آزمون برای تست</label>
                        <select id="examSelect" class="form-select bg-dark text-light" onchange="updateSelectedExam(this.value)">
                            <option value="">بارگذاری...</option>
                        </select>
                    </div>
                    
                    <!-- لاگ‌های زنده -->
                    <div class="mt-4">
                        <h6 class="text-light mb-2">
                            <i class="fas fa-history"></i> لاگ‌های زنده
                            <span class="badge bg-secondary float-end" id="logCount">0</span>
                        </h6>
                        <div id="liveLogs" style="max-height: 200px; overflow-y: auto;">
                            <!-- لاگ‌ها اینجا نمایش داده می‌شوند -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ترمینال اصلی -->
            <div class="col-lg-6">
                <div class="glass-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-terminal text-success"></i> ترمینال اجرا
                        </h5>
                        <div class="text-muted">
                            <span id="connectionStatus" class="badge bg-success">
                                <i class="fas fa-circle pulse"></i> متصل
                            </span>
                            <span class="ms-2">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- خروجی ترمینال -->
                    <div id="terminalOutput" class="terminal-output p-3 rounded mb-3">
                        <div class="log-entry log-info">
                            <span class="timestamp">[{{ now()->format('H:i:s') }}]</span>
                            <span class="command-text">$</span>
                            <span class="result-text">خوش آمدید به کنسول توسعه SmartEdu</span>
                        </div>
                        <div class="log-entry">
                            <span class="timestamp">[{{ now()->format('H:i:s') }}]</span>
                            <span class="command-text">$</span>
                            <span class="result-text">دستورات را اجرا کنید یا کدهای تست را paste کنید</span>
                        </div>
                    </div>
                    
                    <!-- ورودی دستور -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-dark text-light">$</span>
                        <input type="text" 
                               id="commandInput" 
                               class="form-control command-input" 
                               placeholder="دستور را وارد کنید... (مثال: test_exam_update)" 
                               onkeypress="if(event.key === 'Enter') executeCommand()"
                               autocomplete="off">
                        <button class="btn btn-primary" onclick="executeCommand()">
                            <i class="fas fa-paper-plane"></i> اجرا
                        </button>
                    </div>
                    
                    <!-- دستورات پیشنهادی -->
                    <div class="mb-3">
                        <small class="text-muted">دستورات پیشنهادی:</small>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-secondary cursor-pointer" onclick="insertCommand('db_stats')">db_stats</span>
                            <span class="badge bg-secondary cursor-pointer" onclick="insertCommand('last_exams')">last_exams</span>
                            <span class="badge bg-secondary cursor-pointer" onclick="insertCommand('system_info')">system_info</span>
                            <span class="badge bg-secondary cursor-pointer" onclick="insertCommand('artisan', {cmd: 'route:list'})">artisan</span>
                        </div>
                    </div>
                    
                    <!-- تاریخچه دستورات -->
                    <div>
                        <h6 class="text-light">
                            <i class="fas fa-history"></i> تاریخچه دستورات
                            <button class="btn btn-sm btn-outline-light float-end" onclick="clearHistory()">پاک کردن</button>
                        </h6>
                        <div id="commandHistory" class="text-muted small">
                            <!-- تاریخچه اینجا نمایش داده می‌شود -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- پنل کد/نتایج -->
            <div class="col-lg-3">
                <div class="glass-card p-3 h-100">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-code text-info"></i> نتایج/کد
                    </h5>
                    
                    <!-- تب‌ها -->
                    <ul class="nav nav-tabs" id="resultsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="json-tab" data-bs-toggle="tab" data-bs-target="#json" type="button">
                                <i class="fas fa-code"></i> JSON
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="php-tab" data-bs-toggle="tab" data-bs-target="#php" type="button">
                                <i class="fab fa-php"></i> PHP
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="js-tab" data-bs-toggle="tab" data-bs-target="#js" type="button">
                                <i class="fab fa-js"></i> JS
                            </button>
                        </li>
                    </ul>
                    
                    <!-- محتوای تب‌ها -->
                    <div class="tab-content mt-3" id="resultsTabContent">
                        <!-- تب JSON -->
                        <div class="tab-pane fade show active" id="json" role="tabpanel">
                            <div class="code-block position-relative">
                                <button class="copy-btn" onclick="copyToClipboard('jsonOutput')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <pre id="jsonOutput" class="bg-dark p-3 rounded text-light" style="max-height: 300px; overflow: auto;">
{
  "status": "آماده",
  "message": "نتایج اجرای دستورات اینجا نمایش داده می‌شود"
}
                                </pre>
                            </div>
                        </div>
                        
                        <!-- تب PHP -->
                        <div class="tab-pane fade" id="php" role="tabpanel">
                            <div class="code-block position-relative">
                                <button class="copy-btn" onclick="copyToClipboard('phpOutput')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <pre id="phpOutput" class="bg-dark p-3 rounded text-light" style="max-height: 300px; overflow: auto;">
&lt;?php
// کدهای PHP برای تست
public function testExamUpdate()
{
    $exam = Exam::find(1);
    $exam->update(['title' => 'آزمایش آپدیت']);
    return $exam;
}
                                </pre>
                            </div>
                        </div>
                        
                        <!-- تب JavaScript -->
                        <div class="tab-pane fade" id="js" role="tabpanel">
                            <div class="code-block position-relative">
                                <button class="copy-btn" onclick="copyToClipboard('jsOutput')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <pre id="jsOutput" class="bg-dark p-3 rounded text-light" style="max-height: 300px; overflow: auto;">
// کدهای JavaScript برای تست
async function testUpdate() {
    const response = await fetch('/dev/run-command', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': token},
        body: JSON.stringify({command: 'test_exam_update'})
    });
    return await response.json();
}
                                </pre>
                            </div>
                        </div>
                    </div>
                    
                    <!-- دکمه‌های اکشن -->
                    <div class="d-grid gap-2 mt-3">
                        <button class="btn btn-success" onclick="copyResults()">
                            <i class="fas fa-copy"></i> کپی نتایج
                        </button>
                        <button class="btn btn-warning" onclick="exportResults()">
                            <i class="fas fa-file-export"></i> خروجی JSON
                        </button>
                        <button class="btn btn-info" onclick="loadSampleCode()">
                            <i class="fas fa-code"></i> نمونه کد
                        </button>
                    </div>
                    
                    <!-- اطلاعات اجرا -->
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-light mb-2">📊 اطلاعات اجرا</h6>
                        <div class="row small">
                            <div class="col-6">
                                <div class="text-muted">زمان اجرا:</div>
                                <div id="execTime">0.000s</div>
                            </div>
                            <div class="col-6">
                                <div class="text-muted">وضعیت:</div>
                                <div id="execStatus" class="text-success">آماده</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      <!-- اسکریپت‌ها -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="{{ asset('assets/js/dev-console.js') }}"></script>
</body>
</html>