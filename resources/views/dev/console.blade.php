<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کنسول توسعه - SmartEdu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #0f172a; color: #e2e8f0; font-family: system-ui; }
        .terminal { background: #1e293b; border-radius: 10px; padding: 20px; }
        .command { color: #10b981; }
        .output { color: #d1d5db; background: #111827; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        .btn-dev { 
            background: #374151; 
            border: 1px solid #4b5563;
            color: #d1d5db;
            margin: 5px;
            transition: all 0.3s;
        }
        .btn-dev:hover { background: #4b5563; transform: translateY(-2px); }
        .success { color: #10b981; }
        .error { color: #ef4444; }
        .data { color: #93c5fd; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4">
            <i class="fas fa-terminal text-success"></i> کنسول توسعه SmartEdu
            <small class="text-muted d-block fs-6">ورژن سریع - برای تست</small>
        </h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark border-secondary mb-3">
                    <div class="card-header bg-black">
                        <i class="fas fa-bolt"></i> دستورات سریع
                    </div>
                    <div class="card-body">
                        <button class="btn btn-dev" onclick="runCommand('test')">
                            <i class="fas fa-check"></i> تست اتصال
                        </button>
                        <button class="btn btn-dev" onclick="runCommand('exams_count')">
                            <i class="fas fa-list"></i> تعداد آزمون‌ها
                        </button>
                        <button class="btn btn-dev" onclick="runCommand('last_exam')">
                            <i class="fas fa-history"></i> آخرین آزمون
                        </button>
                        <button class="btn btn-dev" onclick="runCommand('check_update')">
                            <i class="fas fa-sync-alt"></i> تست آپدیت
                        </button>
                        
                        <hr class="bg-secondary">
                        
                        <div class="mt-3">
                            <label class="form-label">دستور سفارشی</label>
                            <div class="input-group">
                                <input type="text" id="customCommand" class="form-control bg-dark text-light" placeholder="مثلاً: exams_count">
                                <button class="btn btn-success" onclick="runCustom()">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-black">
                        <i class="fas fa-info-circle"></i> وضعیت سیستم
                    </div>
                    <div class="card-body">
                        <div id="systemInfo">
                            <p>در حال بارگذاری...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card bg-dark border-secondary h-100">
                    <div class="card-header bg-black d-flex justify-content-between">
                        <span><i class="fas fa-code"></i> خروجی ترمینال</span>
                        <button class="btn btn-sm btn-outline-light" onclick="clearConsole()">
                            <i class="fas fa-eraser"></i> پاک کردن
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="terminalOutput" class="terminal" style="height: 400px; overflow-y: auto;">
                            <div class="output">
                                <span class="command">$ </span> سیستم راه‌اندازی شد
                            </div>
                            <div class="output">
                                <span class="command">$ </span> برای شروع روی دکمه‌ها کلیک کنید
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-light">$</span>
                                <input type="text" id="liveCommand" class="form-control bg-dark text-light" 
                                       placeholder="دستور را تایپ کنید..." 
                                       onkeypress="if(event.key === 'Enter') runLiveCommand()">
                                <button class="btn btn-primary" onclick="runLiveCommand()">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // توکن امنیتی Laravel
        const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        let commandHistory = [];
        
        // اجرای دستور
        function runCommand(cmd) {
            addOutput(`$ ${cmd}`, 'command');
            
            fetch('/dev/run-command', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ command: cmd })
            })
            .then(r => r.json())
            .then(data => {
                const msg = data.success ? `✅ ${data.message}` : `❌ ${data.message}`;
                addOutput(msg, data.success ? 'success' : 'error');
                
                if (data.data) {
                    console.log('داده دریافتی:', data.data);
                    addOutput(JSON.stringify(data.data, null, 2), 'data');
                }
            })
            .catch(err => {
                addOutput(`❌ خطا در ارتباط: ${err.message}`, 'error');
            });
            
            commandHistory.push(cmd);
        }
        
        // اجرای دستور سفارشی
        function runCustom() {
            const cmd = document.getElementById('customCommand').value;
            if (cmd) {
                runCommand(cmd);
                document.getElementById('customCommand').value = '';
            }
        }
        
        // اجرای دستور از تکست‌باکس پایین
        function runLiveCommand() {
            const cmd = document.getElementById('liveCommand').value;
            if (cmd) {
                runCommand(cmd);
                document.getElementById('liveCommand').value = '';
            }
        }
        
        // اضافه کردن خروجی به ترمینال
        function addOutput(text, type = 'info') {
            const output = document.getElementById('terminalOutput');
            const div = document.createElement('div');
            div.className = `output ${type}`;
            div.innerHTML = text;
            output.appendChild(div);
            output.scrollTop = output.scrollHeight;
        }
        
        // پاک کردن کنسول
        function clearConsole() {
            document.getElementById('terminalOutput').innerHTML = '';
            addOutput('کنسول پاک شد', 'info');
        }
        
        // بارگذاری اولیه
        window.onload = function() {
            // تست اولیه
            fetch('/dev/run-command', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ command: 'test' })
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('systemInfo').innerHTML = `
                    <p><i class="fas fa-server text-info"></i> وضعیت سرور: <span class="text-success">فعال</span></p>
                    <p><i class="fas fa-database text-info"></i> آخرین پاسخ: ${data.message}</p>
                    <p><i class="fas fa-user text-info"></i> کاربر: ${data.user || 'سیستم'}</p>
                    <p><i class="fas fa-clock text-info"></i> زمان: ${new Date().toLocaleTimeString('fa-IR')}</p>
                `;
            });
        };
        
        console.log('🚀 کنسول توسعه بارگذاری شد');
        console.log('دسترسی: /dev/console');
    </script>
</body>
</html>