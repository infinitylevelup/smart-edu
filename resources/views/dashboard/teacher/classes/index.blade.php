@extends('layouts.app')
@section('title', 'پنل معلم - کلاس‌های من')

@push('styles')
    <style>
        /* تم کامل SmartEdu */
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
            --dark-light: #3A3F6D;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --gradient-3: linear-gradient(135deg, #FFD166, #FF9A3D);
            --gradient-4: linear-gradient(135deg, #7209B7, #3A0CA3);
            --radius-xl: 24px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
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

        .classes-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 15px 80px;
            animation: fadeIn 0.6s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
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

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-15px) translateX(-10px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        /* ========== HERO HEADER ========== */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(123, 104, 238, 0.1) 0%,
                    rgba(255, 107, 157, 0.1) 50%,
                    rgba(0, 212, 170, 0.1) 100%);
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 8s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 10s ease-in-out infinite reverse;
        }

        .hero-content h1 {
            font-weight: 900;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-content h1::before {
            content: '';
            width: 8px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .hero-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 700px;
            margin: 0;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .btn-hero {
            padding: 16px 32px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1.05rem;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .btn-hero:active {
            transform: scale(0.97);
        }

        .btn-primary-grad {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 10px 25px rgba(123, 104, 238, 0.3);
        }

        .btn-primary-grad:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(123, 104, 238, 0.4);
        }

        .btn-primary-grad::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-primary-grad:hover::before {
            left: 100%;
        }

        .btn-outline-secondary-grad {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-outline-secondary-grad:hover {
            background: var(--light-gray);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.08);
            animation: slideInLeft 0.5s ease-out;
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .filter-header i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .filter-header h3 {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            margin: 0;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            align-items: end;
        }

        @media (max-width: 1200px) {
            .filter-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        .filter-group {
            margin-bottom: 0;
        }

        .filter-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .filter-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .filter-select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 18px center;
            background-size: 16px;
            padding-left: 45px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            height: 100%;
            align-items: end;
        }

        .btn-filter {
            flex: 1;
            padding: 14px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 52px;
        }

        .btn-filter:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn-filter-primary {
            background: var(--gradient-1);
            color: white;
            border: none;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.25);
        }

        .btn-filter-primary:hover {
            background: var(--gradient-1);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(123, 104, 238, 0.35);
        }

        .btn-filter-reset {
            background: transparent;
            color: var(--gray);
            border: 2px solid var(--gray);
        }

        .btn-filter-reset:hover {
            background: var(--light-gray);
            color: var(--dark);
        }

        /* ========== CLASSES GRID ========== */
        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
            animation: fadeIn 0.6s ease-out;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        @media (max-width: 768px) {
            .classes-grid {
                grid-template-columns: 1fr;
            }
        }

        .class-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 0;
            box-shadow: var(--shadow-lg);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .class-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .class-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .class-ribbon {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 900;
            border-radius: var(--radius-sm);
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ribbon-active {
            background: rgba(0, 212, 170, 0.15);
            color: #00D4AA;
        }

        .ribbon-archived {
            background: rgba(138, 141, 155, 0.15);
            color: var(--gray);
        }

        .class-header {
            padding: 25px 25px 20px;
            border-bottom: 2px solid var(--light-gray);
            position: relative;
            z-index: 2;
        }

        .class-title {
            font-weight: 900;
            font-size: 1.4rem;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.3;
        }

        .class-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .class-subject {
            color: var(--gray);
            font-size: 0.95rem;
            font-weight: 700;
            padding-right: 57px;
        }

        .class-body {
            padding: 20px 25px;
            flex: 1;
            position: relative;
            z-index: 2;
        }

        .class-description {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 25px;
            min-height: 60px;
        }

        .class-meta {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .meta-item {
            background: var(--light-gray);
            border-radius: var(--radius-md);
            padding: 15px;
            text-align: center;
            transition: all 0.3s;
        }

        .meta-item:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }

        .meta-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 5px;
            line-height: 1;
        }

        .meta-label {
            font-size: 0.85rem;
            color: var(--gray);
            font-weight: 700;
        }

        .class-code {
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), rgba(255, 107, 157, 0.05));
            border-radius: var(--radius-md);
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            border: 2px dashed rgba(123, 104, 238, 0.2);
        }

        .code-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .code-value {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--primary);
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .class-footer {
            padding: 20px 25px 25px;
            border-top: 2px solid var(--light-gray);
            position: relative;
            z-index: 2;
        }

        .class-actions {
            display: flex;
            gap: 12px;
        }

        .btn-class-action {
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
            min-height: 48px;
        }

        .btn-class-action:active {
            transform: scale(0.95);
        }

        .btn-exam {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.25);
        }

        .btn-exam:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(123, 104, 238, 0.35);
        }

        .btn-details {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-details:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 60px 40px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            border: 2px dashed rgba(123, 104, 238, 0.2);
            animation: fadeIn 0.6s ease-out;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(0, 212, 170, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2.8rem;
            color: var(--primary);
            animation: pulse 2s infinite;
        }

        .empty-title {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-description {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        /* ========== PAGINATION ========== */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            animation: fadeIn 0.6s ease-out;
        }

        .pagination-custom {
            display: flex;
            gap: 10px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .page-item {
            display: flex;
        }

        .page-link {
            padding: 12px 20px;
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 800;
            text-decoration: none;
            border: 2px solid var(--light-gray);
            transition: all 0.3s;
            min-width: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-link:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .page-item.active .page-link {
            background: var(--gradient-1);
            color: white;
            border-color: var(--primary);
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .classes-container {
                padding: 15px 10px 60px;
            }

            .hero-section {
                padding: 25px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .filter-section {
                padding: 20px;
            }

            .filter-grid {
                gap: 12px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .class-meta {
                grid-template-columns: 1fr;
            }

            .class-actions {
                flex-direction: column;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-title {
                font-size: 1.5rem;
            }

            .page-link {
                padding: 10px 15px;
                min-width: 40px;
            }
        }

        @media (max-width: 480px) {
            .class-card {
                margin-bottom: 20px;
            }

            .class-header,
            .class-body,
            .class-footer {
                padding: 20px;
            }

            .meta-value {
                font-size: 1.5rem;
            }

            .code-value {
                font-size: 1.3rem;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-hero,
        .btn-filter,
        .btn-class-action,
        .page-link {
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
    <div class="classes-container">
        {{-- ========== HERO SECTION ========== --}}
        <div class="hero-section">
            <div class="hero-content">
                <h1>
                    <span
                        style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        کلاس‌های من
                    </span>
                    👨‍🏫
                </h1>
                <p class="hero-subtitle">
                    مدیریت ساخت، ویرایش، اعضا و آزمون‌های هر کلاس.
                    کلاس‌ها را سازماندهی کرده و عملکرد دانش‌آموزان را دنبال کنید.
                </p>
            </div>

            <div class="hero-actions">
                @if (\Illuminate\Support\Facades\Route::has('teacher.classes.create'))
                    <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد کلاس جدید
                    </a>
                @endif

                <a href="{{ route('teacher.index') }}" class="btn-hero btn-outline-secondary-grad">
                    <i class="fas fa-home"></i>
                    بازگشت به داشبورد
                </a>
            </div>
        </div>

        {{-- ========== FILTER SECTION ========== --}}
        <div class="filter-section">
            <div class="filter-header">
                <i class="fas fa-filter"></i>
                <h3>فیلتر و جستجوی پیشرفته</h3>
            </div>

            <form method="GET" action="{{ route('teacher.classes.index') }}" class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="q" class="filter-input" placeholder="نام کلاس، پایه یا کد کلاس..."
                            value="{{ request('q') }}">
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">پایه</label>
                    @php $grade = request('grade','all'); @endphp
                    <select name="grade" class="filter-select">
                        <option value="all" {{ $grade === 'all' ? 'selected' : '' }}>همه پایه‌ها</option>
                        <option value="7" {{ $grade === '7' ? 'selected' : '' }}>هفتم</option>
                        <option value="8" {{ $grade === '8' ? 'selected' : '' }}>هشتم</option>
                        <option value="9" {{ $grade === '9' ? 'selected' : '' }}>نهم</option>
                        <option value="10" {{ $grade === '10' ? 'selected' : '' }}>دهم</option>
                        <option value="11" {{ $grade === '11' ? 'selected' : '' }}>یازدهم</option>
                        <option value="12" {{ $grade === '12' ? 'selected' : '' }}>دوازدهم</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">وضعیت</label>
                    @php $status=request('status','all'); @endphp
                    <select name="status" class="filter-select">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>همه</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>آرشیو</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">مرتب‌سازی</label>
                    @php $sort=request('sort','latest'); @endphp
                    <select name="sort" class="filter-select">
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>جدیدترین</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
                        <option value="students" {{ $sort === 'students' ? 'selected' : '' }}>بیشترین دانش‌آموز</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>نام A→Z</option>
                        <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>نام Z→A</option>
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="fas fa-sliders-h"></i>
                        اعمال فیلتر
                    </button>
                    <a href="{{ route('teacher.classes.index') }}" class="btn-filter btn-filter-reset">
                        <i class="fas fa-times"></i>
                        حذف فیلتر
                    </a>
                </div>
            </form>
        </div>

        {{-- ========== CLASSES LIST ========== --}}
        @php
            $classes = $classes ?? collect();
            $hasPaginator = method_exists($classes, 'total');
            $totalClasses = $hasPaginator ? $classes->total() : $classes->count();
        @endphp

        @if ($totalClasses == 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-people-group"></i>
                </div>
                <h3 class="empty-title">هنوز کلاسی نساختی!</h3>
                <p class="empty-description">
                    اولین کلاس خود را ایجاد کنید تا بتوانید دانش‌آموزان را اضافه کرده و آزمون‌های کلاسی برگزار کنید.
                    پس از فعال شدن مسیر ساخت کلاس، از دکمه زیر استفاده کنید.
                </p>
                @if (\Illuminate\Support\Facades\Route::has('teacher.classes.create'))
                    <a href="{{ route('teacher.classes.create') }}" class="btn-hero btn-primary-grad"
                        style="display: inline-flex;">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد اولین کلاس
                    </a>
                @endif
            </div>
        @else
            <div class="classes-grid">
                @foreach ($classes as $class)
                    @php
                        $studentsCount = $class->students_count ?? ($class->students->count() ?? 0);
                        $examsCount = $class->exams_count ?? 0;
                        $isActive = $class->is_active ?? true;
                        $gradeLabel = $class->grade ?? '—';
                        $code = $class->code ?? ($class->join_code ?? null);
                        $description = $class->description ?? null;
                    @endphp

                    <div class="class-card">
                        <div class="class-ribbon {{ $isActive ? 'ribbon-active' : 'ribbon-archived' }}">
                            <i class="fas {{ $isActive ? 'fa-check-circle' : 'fa-archive' }}"></i>
                            {{ $isActive ? 'فعال' : 'آرشیو' }}
                        </div>

                        <div class="class-header">
                            <div class="class-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $class->title ?? ($class->name ?? 'کلاس بدون نام') }}
                            </div>
                            <div class="class-subject">
                                {{ $class->subject ?? 'عمومی' }}
                            </div>
                        </div>

                        <div class="class-body">
                            @if ($description)
                                <div class="class-description">
                                    {{ \Illuminate\Support\Str::limit($description, 120) }}
                                </div>
                            @else
                                <div class="class-description" style="color: var(--light-gray);">
                                    <i class="fas fa-info-circle"></i>
                                    توضیحاتی برای این کلاس ثبت نشده است.
                                </div>
                            @endif

                            <div class="class-meta">
                                <div class="meta-item">
                                    <div class="meta-value">{{ $gradeLabel }}</div>
                                    <div class="meta-label">پایه تحصیلی</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-value">{{ $studentsCount }}</div>
                                    <div class="meta-label">دانش‌آموز</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-value">{{ $examsCount }}</div>
                                    <div class="meta-label">آزمون</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-value">
                                        <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
                                    </div>
                                    <div class="meta-label">مدیریت</div>
                                </div>
                            </div>

                            @if ($code)
                                <div class="class-code">
                                    <div class="code-label">کد ورود به کلاس:</div>
                                    <div class="code-value">{{ $code }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="class-footer">
                            <div class="class-actions">
                                <a href="{{ route('teacher.exams.create', ['class_id' => $class->id]) }}"
                                    class="btn-class-action btn-exam">
                                    <i class="fas fa-plus-circle"></i>
                                    آزمون جدید
                                </a>
                                <button class="btn-class-action btn-details"
                                    onclick="showClassDetails({{ $class->id }})">
                                    <i class="fas fa-eye"></i>
                                    جزئیات
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========== PAGINATION ========== --}}
            @if (method_exists($classes, 'hasPages') && $classes->hasPages())
                <div class="pagination-container">
                    <ul class="pagination-custom">
                        {{-- Previous Page Link --}}
                        @if ($classes->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($classes->getUrlRange(1, $classes->lastPage()) as $page => $url)
                            @if ($page == $classes->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($classes->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $classes->nextPageUrl() }}" rel="next">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-hero, .btn-filter, .btn-class-action, .page-link, .class-card');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // افکت hover برای کارت‌ها
            const classCards = document.querySelectorAll('.class-card');
            classCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(10);
                    }
                });
            });

            // انیمیشن ورود کارت‌ها
            const animateCards = () => {
                const cards = document.querySelectorAll('.class-card');
                cards.forEach((card, i) => {
                    card.style.animationDelay = `${i * 0.1}s`;
                    card.style.animation = 'fadeIn 0.5s ease-out forwards';
                    card.style.opacity = '0';
                });
            };

            // اجرای انیمیشن بعد از لود صفحه
            setTimeout(animateCards, 300);

            // اعتبارسنجی فرم فیلتر
            const filterForm = document.querySelector('.filter-grid');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال جستجو...';
                    submitBtn.disabled = true;

                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                });
            }

            // نمایش تعداد نتایج فیلتر شده
            const updateResultsCount = () => {
                const resultsCount = document.querySelectorAll('.class-card').length;
                const countElement = document.createElement('div');
                countElement.className = 'results-count';
                countElement.style.cssText = `
            background: var(--gradient-1);
            color: white;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 900;
            font-size: 0.9rem;
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 8px;
        `;
                countElement.innerHTML = `<i class="fas fa-layer-group"></i> ${resultsCount} کلاس یافت شد`;

                const existingCount = document.querySelector('.results-count');
                if (existingCount) existingCount.remove();

                if (resultsCount > 0) {
                    document.body.appendChild(countElement);

                    setTimeout(() => {
                        countElement.style.opacity = '0';
                        countElement.style.transform = 'translateY(20px)';
                        setTimeout(() => countElement.remove(), 300);
                    }, 3000);
                }
            };

            // اجرای شمارش نتایج
            setTimeout(updateResultsCount, 500);
        });

        // تابع نمایش جزئیات کلاس
        function showClassDetails(classId) {
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
        max-width: 400px;
        width: 90%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

            modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">جزئیات کلاس</h3>
        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
            صفحه جزئیات کلاس به زودی در دسترس خواهد بود.
            در این صفحه می‌توانید دانش‌آموزان، آزمون‌ها و گزارش‌های کلاس را مشاهده کنید.
        </p>
        <div style="display: flex; gap: 10px;">
            <button onclick="this.parentElement.parentElement.remove(); if (this.parentElement.parentElement.nextElementSibling) this.parentElement.parentElement.nextElementSibling.remove();"
                    style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
                باشه
            </button>
            <button onclick="window.location.href='{{ route('teacher.exams.create') }}?class_id=' + ${classId};"
                    style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
                ساخت آزمون
            </button>
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

            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            setTimeout(() => {
                if (document.body.contains(modal)) {
                    modal.remove();
                    overlay.remove();
                }
            }, 8000);
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
