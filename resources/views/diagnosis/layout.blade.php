<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ماژول تشخیص - {{ $title ?? 'پنل' }}</title>
    <style>
        /* استایل‌های اصلی */
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Vazir', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: var(--light-color);
            color: var(--dark-color);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* سایدبار */
        .sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            margin: 0 0 30px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            text-align: center;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar nav ul li {
            margin-bottom: 10px;
        }

        .sidebar nav ul li a {
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            display: block;
            transition: all 0.3s;
        }

        .sidebar nav ul li a:hover {
            background: #374151;
            color: white;
        }

        .sidebar nav ul li a.active {
            background: var(--primary-color);
            color: white;
        }

        /* محتوا */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* هدر */
        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .header h1 {
            margin: 0;
            color: var(--dark-color);
        }

        .header .breadcrumb {
            color: #6b7280;
            margin-top: 5px;
        }

        /* کارت‌ها */
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        /* دکمه‌ها */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-success {
            background: var(--secondary-color);
            color: white;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        /* جدول */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background: #f9fafb;
            font-weight: bold;
            color: var(--dark-color);
        }

        .table tr:hover {
            background: #f9fafb;
        }

        /* فرم‌ها */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* آلرت‌ها */
        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-color: #a7f3d0;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-color: #fecaca;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-color: #bfdbfe;
        }

        /* کد */
        .code-block {
            background: #1f2937;
            color: #e5e7eb;
            padding: 20px;
            border-radius: 6px;
            font-family: 'Monaco', 'Consolas', monospace;
            overflow-x: auto;
            direction: ltr;
        }

        /* مودال */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        /* فایل‌ویور */
        .file-viewer {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .file-header {
            background: #334155;
            padding: 15px 20px;
            border-bottom: 1px solid #475569;
        }

        .file-content {
            padding: 0;
            overflow-x: auto;
        }

        .file-lines {
            display: flex;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 14px;
            line-height: 1.5;
        }

        .line-numbers {
            background: #334155;
            color: #94a3b8;
            padding: 15px 20px;
            text-align: left;
            user-select: none;
            border-right: 1px solid #475569;
        }

        .line-content {
            flex: 1;
            padding: 15px 20px;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* رسپانسیو */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .content {
                padding: 20px;
            }
        }

        /* بدنه اصلی استایل‌ها */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.badge-local {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.badge-production {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.badge-testing {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.badge-staging {
    background: #e0e7ff;
    color: #3730a3;
    border: 1px solid #c7d2fe;
}

/* کارت‌های اطلاعات */
.info-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid var(--border-color);
}

.info-card h3 {
    margin-top: 0;
    color: var(--primary-color);
    border-bottom: 2px solid #f3f4f6;
    padding-bottom: 10px;
}
    </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        <!-- سایدبار -->
        <aside class="sidebar">
            <h2>🔍 ماژول تشخیص</h2>
<nav>
    <ul>
        <li><a href="{{ route('diagnosis.dashboard') }}" class="{{ request()->routeIs('diagnosis.dashboard') ? 'active' : '' }}">📊 داشبورد</a></li>
        <li><a href="{{ route('diagnosis.structure') }}" class="{{ request()->routeIs('diagnosis.structure') ? 'active' : '' }}">📁 ساختار پروژه</a></li>
        <li><a href="{{ route('diagnosis.merge') }}" class="{{ request()->routeIs('diagnosis.merge') ? 'active' : '' }}">🧠 ادغام هوشمند</a></li>
        <li><a href="{{ route('diagnosis.analysis') }}" class="{{ request()->routeIs('diagnosis.analysis') ? 'active' : '' }}">📈 تحلیل پروژه</a></li>
        <li><a href="{{ route('diagnosis.security') }}" class="{{ request()->routeIs('diagnosis.security') ? 'active' : '' }}">🔐 امنیت</a></li>
        <li><a href="{{ route('diagnosis.performance') }}" class="{{ request()->routeIs('diagnosis.performance') ? 'active' : '' }}">⚡ عملکرد</a></li>
    </ul>
</nav>

            <div style="margin-top: 50px; padding-top: 20px; border-top: 1px solid #374151;">
                <small style="color: #9ca3af;">
                    پروژه: Smart-Edu<br>
                    نسخه: 1.0.0<br>
                    وضعیت: فعال
                </small>
            </div>
        </aside>

        <!-- محتوای اصلی -->
        <main class="content">
            <div class="header">
                <h1>@yield('title', 'پنل تشخیص پروژه')</h1>
                <div class="breadcrumb">
                    @yield('breadcrumb', 'ماژول تشخیص')
                </div>
            </div>

            <!-- پیام‌های وضعیت -->
            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    ℹ️ {{ session('info') }}
            @endif

            <!-- محتوای صفحه -->
            @yield('content')
        </main>
    </div>

    <!-- اسکریپت‌ها -->
    <script>
        // باز کردن/بستن مودال
        function openModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        // کپی به کلیپ‌بورد
        function copyToClipboard(text, elementId) {
            navigator.clipboard.writeText(text).then(function() {
                const btn = document.getElementById(elementId);
                const originalText = btn.textContent;
                btn.textContent = '✅ کپی شد!';
                btn.classList.add('btn-success');
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.classList.remove('btn-success');
                }, 2000);
            });
        }

        // لود کردن خودکار مودال در صورت خطا
        document.addEventListener('DOMContentLoaded', function() {
            const hasError = "{{ session('error') ?: '' }}";
            const hasMergedText = "{{ session('mergedText') ?: '' }}";

            if (hasError || hasMergedText) {
                openModal('mergeModal');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
