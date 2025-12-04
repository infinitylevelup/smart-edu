@extends('layouts.app')
@section('title', 'کلاس‌های من - SmartEdu')

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

        .classrooms-page {
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
        .header-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
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
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
            box-shadow: var(--shadow-md);
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

        /* نوار جستجو */
        .search-bar {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 15px 20px;
            box-shadow: var(--shadow-md);
            margin-bottom: 25px;
            border: 2px solid rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.6s ease-out;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-icon {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .search-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 1rem;
            color: var(--dark);
            padding: 8px 0;
        }

        .search-input::placeholder {
            color: var(--gray);
        }

        .search-input:focus {
            box-shadow: none;
        }

        /* کارت کلاس */
        .class-card {
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

        .class-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .class-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .class-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .class-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .class-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .class-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .class-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .class-card::before {
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
        .class-header {
            background: var(--gradient-1);
            color: white;
            padding: 25px 20px;
            position: relative;
            overflow: hidden;
        }

        .class-header::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent 70%);
            border-radius: 50%;
        }

        .class-chip {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 2;
            animation: pulse 2s infinite;
        }

        .class-title {
            font-weight: 800;
            font-size: 1.3rem;
            margin: 0 0 10px 0;
            color: white;
            position: relative;
            z-index: 2;
        }

        .class-meta {
            font-size: 0.9rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        /* بدنه کارت */
        .class-body {
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .teacher-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            padding: 12px;
            background: var(--light-gray);
            border-radius: var(--radius-md);
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .teacher-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .teacher-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .teacher-label {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .class-description {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
            padding: 12px;
            background: var(--light-gray);
            border-radius: var(--radius-md);
            border: 2px dashed rgba(0, 0, 0, 0.05);
            min-height: 60px;
        }

        .class-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-badge {
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

        .stat-badge:hover {
            transform: translateY(-3px);
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .stat-badge i {
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-active {
            background: var(--accent-light);
            color: var(--accent);
            border: 2px solid rgba(0, 212, 170, 0.2);
        }

        .status-inactive {
            background: var(--light-gray);
            color: var(--gray);
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .status-published {
            background: var(--primary-light);
            color: var(--primary);
            border: 2px solid rgba(123, 104, 238, 0.2);
        }

        .status-draft {
            background: var(--secondary-light);
            color: var(--secondary);
            border: 2px solid rgba(255, 107, 157, 0.2);
        }

        .class-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-btn {
            flex: 1;
            padding: 14px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .action-btn:active {
            transform: scale(0.97);
        }

        .btn-enter {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-enter:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.4);
        }

        .btn-enter::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-enter:hover::before {
            right: 100%;
        }

        .btn-exams {
            background: var(--light-gray);
            color: var(--dark);
            border-color: rgba(0, 0, 0, 0.05);
        }

        .btn-exams:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.15);
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

        /* بهینه‌سازی موبایل */
        @media (max-width: 768px) {
            .classrooms-page {
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

            .header-buttons {
                width: 100%;
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-outline-custom {
                width: 100%;
                justify-content: center;
            }

            .class-body {
                padding: 15px;
            }

            .class-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
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
        }

        @media (min-width: 992px) {
            .class-actions {
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
        $classrooms = $classrooms ?? collect();

        $joinFormRoute = route('student.classrooms.join.form');
        $dashboardRoute = route('student.index');
        $examsIndexRoute = route('student.exams.index');
    @endphp

    <div class="classrooms-page">
        {{-- ================= HEADER ================= --}}
        <div class="page-header">
            <div class="page-title-section">
                <h1>
                    <i class="fas fa-users-class"></i>
                    کلاس‌های من
                </h1>
                <p class="page-subtitle">
                    اینجا تمام کلاس‌هایی که عضو هستی دیده می‌شود؛
                    از همینجا می‌تونی وارد کلاس بشی یا آزمون‌هاش رو ببینی. 🎓
                </p>
            </div>

            <div class="header-buttons">
                <a href="{{ $joinFormRoute }}" class="btn-primary-custom">
                    <i class="fas fa-plus-circle"></i>
                    عضویت با کد کلاس
                </a>
                <a href="{{ route('student.classrooms.index') }}" class="btn-outline-custom">
                    <i class="fas fa-sync-alt"></i>
                    بروزرسانی
                </a>
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

        {{-- ================= SEARCH BAR ================= --}}
        @if ($classrooms->count())
            <div class="search-bar">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input id="classSearch" type="text" class="search-input"
                        placeholder="جستجو در کلاس‌ها (عنوان، معلم، کد کلاس...)">
                </div>
            </div>
        @endif

        {{-- ================= EMPTY STATE ================= --}}
        @if ($classrooms->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>

                <h2 class="empty-title">هنوز عضو هیچ کلاسی نیستی!</h2>

                <p class="empty-text">
                    برای شروع یادگیری، کد ورود کلاس را از معلمت بگیر
                    و همینجا وارد کن تا به کلاس متصل بشی و آزمون‌ها برات فعال بشه.
                    کلاس‌ها دنیای جدیدی از یادگیری رو برات باز می‌کنن ✨
                </p>

                <div class="empty-actions">
                    <a href="{{ $joinFormRoute }}" class="btn-primary-custom" style="padding: 15px 30px;">
                        <i class="fas fa-key"></i>
                        عضویت با کد کلاس
                    </a>
                    <a href="{{ $dashboardRoute }}" class="btn-outline-custom" style="padding: 15px 30px;">
                        <i class="fas fa-home"></i>
                        برگشت به داشبورد
                    </a>
                </div>
            </div>
        @else
            {{-- ================= CLASSROOMS GRID ================= --}}
            <div class="row g-4" id="classGrid">
                @foreach ($classrooms as $classroom)
                    @php
                        $teacher = $classroom->teacher ?? null;
                        $title = $classroom->title ?? ($classroom->name ?? 'کلاس بدون عنوان');
                        $joinCode = $classroom->join_code ?? null;
                        $examsCount =
                            $classroom->exams_count ?? (isset($classroom->exams) ? $classroom->exams->count() : 0);
                        $studentsCount = $classroom->students_count ?? null;
                        $teacherName = $teacher->display_name ?? ($teacher->name ?? 'نامشخص');
                        $teacherInitial = mb_substr(trim($teacherName), 0, 1) ?: 'م';

                        $searchText = \Illuminate\Support\Str::lower(
                            trim(
                                $title .
                                    ' ' .
                                    $teacherName .
                                    ' ' .
                                    ($joinCode ?? '') .
                                    ' ' .
                                    ($classroom->subject ?? '') .
                                    ' ' .
                                    ($classroom->grade ?? ''),
                            ),
                        );
                    @endphp

                    <div class="col-md-6 col-xl-4 class-item" data-search="{{ $searchText }}">
                        <div class="class-card">
                            {{-- هدر کارت --}}
                            <div class="class-header">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h3 class="class-title">{{ $title }}</h3>
                                        <div class="class-meta">
                                            @if (!empty($classroom->subject) || !empty($classroom->grade))
                                                <span>
                                                    <i class="fas fa-book"></i>
                                                    {{ $classroom->subject ?? 'عمومی' }}
                                                </span>
                                            @endif

                                            @if (!empty($classroom->grade))
                                                <span>
                                                    <i class="fas fa-graduation-cap"></i>
                                                    پایه {{ $classroom->grade }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($joinCode)
                                        <span class="class-chip">
                                            <i class="fas fa-key"></i>
                                            {{ $joinCode }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- بدنه کارت --}}
                            <div class="class-body">
                                {{-- اطلاعات معلم --}}
                                <div class="teacher-info">
                                    <div class="teacher-avatar">
                                        {{ $teacherInitial }}
                                    </div>
                                    <div>
                                        <div class="teacher-label">معلم</div>
                                        <div class="teacher-name">{{ $teacherName }}</div>
                                    </div>
                                </div>

                                {{-- توضیحات --}}
                                <div class="class-description">
                                    @if (!empty($classroom->description))
                                        {{ \Illuminate\Support\Str::limit($classroom->description, 95) }}
                                    @else
                                        توضیحی برای این کلاس ثبت نشده؛ می‌تونی با ورود به کلاس جزئیات بیشتر رو ببینی.
                                    @endif
                                </div>

                                {{-- آمار و وضعیت --}}
                                <div class="class-stats">
                                    <span class="stat-badge">
                                        <i class="fas fa-clipboard-list"></i>
                                        {{ $examsCount }} آزمون
                                    </span>

                                    @if (!is_null($studentsCount))
                                        <span class="stat-badge">
                                            <i class="fas fa-user-friends"></i>
                                            {{ $studentsCount }} عضو
                                        </span>
                                    @endif

                                    <span
                                        class="status-badge {{ $classroom->is_active ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas fa-circle"></i>
                                        {{ $classroom->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>

                                    @if (isset($classroom->is_published))
                                        <span
                                            class="status-badge {{ $classroom->is_published ? 'status-published' : 'status-draft' }}">
                                            <i class="fas fa-{{ $classroom->is_published ? 'globe' : 'edit' }}"></i>
                                            {{ $classroom->is_published ? 'منتشر شده' : 'پیش‌نویس' }}
                                        </span>
                                    @endif
                                </div>

                                {{-- دکمه‌های اقدام --}}
                                <div class="class-actions">
                                    <a href="{{ route('student.classrooms.show', $classroom) }}"
                                        class="action-btn btn-enter">
                                        <i class="fas fa-door-open"></i>
                                        ورود به کلاس
                                    </a>
                                    <a href="{{ $examsIndexRoute }}" class="action-btn btn-exams">
                                        <i class="fas fa-clipboard-check"></i>
                                        آزمون‌ها
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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

                // جستجوی کلاینت ساید
                const searchInput = document.getElementById('classSearch');
                const classItems = document.querySelectorAll('.class-item');

                if (searchInput && classItems.length > 0) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase().trim();

                        classItems.forEach(item => {
                            const searchText = item.getAttribute('data-search') || '';
                            if (searchText.includes(query)) {
                                item.style.display = 'block';
                                // انیمیشن ظهور
                                item.style.animation = 'fadeIn 0.3s ease';
                            } else {
                                item.style.display = 'none';
                            }
                        });

                        if (navigator.vibrate && query.length > 0) {
                            navigator.vibrate(20);
                        }
                    });

                    // پاک کردن جستجو با دکمه ESC
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            this.value = '';
                            this.dispatchEvent(new Event('input'));
                        }
                    });
                }

                // انیمیشن‌های کارت‌ها
                const classCards = document.querySelectorAll('.class-card');
                classCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });

                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('.action-btn')) {
                            this.style.transform = 'scale(0.98)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 150);

                            if (navigator.vibrate) {
                                navigator.vibrate(30);
                            }

                            // در حالت واقعی به صفحه کلاس هدایت می‌شود
                            // window.location.href = this.querySelector('.btn-enter').href;
                        }
                    });
                });

                // دکمه ورود به کلاس
                const enterButtons = document.querySelectorAll('.btn-enter');
                enterButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate([30, 50, 30]);
                        }
                    });
                });

                // دکمه آزمون‌ها
                const examButtons = document.querySelectorAll('.btn-exams');
                examButtons.forEach(btn => {
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

                // دکمه‌های عضویت و بروزرسانی
                document.querySelectorAll('.btn-primary-custom, .btn-outline-custom').forEach(btn => {
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

                // اضافه کردن استایل انیمیشن
                const style = document.createElement('style');
                style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
                document.head.appendChild(style);
            });
        </script>
    @endpush
@endsection
