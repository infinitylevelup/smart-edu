@extends('layouts.app')
@section('title', 'معلمان من - SmartEdu')

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

        .teachers-page {
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* هدر صفحه */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            animation: slideInRight 0.5s ease-out;
        }

        .page-title-section h1 {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .page-title-section h1 i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce 2s infinite;
        }

        .page-subtitle {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 600px;
        }

        /* دکمه‌های هدر */
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
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
            box-shadow: var(--shadow-md);
        }

        /* کارت معلم */
        .teacher-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 0;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            height: 100%;
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
            position: relative;
            overflow: hidden;
        }

        .teacher-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .teacher-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .teacher-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .teacher-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .teacher-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .teacher-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .teacher-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .teacher-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
            z-index: 1;
        }

        /* هدر کارت */
        .teacher-header {
            background: var(--gradient-1);
            color: white;
            padding: 25px 20px;
            position: relative;
            overflow: hidden;
        }

        .teacher-header::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2), transparent 70%);
            border-radius: 50%;
        }

        .teacher-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 2rem;
            font-weight: 800;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: 4px solid white;
            position: relative;
            z-index: 2;
            transition: all 0.3s;
            animation: float 3s ease-in-out infinite;
        }

        .teacher-card:hover .teacher-avatar {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
        }

        .teacher-info {
            position: relative;
            z-index: 2;
            margin-top: 15px;
        }

        .teacher-name {
            font-weight: 800;
            font-size: 1.3rem;
            margin: 0 0 5px 0;
            color: white;
        }

        .teacher-meta {
            font-size: 0.9rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.95);
        }

        .teacher-meta i {
            font-size: 0.9rem;
        }

        /* بدنه کارت */
        .teacher-body {
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .teacher-specialty {
            background: var(--accent-light);
            color: var(--accent);
            padding: 10px 15px;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            border: 2px solid rgba(0, 212, 170, 0.2);
            animation: pulse 2s infinite;
        }

        .teacher-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .teacher-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            background: var(--light-gray);
            color: var(--dark);
            border: 2px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .teacher-chip:hover {
            transform: translateY(-3px);
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .teacher-chip i {
            font-size: 0.9rem;
        }

        .teacher-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
        }

        .action-btn {
            padding: 14px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .action-btn:active {
            transform: scale(0.97);
        }

        .btn-profile {
            background: var(--light-gray);
            color: var(--dark);
            border-color: rgba(0, 0, 0, 0.05);
        }

        .btn-profile:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.15);
        }

        .btn-message {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.3);
        }

        .btn-message:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.4);
        }

        .btn-message::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-message:hover::before {
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
            max-width: 600px;
            margin: 0 auto;
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
            animation: float 4s ease-in-out infinite;
        }

        .empty-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-text {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .empty-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: var(--gradient-1);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: var(--radius-lg);
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
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

        /* بهینه‌سازی موبایل */
        @media (max-width: 768px) {
            .teachers-page {
                padding: 15px 10px 90px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .page-title-section h1 {
                font-size: 1.5rem;
            }

            .page-title-section h1 i {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .teacher-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .teacher-name {
                font-size: 1.1rem;
            }

            .teacher-body {
                padding: 15px;
            }

            .teacher-chips {
                justify-content: center;
            }

            .action-btn {
                padding: 12px;
                font-size: 0.9rem;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-icon {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .empty-actions {
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-outline-custom {
                width: 100%;
                justify-content: center;
            }
        }

        @media (min-width: 992px) {
            .teacher-actions {
                flex-direction: row;
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
        // ✅ مقاوم در برابر null
        $teachers = $teachers ?? collect();

        // Route-safe ها
        $hasTeacherShow = \Route::has('student.teachers.show');
        $hasMessageCreate = \Route::has('student.messages.create');

        $classroomsRoute = route('student.classrooms.index');
    @endphp

    <div class="teachers-page">
        {{-- ================= HEADER ================= --}}
        <div class="page-header">
            <div class="page-title-section">
                <h1>
                    <i class="fas fa-chalkboard-teacher"></i>
                    معلمان من
                </h1>
                <p class="page-subtitle">
                    لیست معلم‌هایی که در کلاس‌های شما فعال هستند.
                    هر معلم یک راهنمای اختصاصی در مسیر یادگیری شماست 👨‍🏫
                </p>
            </div>

            <a href="{{ $classroomsRoute }}" class="btn-outline-custom">
                <i class="fas fa-users"></i>
                کلاس‌های من
            </a>
        </div>

        {{-- ================= TEACHERS LIST ================= --}}
        @if ($teachers->count())
            <div class="row g-4">
                @foreach ($teachers as $t)
                    @php
                        $name = $t->display_name ?? ($t->name ?? 'معلم');
                        $initials = mb_substr(trim($name), 0, 1) ?: 'م';
                        $email = $t->email ?? 'ایمیل ثبت نشده';
                        $phone = $t->phone ?? null;
                        $specialty = $t->specialty ?? null;
                        $role = $t->role ?? 'teacher';
                        $roleText = match ($role) {
                            'teacher' => 'معلم',
                            'assistant' => 'دستیار',
                            'expert' => 'متخصص',
                            default => $role,
                        };

                        // تاریخ به ساده‌ترین شکل
                        $createdAt = $t->created_at ?? null;
                        $formattedDate = $createdAt ? $createdAt->format('Y/m/d') : null;
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="teacher-card">
                            <div class="teacher-header">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="teacher-avatar">
                                        {{ $initials }}
                                    </div>

                                    <div class="teacher-info">
                                        <h3 class="teacher-name">{{ $name }}</h3>
                                        <div class="teacher-meta">
                                            <i class="fas fa-envelope"></i>
                                            {{ $email }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="teacher-body">
                                @if (!empty($specialty))
                                    <div class="teacher-specialty">
                                        <i class="fas fa-graduation-cap"></i>
                                        {{ $specialty }}
                                    </div>
                                @endif

                                <div class="teacher-chips">
                                    <span class="teacher-chip">
                                        <i class="fas fa-user-tag"></i>
                                        نقش: {{ $roleText }}
                                    </span>

                                    @if (!empty($phone))
                                        <span class="teacher-chip">
                                            <i class="fas fa-phone"></i>
                                            {{ $phone }}
                                        </span>
                                    @endif

                                    @if (!empty($formattedDate))
                                        <span class="teacher-chip">
                                            <i class="fas fa-calendar-alt"></i>
                                            از {{ $formattedDate }}
                                        </span>
                                    @endif
                                </div>

                                <div class="teacher-actions">
                                    {{-- پروفایل معلم --}}
                                    @if ($hasTeacherShow)
                                        <a href="{{ route('student.teachers.show', $t->id) }}"
                                            class="action-btn btn-profile">
                                            <i class="fas fa-user-circle"></i>
                                            پروفایل
                                        </a>
                                    @else
                                        <button class="action-btn btn-profile"
                                            onclick="showComingSoonModal('پروفایل معلم')">
                                            <i class="fas fa-user-circle"></i>
                                            پروفایل
                                        </button>
                                    @endif

                                    {{-- پیام به معلم --}}
                                    @if ($hasMessageCreate)
                                        <a href="{{ route('student.messages.create', ['teacher' => $t->id]) }}"
                                            class="action-btn btn-message">
                                            <i class="fas fa-comment-dots"></i>
                                            پیام
                                        </a>
                                    @else
                                        <button class="action-btn btn-message"
                                            onclick="showComingSoonModal('پیام به معلم')">
                                            <i class="fas fa-comment-dots"></i>
                                            پیام
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- ================= EMPTY STATE ================= --}}
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <h2 class="empty-title">هنوز معلمی نداری!</h2>

                <p class="empty-text">
                    برای دیدن معلمان باید در کلاسی عضو بشی.
                    معلمان راهنمایان اصلی تو در مسیر یادگیری هستند و
                    همیشه برای کمک به تو آماده‌اند.
                </p>

                <div class="empty-actions">
                    <a href="{{ $classroomsRoute }}" class="btn-primary-custom">
                        <i class="fas fa-search"></i>
                        جستجوی کلاس
                    </a>

                    @if (\Route::has('student.exams.index'))
                        <a href="{{ route('student.exams.index') }}" class="btn-outline-custom">
                            <i class="fas fa-play-circle"></i>
                            آزمون مستقل
                        </a>
                    @endif
                </div>
            </div>
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
                const teacherCards = document.querySelectorAll('.teacher-card');
                teacherCards.forEach(card => {
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

                // دکمه‌های پیام
                const messageButtons = document.querySelectorAll('.btn-message:not([disabled])');
                messageButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        if (this.tagName === 'BUTTON') return;

                        // افکت کلیک
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate([30, 50, 30]);
                        }
                    });
                });

                // دکمه‌های پروفایل
                const profileButtons = document.querySelectorAll('.btn-profile:not([disabled])');
                profileButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        if (this.tagName === 'BUTTON') return;

                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                });

                // چیپ‌ها
                const chips = document.querySelectorAll('.teacher-chip');
                chips.forEach(chip => {
                    chip.addEventListener('click', function() {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });
                });

                // دکمه جستجوی کلاس
                const searchClassBtn = document.querySelector('a[href*="classrooms"]');
                if (searchClassBtn) {
                    searchClassBtn.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                }
            });

            // تابع نمایش مودال "به زودی"
            function showComingSoonModal(feature) {
                const modal = document.createElement('div');
                modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 350px;
        width: 85%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

                modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
            <i class="fas fa-tools"></i>
        </div>
        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${feature}</h3>
        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
            این قابلیت در حال توسعه است و به زودی در دسترس قرار خواهد گرفت.
        </p>
        <button onclick="this.parentElement.remove(); if (this.parentElement.nextElementSibling) this.parentElement.nextElementSibling.remove();"
                style="width:100%; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
            باشه، منتظر می‌مونم!
        </button>
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

                // لرزش موبایل
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }

                // حذف خودکار بعد از 5 ثانیه
                setTimeout(() => {
                    if (document.body.contains(modal)) {
                        modal.remove();
                        overlay.remove();
                    }
                }, 5000);
            }

            // اضافه کردن استایل‌های انیمیشن
            const style = document.createElement('style');
            style.textContent = `
    @keyframes scaleIn {
        from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
        to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection
