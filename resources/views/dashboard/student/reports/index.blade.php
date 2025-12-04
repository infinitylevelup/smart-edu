@extends('layouts.app')
@section('title', 'گزارش‌های من - SmartEdu')

@push('styles')
    <style>
        /* تم رنگ هماهنگ با SmartEdu */
        :root {
            --primary: #7B68EE;
            --primary-light: rgba(123, 104, 238, 0.1);
            --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --secondary: #FF6B9D;
            --secondary-light: rgba(255, 107, 157, 0.1);
            --accent: #00D4AA;
            --accent-light: rgba(0, 212, 170, 0.1);
            --gold: #FFD166;
            --light: #ffffff;
            --dark: #2D3047;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .reports-page {
            animation: fadeIn 0.6s ease both;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px 100px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* هدر صفحه */
        .page-header {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px;
            box-shadow: var(--shadow-md);
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .page-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* دکمه‌های هدر */
        .header-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        @media (min-width: 768px) {
            .header-buttons {
                margin-top: 0;
            }
        }

        .btn-primary-custom {
            background: var(--gradient-1);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: var(--radius-lg);
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
            color: white;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-primary-custom:hover::before {
            right: 100%;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--primary);
            padding: 12px 24px;
            border-radius: var(--radius-lg);
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
        }

        /* آلرت‌ها */
        .alert-custom {
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            border: none;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            animation: slideInRight 0.6s ease-out;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
            border-right: 4px solid var(--accent);
            color: var(--dark);
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(255, 107, 157, 0.05));
            border-right: 4px solid var(--secondary);
            color: var(--dark);
        }

        /* کارت گزارش */
        .report-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 22px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            height: 100%;
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
            position: relative;
            overflow: hidden;
        }

        .report-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .report-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .report-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .report-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .report-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .report-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .report-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .report-title {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--dark);
            line-height: 1.4;
            flex: 1;
        }

        .score-badge {
            background: var(--gradient-1);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.95rem;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(123, 104, 238, 0.3);
            animation: pulse 2s infinite;
        }

        .report-meta {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-left: 15px;
        }

        .meta-item:first-child {
            margin-left: 0;
        }

        .meta-item i {
            font-size: 0.9rem;
        }

        .report-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .status-completed {
            background: var(--accent-light);
            color: var(--accent);
        }

        .status-pending {
            background: rgba(255, 209, 102, 0.15);
            color: #D4A017;
        }

        .status-review {
            background: var(--secondary-light);
            color: var(--secondary);
        }

        .report-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }

        .action-btn {
            flex: 1;
            padding: 12px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
        }

        .action-btn:active {
            transform: scale(0.96);
        }

        .btn-result {
            background: var(--light-gray);
            color: var(--dark);
            border-color: rgba(0, 0, 0, 0.05);
        }

        .btn-result:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.15);
        }

        .btn-analysis {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-analysis:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.4);
        }

        .btn-analysis::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-analysis:hover::before {
            right: 100%;
        }

        /* وضعیت خالی */
        .empty-state {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 60px 30px;
            text-align: center;
            box-shadow: var(--shadow-md);
            animation: slideInRight 0.6s ease-out;
            border: 2px dashed var(--primary-light);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--accent-light));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: var(--primary);
            font-size: 3rem;
            animation: bounce 3s infinite;
        }

        .empty-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-text {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        /* بهینه‌سازی موبایل */
        @media (max-width: 768px) {
            .reports-page {
                padding: 15px 10px 90px;
            }

            .page-header {
                padding: 20px;
            }

            .page-title {
                font-size: 1.3rem;
            }

            .header-buttons {
                flex-direction: column;
                width: 100%;
            }

            .btn-primary-custom,
            .btn-outline-custom {
                width: 100%;
                justify-content: center;
            }

            .report-card {
                padding: 18px;
            }

            .report-header {
                flex-direction: column;
                gap: 10px;
            }

            .score-badge {
                align-self: flex-start;
            }

            .meta-item {
                display: block;
                margin-left: 0;
                margin-bottom: 5px;
            }

            .report-actions {
                flex-direction: column;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-icon {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .action-btn,
        .btn-primary-custom,
        .btn-outline-custom {
            min-height: 44px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    @php
        /**
         * انتظار معمول ما اینه که کنترلر یکی از این‌ها رو بده:
         * - $reports (collection)
         * - یا $attempts (collection)
         * فعلاً هر دو رو ساپورت می‌کنیم با fallback.
         */
        $reports = $reports ?? null;
        $attempts = $attempts ?? null;

        $items = collect();

        if ($reports instanceof \Illuminate\Support\Collection) {
            $items = $reports;
        } elseif ($attempts instanceof \Illuminate\Support\Collection) {
            $items = $attempts;
        }

        // روت‌های امن
        $examsIndexRoute = route('student.exams.index');
        $dashboardRoute = route('student.index');
        $classroomsRoute = route('student.classrooms.index');
        $profileRoute = \Route::has('student.profile') ? route('student.profile') : '#';
    @endphp

    <div class="reports-page">

        {{-- ================= HEADER ================= --}}
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-chart-line" style="color: var(--primary);"></i>
                        گزارش‌های من
                    </h1>
                    <p class="page-subtitle">
                        در این بخش می‌تونی گزارش عملکردت در آزمون‌ها و تحلیل‌های مرتبط رو ببینی.
                        هر گزارش یک داستان از پیشرفت تو رو تعریف می‌کنه 📈
                    </p>
                </div>

                <div class="d-flex flex-column flex-md-row gap-2 header-buttons">
                    <a href="{{ $examsIndexRoute }}" class="btn-primary-custom">
                        <i class="fas fa-play-circle"></i>
                        شروع آزمون جدید
                    </a>
                    <a href="{{ $dashboardRoute }}" class="btn-outline-custom">
                        <i class="fas fa-home"></i>
                        داشبورد
                    </a>
                </div>
            </div>
        </div>

        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div class="alert-custom alert-success-custom d-flex align-items-center gap-3">
                <div style="font-size: 1.5rem; color: var(--accent);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert-custom alert-danger-custom d-flex align-items-center gap-3">
                <div style="font-size: 1.5rem; color: var(--secondary);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ================= EMPTY STATE ================= --}}
        @if ($items->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>

                <h2 class="empty-title">هنوز گزارشی نداری!</h2>

                <p class="empty-text">
                    گزارش‌ها بعد از شرکت در آزمون‌ها ایجاد می‌شن.
                    هر آزمون یک قدم به سمت پیشرفت و هر گزارش یک نقشه راه برای موفقیت‌های بعدی‌ست.
                </p>

                <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                    <a href="{{ $examsIndexRoute }}" class="btn-primary-custom" style="padding: 15px 30px;">
                        <i class="fas fa-bolt"></i>
                        شروع اولین آزمون
                    </a>
                    <a href="{{ $classroomsRoute }}" class="btn-outline-custom" style="padding: 15px 30px;">
                        <i class="fas fa-users"></i>
                        کلاس‌های من
                    </a>
                </div>
            </div>
        @else
            {{-- ================= REPORTS LIST ================= --}}
            <div class="row g-3">
                @foreach ($items as $item)
                    @php
                        // بیشترین حالت‌های ممکن:
                        $exam = $item->exam ?? null;
                        $examTitle = $exam->title ?? ($item->exam_title ?? 'آزمون بدون عنوان');
                        $classTitle = $exam->classroom->title ?? ($exam->classroom->name ?? null);
                        $subjectName = $exam->subject->name ?? ($exam->subject->title ?? null);

                        $score = $item->score ?? ($item->total_score ?? null);
                        $submittedAt = $item->submitted_at ?? ($item->created_at ?? null);
                        $status = $item->status ?? 'completed'; // completed/pending/review

                        $statusClass = match ($status) {
                            'completed' => 'status-completed',
                            'pending' => 'status-pending',
                            'review' => 'status-review',
                            default => 'status-completed',
                        };

                        $statusIcon = match ($status) {
                            'completed' => 'fas fa-check-circle',
                            'pending' => 'fas fa-clock',
                            'review' => 'fas fa-eye',
                            default => 'fas fa-chart-line',
                        };

                        $statusText = match ($status) {
                            'completed' => 'تکمیل شده',
                            'pending' => 'در انتظار',
                            'review' => 'در حال بررسی',
                            default => 'تکمیل شده',
                        };
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <div class="report-card">
                            <div class="report-header">
                                <h3 class="report-title">{{ $examTitle }}</h3>
                                @if (!is_null($score))
                                    <div class="score-badge">
                                        {{ $score }}
                                    </div>
                                @endif
                            </div>

                            <div class="report-status {{ $statusClass }}">
                                <i class="{{ $statusIcon }}"></i>
                                <span>{{ $statusText }}</span>
                            </div>

                            <div class="report-meta">
                                @if ($classTitle)
                                    <div class="meta-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        کلاس: {{ $classTitle }}
                                    </div>
                                @endif

                                @if ($subjectName)
                                    <div class="meta-item">
                                        <i class="fas fa-book"></i>
                                        درس: {{ $subjectName }}
                                    </div>
                                @endif

                                @if ($submittedAt)
                                    <div class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        تاریخ: {{ jdate($submittedAt)->format('Y/m/d') }}
                                    </div>
                                @endif
                            </div>

                            <div class="report-actions">
                                @if (Route::has('student.attempts.result') && !empty($item->id))
                                    <a href="{{ route('student.attempts.result', $item) }}" class="action-btn btn-result">
                                        <i class="fas fa-poll"></i>
                                        نتیجه
                                    </a>
                                @else
                                    <button class="action-btn btn-result" disabled>
                                        <i class="fas fa-poll"></i>
                                        نتیجه
                                    </button>
                                @endif

                                @if (Route::has('student.attempts.analysis') && !empty($item->id))
                                    <a href="{{ route('student.attempts.analysis', $item) }}"
                                        class="action-btn btn-analysis">
                                        <i class="fas fa-chart-pie"></i>
                                        تحلیل
                                    </a>
                                @else
                                    <button class="action-btn btn-analysis" disabled>
                                        <i class="fas fa-chart-pie"></i>
                                        تحلیل
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ================= PAGINATION (اگر وجود دارد) ================= --}}
            @if (method_exists($items, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    <div class="pagination-custom">
                        {{ $items->links() }}
                    </div>
                </div>
            @endif
        @endif

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // لرزش موبایل برای تعاملات
                if (navigator.vibrate) {
                    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate ||
                        navigator.msVibrate;
                }

                // انیمیشن‌های کارت‌ها هنگام هاور
                const reportCards = document.querySelectorAll('.report-card');
                reportCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });

                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('.action-btn')) {
                            // افکت کلیک روی کل کارت
                            this.style.transform = 'scale(0.98)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 150);

                            if (navigator.vibrate) {
                                navigator.vibrate(30);
                            }
                        }
                    });
                });

                // دکمه‌های تحلیل
                const analysisButtons = document.querySelectorAll('.btn-analysis:not([disabled])');
                analysisButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // افکت کلیک
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate([30, 50, 30]);
                        }

                        // نمایش مودال در حال بارگذاری
                        showLoadingModal('تحلیل آماری', 'در حال تحلیل نتایج آزمون...');
                    });
                });

                // دکمه‌های نتیجه
                const resultButtons = document.querySelectorAll('.btn-result:not([disabled])');
                resultButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                });

                // تابع نمایش مودال بارگذاری
                function showLoadingModal(title, message) {
                    const modal = document.createElement('div');
                    modal.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            z-index: 1000;
            text-align: center;
            max-width: 320px;
            width: 85%;
            animation: scaleIn 0.3s ease forwards;
            border: 3px solid var(--primary);
        `;

                    modal.innerHTML = `
            <div style="font-size: 2.5rem; margin-bottom: 15px; color: var(--primary);">
                <i class="fas fa-chart-line fa-spin"></i>
            </div>
            <h3 style="margin-bottom: 12px; color: var(--dark); font-size: 1.2rem; font-weight: 700;">${title}</h3>
            <p style="color: var(--gray); margin-bottom: 20px; font-size: 0.95rem; line-height: 1.5;">${message}</p>
            <div class="progress" style="height: 8px; border-radius: 10px; background: var(--light-gray); overflow: hidden;">
                <div class="progress-bar" style="height:100%; width:0%; background: var(--gradient-1); border-radius:10px; animation: loading 1.5s infinite;"></div>
            </div>
        `;

                    document.body.appendChild(modal);

                    const overlay = document.createElement('div');
                    overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            animation: fadeIn 0.3s ease;
        `;
                    document.body.appendChild(overlay);

                    // حذف خودکار بعد از 2 ثانیه (در حالت واقعی این اتفاق نمی‌افته)
                    setTimeout(() => {
                        if (document.body.contains(modal)) {
                            modal.remove();
                            overlay.remove();
                        }
                    }, 2000);
                }

                // اضافه کردن استایل‌های انیمیشن
                const style = document.createElement('style');
                style.textContent = `
        @keyframes scaleIn {
            from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
            to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }

        @keyframes loading {
            0% { width: 0%; transform: translateX(-100%); }
            50% { width: 100%; transform: translateX(0%); }
            100% { width: 100%; transform: translateX(100%); }
        }

        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
                document.head.appendChild(style);
            });
        </script>
    @endpush
@endsection
