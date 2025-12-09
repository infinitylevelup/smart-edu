@extends('layouts.app')

@section('title', 'پنل معلم - داشبورد')

@push('styles')
<style>
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

    .teacher-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 15px 80px;
        animation: fadeIn 0.6s ease both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { transform: translateX(-30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideInRight {
        from { transform: translateX(30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }

    @keyframes float {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-10px); }
    }

    @keyframes floaty {
        0%,100% { transform: translateY(0) translateX(0); }
        50%     { transform: translateY(-15px) translateX(-10px); }
    }

    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position: 200% center; }
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
        background: #fff;
    }

    .btn-hero:active {
        transform: scale(0.97);
    }

    .btn-primary-grad {
        background: var(--primary-gradient);
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

    .highlight {
        background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 900;
    }

    /* ========== KPI STATS ========== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
        animation: fadeIn 0.6s ease-out;
        animation-delay: 0.1s;
        animation-fill-mode: both;
    }

    .kpi-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 28px;
        box-shadow: var(--shadow-lg);
        border: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        animation: slideInRight 0.5s ease-out;
    }

    .kpi-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-light);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
        border-radius: 0 var(--radius-xl) 0 0;
    }

    .kpi-card:nth-child(2)::before {
        background: linear-gradient(135deg, rgba(0, 212, 170, 0.08), transparent);
    }

    .kpi-card:nth-child(3)::before {
        background: linear-gradient(135deg, rgba(255, 209, 102, 0.08), transparent);
    }

    .kpi-card:nth-child(4)::before {
        background: linear-gradient(135deg, rgba(255, 107, 157, 0.08), transparent);
    }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }

    .kpi-title {
        color: var(--gray);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
    }

    .kpi-card:hover .kpi-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .kpi-value {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--dark);
        margin-bottom: 10px;
        position: relative;
        z-index: 2;
        line-height: 1;
    }

    .kpi-trend {
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
        position: relative;
        z-index: 2;
    }

    .trend-up { color: #00D4AA; }
    .trend-down { color: #FF6B9D; }

    /* ========== MAIN DASHBOARD GRID ========== */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 35px;
    }

    @media (max-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ========== RECENT EXAMS CARD ========== */
    .recent-exams-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 30px;
        box-shadow: var(--shadow-lg);
        border: 2px solid rgba(0, 0, 0, 0.05);
        height: 100%;
        position: relative;
        overflow: hidden;
        animation: slideInLeft 0.6s ease-out;
    }

    .recent-exams-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
        border-radius: 0 var(--radius-xl) 0 0;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        position: relative;
        z-index: 2;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--light-gray);
    }

    .card-title {
        font-weight: 900;
        font-size: 1.4rem;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-title i {
        color: var(--primary);
        background: var(--primary-light);
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-all-link {
        color: var(--primary);
        font-weight: 800;
        font-size: 0.95rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }

    .view-all-link:hover {
        gap: 10px;
        color: var(--secondary);
    }

    .exams-list {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
        z-index: 2;
    }

    .exam-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 15px;
        border-radius: var(--radius-md);
        border-bottom: 2px dashed rgba(123, 104, 238, 0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }

    .exam-item:last-child {
        border-bottom: none;
    }

    .exam-item:hover {
        background: var(--primary-light);
        transform: translateX(8px);
        border-bottom-color: transparent;
    }

    .exam-info { flex: 1; }

    .exam-title {
        font-weight: 900;
        color: var(--dark);
        font-size: 1.05rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .exam-title i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .exam-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 0.9rem;
        color: var(--gray);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-item i {
        font-size: 0.95rem;
    }

    .exam-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 900;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .empty-state {
        text-align: center;
        padding: 50px 30px;
        position: relative;
        z-index: 2;
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--light-gray);
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .empty-text {
        color: var(--gray);
        font-size: 1.1rem;
        margin-bottom: 25px;
        line-height: 1.7;
    }

    .btn-create-first {
        padding: 14px 28px;
        background: var(--gradient-1);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 800;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
    }

    .btn-create-first:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
    }

    /* ========== QUICK ACTIONS CARD ========== */
    .quick-actions-card {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 30px;
        box-shadow: var(--shadow-lg);
        border: 2px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        animation: slideInRight 0.6s ease-out;
    }

    .quick-actions-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: var(--gradient-2);
    }

    .quick-grid {
        display: grid;
        gap: 15px;
        position: relative;
        z-index: 2;
    }

    .quick-action {
        background: var(--light-gray);
        border-radius: var(--radius-lg);
        padding: 22px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid transparent;
        color: inherit;
    }

    .quick-action:hover {
        background: var(--light);
        border-color: var(--primary-light);
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .quick-content {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }

    .quick-icon {
        width: 55px;
        height: 55px;
        border-radius: 16px;
        background: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .quick-action:hover .quick-icon {
        background: var(--gradient-1);
        color: white;
        transform: scale(1.1);
    }

    .quick-text h4 {
        font-weight: 900;
        color: var(--dark);
        font-size: 1rem;
        margin: 0 0 5px;
    }

    .quick-text p {
        color: var(--gray);
        font-size: 0.85rem;
        margin: 0;
        line-height: 1.5;
    }

    .quick-arrow {
        color: var(--gray);
        font-size: 1.1rem;
        transition: all 0.3s;
    }

    .quick-action:hover .quick-arrow {
        transform: translateX(-5px);
        color: var(--primary);
    }

    /* ========== SYSTEM STATUS CARD ========== */
    .system-status-card {
        background: linear-gradient(135deg, #2D3047, #1A1C30);
        color: white;
        border-radius: var(--radius-xl);
        padding: 30px;
        position: relative;
        overflow: hidden;
        animation: slideInRight 0.6s ease-out;
        animation-delay: 0.2s;
        animation-fill-mode: both;
    }

    .system-status-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 212, 170, 0.15), transparent 70%);
        border-radius: 50%;
        animation: floaty 12s ease-in-out infinite;
    }

    .system-status-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(123, 104, 238, 0.15), transparent 70%);
        border-radius: 50%;
        animation: floaty 10s ease-in-out infinite reverse;
    }

    .system-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        position: relative;
        z-index: 2;
    }

    .system-header i {
        color: var(--accent);
        background: rgba(0, 212, 170, 0.15);
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .system-header h3 {
        font-weight: 900;
        font-size: 1.3rem;
        margin: 0;
    }

    .system-description {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 25px;
        position: relative;
        z-index: 2;
    }

    .system-list {
        display: grid;
        gap: 18px;
        position: relative;
        z-index: 2;
    }

    .system-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .system-item i {
        color: var(--gold);
        font-size: 1rem;
        margin-top: 3px;
        flex-shrink: 0;
    }

    .system-item p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95rem;
        margin: 0;
        line-height: 1.6;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .teacher-dashboard { padding: 15px 10px 60px; }

        .hero-section { padding: 25px; }

        .hero-content h1 { font-size: 1.8rem; }

        .hero-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-hero {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .kpi-card { padding: 22px; }

        .kpi-value { font-size: 2.2rem; }

        .dashboard-grid { gap: 20px; }

        .exam-meta {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .exam-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .exam-badge { align-self: flex-start; }

        .quick-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .quick-action { padding: 18px; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }

        .kpi-value { font-size: 2rem; }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .view-all-link { align-self: flex-start; }
    }

    .btn-hero,
    .btn-create-first,
    .quick-action,
    .exam-item {
        min-height: 48px;
    }

    ::selection {
        background: rgba(123, 104, 238, 0.2);
        color: var(--dark);
    }
</style>
@endpush

@section('content')
<div class="teacher-dashboard">

    {{-- HERO --}}
    <div class="hero-section">
        <div class="hero-content">
            <h1>
                <span class="highlight">داشبورد معلم</span>
            </h1>
            <p class="hero-subtitle">
                خلاصه‌ای از آزمون‌ها، کلاس‌ها و فعالیت دانش‌آموزان شما.
                از آخرین آمارها و گزارش‌ها مطلع شوید.
            </p>
        </div>

        <div class="hero-actions">
            <a href="{{ route('teacher.exams.create') }}" class="btn-hero btn-primary-grad">
                <i class="fas fa-file-circle-plus"></i>
                ایجاد آزمون جدید
            </a>
            <a href="{{ route('teacher.exams.index') }}" class="btn-hero btn-outline-secondary-grad">
                <i class="fas fa-clipboard-list"></i>
                مدیریت آزمون‌ها
            </a>
        </div>
    </div>

    {{-- KPI --}}
    <div class="stats-grid">
        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-title">تعداد کل آزمون‌ها</div>
                <div class="kpi-icon" style="background: var(--gradient-1);">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $stats['total_exams'] ?? '0' }}</div>
            <div class="kpi-trend trend-up">
                <i class="fas fa-arrow-up"></i>
                نسبت به ماه گذشته
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-title">آزمون‌های فعال</div>
                <div class="kpi-icon" style="background: var(--gradient-2);">
                    <i class="fas fa-bolt"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $stats['active_exams'] ?? '0' }}</div>
            <div class="kpi-trend trend-up">
                <i class="fas fa-chart-line"></i>
                در حال اجرا
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-title">تعداد دانش‌آموزان</div>
                <div class="kpi-icon" style="background: var(--gradient-3);">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $stats['students_count'] ?? '0' }}</div>
            <div class="kpi-trend trend-up">
                <i class="fas fa-users"></i>
                @if (($stats['new_students'] ?? 0) > 0)
                    {{ $stats['new_students'] }} دانش‌آموز جدید
                @else
                    بدون دانش‌آموز جدید
                @endif
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-header">
                <div class="kpi-title">پاسخ بررسی‌نشده</div>
                <div class="kpi-icon" style="background: var(--gradient-4);">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $stats['pending_reviews'] ?? '0' }}</div>
            <div class="kpi-trend trend-down">
                <i class="fas fa-exclamation-circle"></i>
                نیاز به بررسی فوری
            </div>
        </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="dashboard-grid">

        {{-- RECENT EXAMS --}}
        <div class="recent-exams-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-clock-rotate-left"></i>
                    آزمون‌های اخیر شما
                </div>
                <a href="{{ route('teacher.exams.index') }}" class="view-all-link">
                    مشاهده همه
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            @if($recentExams->count())
                <ul class="exams-list">
                    @foreach($recentExams as $exam)
                        <li>
                            <a href="{{ route('teacher.exams.edit', $exam->id) }}" class="exam-item">
                                <div class="exam-info">
                                    <div class="exam-title">
                                        <i class="fas fa-file-pen"></i>
                                        {{ $exam->title }}
                                    </div>

                                    <div class="exam-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-layer-group"></i>
                                            سطح: {{ $exam->level ?? '—' }}
                                        </span>

                                        <span class="meta-item">
                                            <i class="fas fa-calendar-day"></i>
                                            تاریخ:
                                            {{ $exam->start_at ? $exam->start_at->format('Y/m/d H:i') : 'نامشخص' }}
                                        </span>

                                        <span class="meta-item">
                                            <i class="fas fa-question-circle"></i>
                                            سوالات: {{ $exam->questions_count ?? 0 }}
                                        </span>
                                    </div>
                                </div>

                                <div class="exam-badge">
                                    <i class="fas fa-user-check"></i>
                                    {{ $exam->attempts_count ?? 0 }} شرکت‌کننده
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 style="color: var(--dark); margin-bottom: 15px; font-weight: 900;">
                        هنوز آزمونی ثبت نکرده‌اید
                    </h3>
                    <p class="empty-text">
                        برای شروع کار و ایجاد اولین آزمون، روی دکمه زیر کلیک کنید.
                        می‌توانید آزمون‌های مختلفی با سطوح متفاوت ایجاد کنید.
                    </p>
                    <a href="{{ route('teacher.exams.create') }}" class="btn-create-first">
                        <i class="fas fa-plus-circle"></i>
                        ایجاد اولین آزمون
                    </a>
                </div>
            @endif
        </div>

        {{-- RIGHT SIDE: QUICK + STATUS --}}
        <div>
            <div class="quick-actions-card">
                <div class="card-header" style="border: none; padding: 0 0 20px 0;">
                    <div class="card-title">
                        <i class="fas fa-bolt"></i>
                        میانبرهای مهم
                    </div>
                </div>

                <div class="quick-grid">
                    <a href="{{ route('teacher.classes.index') }}" class="quick-action">
                        <div class="quick-content">
                            <div class="quick-icon"
                                 style="color: var(--primary); background: rgba(123, 104, 238, 0.1);">
                                <i class="fas fa-people-group"></i>
                            </div>
                            <div class="quick-text">
                                <h4>مدیریت کلاس‌ها</h4>
                                <p>ساخت، ویرایش و گروه‌بندی</p>
                            </div>
                        </div>
                        <div class="quick-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    <a href="{{ route('teacher.students.index') }}" class="quick-action">
                        <div class="quick-content">
                            <div class="quick-icon"
                                 style="color: #00D4AA; background: rgba(0, 212, 170, 0.1);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="quick-text">
                                <h4>لیست دانش‌آموزان</h4>
                                <p>حضور و عملکرد</p>
                            </div>
                        </div>
                        <div class="quick-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    <a href="{{ route('teacher.reports.index') }}" class="quick-action">
                        <div class="quick-content">
                            <div class="quick-icon"
                                 style="color: #4361EE; background: rgba(67, 97, 238, 0.1);">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="quick-text">
                                <h4>گزارش‌ها و تحلیل‌ها</h4>
                                <p>نمودار و پیشرفت</p>
                            </div>
                        </div>
                        <div class="quick-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>

                    <a href="{{ route('teacher.profile') }}" class="quick-action">
                        <div class="quick-content">
                            <div class="quick-icon"
                                 style="color: #FF6B9D; background: rgba(255, 107, 157, 0.1);">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="quick-text">
                                <h4>پروفایل من</h4>
                                <p>مدیریت حساب کاربری</p>
                            </div>
                        </div>
                        <div class="quick-arrow">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </a>
                </div>
            </div>

            <div class="system-status-card">
                <div class="system-header">
                    <i class="fas fa-shield-check"></i>
                    <h3>وضعیت کلی سیستم</h3>
                </div>
                <p class="system-description">
                    این بخش برای اعلان‌ها و یادآوری‌های مهم شماست.
                    آخرین وضعیت سیستم و توصیه‌های آموزشی را اینجا مشاهده کنید.
                </p>

                <div class="system-list">
                    <div class="system-item">
                        <i class="fas fa-bell"></i>
                        <p><strong>یادآوری:</strong> نتایج آزمون‌های تشریحی را بررسی کنید.</p>
                    </div>
                    <div class="system-item">
                        <i class="fas fa-lightbulb"></i>
                        <p><strong>پیشنهاد:</strong> برای هر کلاس حداقل یک آزمون تمرینی تنظیم کنید.</p>
                    </div>
                    <div class="system-item">
                        <i class="fas fa-chart-line"></i>
                        <p><strong>گزارش:</strong> میانگین نمرات کلاسی شما ۱۵٪ بهبود یافته است.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // انیمیشن تأخیری KPI
        document.querySelectorAll('.kpi-card').forEach((card, i) => {
            card.style.animationDelay = (i * 0.1) + 's';
        });

        // ویبره برای آیتم‌های کلیکی (در صورت پشتیبانی)
        if (navigator.vibrate) {
            const clickable = document.querySelectorAll(
                '.btn-hero, .quick-action, .exam-item, .btn-create-first'
            );
            clickable.forEach(el => {
                el.addEventListener('click', () => navigator.vibrate(25));
            });
        }

        // اگر آزمون بررسی‌نشده داریم، یک مودال یادآوری نرم
        const pending = parseInt('{{ $stats['pending_reviews'] ?? 0 }}', 10);
        if (pending > 0) {
            setTimeout(showReminderModal, 3000);
        }

        function showReminderModal() {
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
                    <i class="fas fa-bell"></i>
                </div>
                <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">
                    یادآوری مهم!
                </h3>
                <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
                    شما {{ $stats['pending_reviews'] ?? 0 }} آزمون بررسی‌نشده دارید.
                    توصیه می‌کنیم برای حفظ کیفیت آموزش، حداکثر ظرف ۲۴ ساعت بررسی شوند.
                </p>
                <div style="display: flex; gap: 10px;">
                    <button onclick="closeReminderModal()"
                            style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
                        باشه
                    </button>
                    <button onclick="window.location.href='{{ route('teacher.exams.index') }}';"
                            style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
                        مشاهده آزمون‌ها
                    </button>
                </div>
            `;
            document.body.appendChild(modal);

            const overlay = document.createElement('div');
            overlay.id = 'reminderOverlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                animation: fadeIn .3s ease;
            `;
            document.body.appendChild(overlay);

            window.closeReminderModal = function() {
                modal.remove();
                const ov = document.getElementById('reminderOverlay');
                if (ov) ov.remove();
            };
        }

        const style = document.createElement('style');
        style.textContent = `
            @keyframes scaleIn {
                from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
                to   { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to   { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
