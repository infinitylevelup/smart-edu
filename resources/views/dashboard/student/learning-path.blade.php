@extends('layouts.app')
@section('title', 'مسیر یادگیری - SmartEdu')

@push('styles')
    <style>
        /* تنظیمات پایه و تم رنگ جدید */
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
            --radius-sm: 8px;
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

        .lp-page {
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

        @keyframes bounce {

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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
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

        /* هدر مسیر یادگیری */
        .lp-hero {
            background: var(--gradient-1);
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 25px 20px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 25px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            animation: slideInRight 0.5s ease-out;
        }

        .lp-hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            border-radius: 0 20px 0 0;
        }

        .lp-hero::after {
            content: '🚀';
            position: absolute;
            bottom: -20px;
            left: -20px;
            font-size: 120px;
            opacity: 0.1;
            transform: rotate(-15deg);
            animation: float 6s ease-in-out infinite;
        }

        .lp-hero h4 {
            font-weight: 800;
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .lp-hero .sub {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 5px;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        /* کارت‌های نرم */
        .card-soft {
            border-radius: var(--radius-xl);
            background: var(--light);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: var(--shadow-md);
            padding: 20px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 20px;
            animation: fadeIn 0.6s ease-out;
        }

        .card-soft:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        /* حلقه پیشرفت */
        .ring {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin: 0 auto 20px;
            background: conic-gradient(var(--primary) calc(var(--p)*1%), var(--light-gray) 0);
            position: relative;
            box-shadow: var(--shadow-md);
            animation: pulse 3s infinite;
        }

        .ring::before {
            content: "";
            position: absolute;
            inset: 15px;
            background: var(--light);
            border-radius: 50%;
            box-shadow: inset 0 0 0 3px var(--light-gray);
        }

        .ring .inner {
            position: relative;
            text-align: center;
            z-index: 2;
        }

        .ring .big {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--dark);
            line-height: 1;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .ring .small {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--gray);
            margin-top: 8px;
        }

        /* چیپ‌های آماری */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            border: 2px solid transparent;
            background: var(--light);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .chip:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .chip.primary {
            background: var(--primary-light);
            color: var(--primary);
            border-color: rgba(123, 104, 238, 0.2);
        }

        .chip.secondary {
            background: var(--secondary-light);
            color: var(--secondary);
            border-color: rgba(255, 107, 157, 0.2);
        }

        .chip.accent {
            background: var(--accent-light);
            color: var(--accent);
            border-color: rgba(0, 212, 170, 0.2);
        }

        .chip.dark {
            background: var(--dark);
            color: var(--light);
            border-color: rgba(45, 48, 71, 0.3);
        }

        .chip.gold {
            background: linear-gradient(135deg, #FFD166, #FFB347);
            color: #5C4033;
            border-color: rgba(255, 209, 102, 0.3);
        }

        /* میل‌استون‌ها */
        .milestone {
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            border: 2px solid #eef2ff;
            background: var(--light);
            transition: all 0.3s ease;
            margin-bottom: 12px;
            animation: slideInRight 0.6s ease-out;
            animation-fill-mode: both;
        }

        .milestone:nth-child(1) {
            animation-delay: 0.1s;
        }

        .milestone:nth-child(2) {
            animation-delay: 0.2s;
        }

        .milestone:nth-child(3) {
            animation-delay: 0.3s;
        }

        .milestone:nth-child(4) {
            animation-delay: 0.4s;
        }

        .milestone:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .milestone .icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 1.8rem;
            font-weight: 900;
            flex-shrink: 0;
            background: var(--primary-light);
            color: var(--primary);
            border: 3px solid rgba(123, 104, 238, 0.2);
            transition: all 0.3s;
        }

        .milestone.done .icon {
            background: var(--accent-light);
            color: var(--accent);
            border-color: rgba(0, 212, 170, 0.3);
            animation: bounce 2s infinite;
        }

        .milestone.locked .icon {
            background: var(--light-gray);
            color: var(--gray);
            border-color: #e2e8f0;
        }

        .milestone .title {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--dark);
        }

        .milestone .sub {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 600;
            margin-top: 4px;
        }

        .milestone .badge {
            font-size: 0.8rem;
            font-weight: 800;
            border-radius: 50px;
            padding: 6px 12px;
            white-space: nowrap;
        }

        /* لیست تمرکز */
        .focus-item {
            border: 2px dashed var(--primary-light);
            border-radius: var(--radius-lg);
            padding: 12px 15px;
            background: var(--light-gray);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--dark);
            transition: all 0.3s;
            margin-bottom: 10px;
        }

        .focus-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: translateX(-5px);
        }

        .focus-item i {
            font-size: 1.2rem;
            color: var(--primary);
        }

        /* کارت آزمون بعدی */
        .next-box {
            border-radius: var(--radius-xl);
            padding: 20px;
            border: 2px dashed var(--primary);
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), rgba(255, 107, 157, 0.05));
            position: relative;
            overflow: hidden;
            animation: pulse 2s infinite;
        }

        .next-box::before {
            content: '⚡';
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 40px;
            opacity: 0.1;
            animation: float 4s ease-in-out infinite;
        }

        .next-btn {
            border-radius: var(--radius-lg);
            font-weight: 800;
            padding: 15px 20px;
            background: var(--gradient-1);
            border: none;
            color: #fff;
            box-shadow: 0 12px 28px rgba(123, 104, 238, 0.3);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .next-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 34px rgba(123, 104, 238, 0.4);
        }

        .next-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .next-btn:hover::before {
            right: 100%;
        }

        /* کارت‌های پیشنهادی */
        .suggest-card {
            border-radius: var(--radius-xl);
            background: var(--light);
            border: 2px solid rgba(0, 0, 0, 0.05);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            isolation: isolate;
            height: 100%;
            padding: 20px;
            animation: fadeIn 0.7s ease-out;
            animation-fill-mode: both;
        }

        .suggest-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .suggest-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .suggest-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .suggest-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-1);
            opacity: 0.9;
        }

        .suggest-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .suggest-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            font-size: 1.8rem;
            font-weight: 900;
            flex-shrink: 0;
            background: var(--primary-light);
            color: var(--primary);
            border: 3px solid rgba(123, 104, 238, 0.2);
            box-shadow: inset 0 0 0 3px rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
        }

        .suggest-card:hover .suggest-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient-1);
            color: white;
        }

        .suggest-card.amber .suggest-icon {
            background: rgba(255, 209, 102, 0.2);
            color: #D4A017;
            border-color: rgba(255, 209, 102, 0.3);
        }

        .suggest-card.green .suggest-icon {
            background: var(--accent-light);
            color: var(--accent);
            border-color: rgba(0, 212, 170, 0.3);
        }

        .suggest-title {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .suggest-desc {
            font-size: 0.9rem;
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .recommended-badge {
            position: absolute;
            top: 15px;
            right: -45px;
            transform: rotate(35deg);
            background: var(--gradient-1);
            color: #fff;
            font-weight: 800;
            font-size: 0.8rem;
            padding: 5px 40px;
            box-shadow: 0 8px 16px rgba(123, 104, 238, 0.35);
            z-index: 2;
        }

        .mini-progress {
            height: 10px;
            border-radius: 50px;
            background: var(--light-gray);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin: 15px 0;
        }

        .mini-progress>div {
            height: 100%;
            width: var(--w, 0%);
            background: var(--gradient-1);
            transition: width 0.6s ease;
            border-radius: 50px;
        }

        /* دکمه شروع فوری */
        .btn-start-now {
            border-radius: var(--radius-lg);
            font-weight: 800;
            padding: 15px;
            border: none;
            color: #fff;
            position: relative;
            background: var(--gradient-1);
            box-shadow: 0 12px 28px rgba(123, 104, 238, 0.3);
            transition: all 0.3s;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-start-now:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 36px rgba(123, 104, 238, 0.4);
        }

        .btn-start-now::after {
            content: "";
            position: absolute;
            top: -40%;
            right: -60%;
            width: 55%;
            height: 160%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.7), transparent);
            transform: rotate(18deg);
            animation: btnShine 2.6s infinite ease-in-out;
        }

        @keyframes btnShine {
            0% {
                right: -60%;
                opacity: 0
            }

            30% {
                opacity: .9
            }

            60% {
                right: 120%;
                opacity: 0
            }

            100% {
                right: 120%;
                opacity: 0
            }
        }

        .pulse-soft {
            animation: pulseSoft 1.9s infinite ease-in-out;
        }

        @keyframes pulseSoft {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }
        }

        /* انیمیشن‌های دکمه */
        .btn-start-now.clicked .burst::before {
            animation: burst1 .6s ease-out forwards;
        }

        .btn-start-now.clicked .burst::after {
            animation: burst2 .7s ease-out forwards;
        }

        @keyframes burst1 {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1
            }

            70% {
                transform: translate(-180%, -140%) scale(1.4);
                opacity: .8
            }

            100% {
                transform: translate(-220%, -180%) scale(0);
                opacity: 0
            }
        }

        @keyframes burst2 {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 1
            }

            70% {
                transform: translate(140%, -160%) scale(1.6);
                opacity: .8
            }

            100% {
                transform: translate(180%, -220%) scale(0);
                opacity: 0
            }
        }

        /* دکمه‌های عمومی */
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
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
            color: white;
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
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
        }

        /* هایلایت متن */
        .highlight {
            position: relative;
            display: inline-block;
        }

        .highlight::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, rgba(123, 104, 238, 0.2), rgba(255, 107, 157, 0.2));
            z-index: -1;
            border-radius: 4px;
        }

        /* بهینه‌سازی‌های موبایل */
        @media (max-width: 768px) {
            .lp-page {
                padding: 15px 10px 90px;
            }

            .lp-hero {
                padding: 20px 15px;
            }

            .lp-hero h4 {
                font-size: 1.3rem;
            }

            .ring {
                width: 150px;
                height: 150px;
            }

            .ring .big {
                font-size: 2rem;
            }

            .milestone {
                padding: 15px;
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .milestone .icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .suggest-card {
                padding: 15px;
            }

            .btn-start-now,
            .next-btn {
                padding: 12px 15px;
                font-size: 0.9rem;
            }
        }

        /* اسکرول نرم */
        html {
            scroll-behavior: smooth;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-start-now,
        .next-btn,
        .btn-primary-custom,
        .btn-outline-custom {
            min-height: 44px;
        }
    </style>
@endpush

@section('content')
    @php
        $overallPercent = (int) ($overallPercent ?? 0);
        $currentLevel = $currentLevel ?? 1;
        $levelProgress = $levelProgress ?? $overallPercent;
        $focusTopics = $focusTopics ?? ['درک مطلب', 'حل مسئله', 'سرعت پاسخ‌گویی', 'مدیریت زمان'];

        $recommendedLevel =
            $recommendedLevel ??
            ($overallPercent >= 85 ? 'olympiad' : ($overallPercent >= 60 ? 'konkur' : 'taghviyati'));

        if ($overallPercent >= 85) {
            $headline = 'تو آماده‌ی سطح بالاتری هستی! 🏆';
            $subline = 'از اینجا به بعد، چالش‌ها جذاب‌تر می‌شن. بریم برای مرحله‌ی سخت‌تر.';
            $nextText = 'آزمون المپیادی / سطح سخت';
            $nextIcon = 'fas fa-trophy';
            $nextColor = 'gold';
        } elseif ($overallPercent >= 60) {
            $headline = 'خیلی خوب در مسیر رشد هستی! ⚡';
            $subline = 'فقط چند تمرین دیگه تا رکورد جدید فاصله داری.';
            $nextText = 'آزمون مشابه برای رکورد';
            $nextIcon = 'fas fa-bullseye';
            $nextColor = 'accent';
        } else {
            $headline = 'شروع خوبی بوده، ادامه بده! 🚀';
            $subline = 'با چند آزمون کوتاه تقویتی، جهش سریع می‌بینی.';
            $nextText = 'آزمون تقویتی کوتاه';
            $nextIcon = 'fas fa-bolt';
            $nextColor = 'primary';
        }

        $milestones = [
            ['title' => 'شروع مسیر', 'sub' => 'اولین قدم همیشه مهمه', 'min' => 0, 'icon' => 'fas fa-flag'],
            ['title' => 'ثبات تمرین', 'sub' => '۳ آزمون پشت‌سرهم', 'min' => 35, 'icon' => 'fas fa-calendar-check'],
            ['title' => 'رکورد شخصی', 'sub' => 'بهبود حداقل ۱۰٪', 'min' => 60, 'icon' => 'fas fa-chart-line'],
            ['title' => 'سطح قهرمانی', 'sub' => 'آماده‌ی آزمون‌های سخت', 'min' => 85, 'icon' => 'fas fa-crown'],
        ];

        $levelText = fn($lv) => match ($lv) {
            'taghviyati' => 'تقویتی',
            'konkur' => 'کنکوری',
            'olympiad' => 'المپیادی',
            default => $lv ?? 'نامشخص',
        };

        $diffText = fn($df) => match ($df) {
            'easy' => 'آسان',
            'hard' => 'سخت',
            'medium' => 'متوسط',
            default => $df ?? 'متوسط',
        };

        $suggestedExams = isset($suggestedExams) ? collect($suggestedExams) : collect();

        // روت‌های امن
        $examsIndexRoute = route('student.exams.index');
        $profileRoute = \Route::has('student.profile') ? route('student.profile') : '#';
    @endphp

    <div class="lp-page">
        {{-- ================= HERO ================= --}}
        <div class="lp-hero">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4>
                        <i class="fas fa-map-marked-alt"></i>
                        مسیر یادگیری شخصی
                    </h4>
                    <div class="sub">
                        اینجا نقشه‌ی پیشرفت توئه؛ هر آزمون یه قدمه.
                        مسیرت رو ادامه بده تا مرحله‌های جدید باز بشن ✨
                    </div>
                </div>

                <a href="{{ $examsIndexRoute }}" class="btn"
                    style="background: white; color: var(--primary); font-weight: 700; border-radius: 12px; padding: 10px 20px;">
                    <i class="fas fa-arrow-left"></i> همه آزمون‌ها
                </a>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="chip dark"><i class="fas fa-shield-alt"></i> مسیر امن و مرحله‌ای</span>
                <span class="chip primary"><i class="fas fa-chart-line"></i> پیشرفت قابل اندازه‌گیری</span>
                <span class="chip accent"><i class="fas fa-trophy"></i> پاداش برای رشد</span>
                <span class="chip secondary"><i class="fas fa-bolt"></i> چالش برای هیجان</span>
            </div>
        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-4">
                <div class="card-soft text-center h-100">
                    <div class="ring" style="--p: {{ $overallPercent }};">
                        <div class="inner">
                            <div class="big">{{ round($overallPercent) }}%</div>
                            <div class="small">پیشرفت کلی</div>
                        </div>
                    </div>

                    <div class="fw-bold fs-5 mt-2 mb-3">{{ $headline }}</div>
                    <div class="text-muted mb-3" style="line-height: 1.8; font-size: 0.9rem;">
                        {{ $subline }}
                    </div>

                    <div class="mt-3">
                        <span class="chip primary">
                            <i class="fas fa-user-graduate"></i>
                            سطح فعلی: {{ $currentLevel }}
                        </span>
                        <span class="chip {{ $nextColor }} ms-2">
                            <i class="fas fa-arrow-up"></i>
                            {{ $levelText($recommendedLevel) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card-soft h-100">
                    <div class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                        <i class="fas fa-bullseye text-primary"></i>
                        تمرکز همین هفته
                    </div>

                    <div class="row g-2">
                        @foreach ($focusTopics as $t)
                            <div class="col-12 col-md-6">
                                <div class="focus-item">
                                    <i class="fas fa-dot-circle text-primary"></i>
                                    {{ $t }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 next-box">
                        <div class="fw-bold d-flex align-items-center gap-2 mb-2" style="font-size: 1.1rem;">
                            <i class="{{ $nextIcon }} text-primary"></i>
                            پیشنهاد مرحله بعدی
                        </div>
                        <div class="text-muted mb-3" style="line-height: 1.7; font-size: 0.9rem;">
                            {{ $nextText }} — چون الان دقیقاً بهترین زمان برای ادامه‌ی یادگیریه.
                        </div>
                        <a href="{{ $examsIndexRoute }}" class="next-btn">
                            شروع آزمون پیشنهادی
                            <i class="fas fa-play ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MILESTONES ================= --}}
        <div class="card-soft mb-4">
            <div class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                <i class="fas fa-mountain text-primary"></i>
                مرحله‌های مسیر
            </div>
            <div class="text-muted mb-4" style="line-height: 1.7; font-size: 0.9rem;">
                این‌ها نقاطی هستن که وقتی بهشون برسی، حس «ارتقاء» می‌گیری و سطح بعدی باز میشه.
            </div>

            <div>
                @foreach ($milestones as $m)
                    @php
                        $done = $overallPercent >= $m['min'];
                        $locked = !$done && $m['min'] > $overallPercent;
                    @endphp

                    <div class="milestone {{ $done ? 'done' : ($locked ? 'locked' : '') }}">
                        <div class="icon">
                            <i class="{{ $m['icon'] }}"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="title">{{ $m['title'] }}</div>
                            <div class="sub">{{ $m['sub'] }}</div>
                        </div>

                        @if ($done)
                            <span class="badge" style="background: var(--accent); color: white; font-weight: 700;">باز
                                شد</span>
                        @else
                            <span class="badge"
                                style="background: var(--light-gray); color: var(--dark); font-weight: 700;">نیاز به
                                {{ $m['min'] }}٪</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ================= SUGGESTED EXAMS (SAFE) ================= --}}
        @if ($suggestedExams->count())
            @php
                $labels = [
                    0 => [
                        'title' => 'چالش سخت‌تر 🔥',
                        'class' => 'amber',
                        'icon' => 'fas fa-fire',
                        'recommended' => true,
                    ],
                    1 => [
                        'title' => 'مشابه برای رکورد 🎯',
                        'class' => 'blue',
                        'icon' => 'fas fa-bullseye',
                        'recommended' => false,
                    ],
                    2 => [
                        'title' => 'کوتاه و تقویتی ⚡',
                        'class' => 'green',
                        'icon' => 'fas fa-bolt',
                        'recommended' => false,
                    ],
                ];
            @endphp

            <div class="card-soft mb-4">
                <div class="fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                    <i class="fas fa-magic text-primary"></i>
                    پیشنهاد آزمون‌های بعدی برای تو
                </div>

                <div class="text-muted mb-4" style="line-height: 1.7; font-size: 0.9rem;">
                    این آزمون‌ها بر اساس نتیجه‌هات انتخاب شده‌اند تا سریع‌تر رشد کنی.
                </div>

                <div class="row g-3">
                    @foreach ($suggestedExams as $i => $ex)
                        @php
                            $meta = $labels[$i] ?? [
                                'title' => 'آزمون پیشنهادی',
                                'class' => 'primary',
                                'icon' => 'fas fa-play',
                                'recommended' => false,
                            ];
                            $qCount = $ex->questions_count ?? (isset($ex->questions) ? $ex->questions->count() : 0);
                            $level = $levelText($ex->level ?? null);
                            $difficulty = $diffText($ex->difficulty ?? null);
                            $fakeProgress = min(100, max(5, $overallPercent + $i * 12));
                            $examShowRoute = route('student.exams.show', $ex->id);
                            $examTakeRoute = \Route::has('student.exams.take')
                                ? route('student.exams.take', $ex->id)
                                : $examsIndexRoute;
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="suggest-card {{ $meta['class'] }} h-100">
                                @if ($meta['recommended'])
                                    <div class="recommended-badge">پیشنهاد ویژه ⭐</div>
                                @endif

                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="chip {{ $meta['class'] }}">
                                        <i class="{{ $meta['icon'] }}"></i>
                                        {{ $meta['title'] }}
                                    </span>
                                    <span class="chip dark">{{ $level }}</span>
                                </div>

                                <div class="d-flex align-items-start gap-3">
                                    <div class="suggest-icon">
                                        <i class="{{ $meta['icon'] }}"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="suggest-title">{{ $ex->title ?? 'آزمون بدون عنوان' }}</div>
                                        <div class="suggest-desc">
                                            {{ \Illuminate\Support\Str::limit($ex->description ?? 'بدون توضیح', 80) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-bold" style="color: var(--dark);">پیشرفت پیشنهادی</span>
                                        <span class="small fw-bold"
                                            style="color: var(--primary);">{{ $fakeProgress }}%</span>
                                    </div>
                                    <div class="mini-progress" style="--w: {{ $fakeProgress }}%;">
                                        <div></div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <span class="chip primary">
                                        <i class="fas fa-question-circle"></i>
                                        {{ $qCount }} سوال
                                    </span>
                                    <span class="chip">
                                        <i class="fas fa-clock"></i>
                                        {{ $ex->duration ?? 0 }} دقیقه
                                    </span>
                                    <span class="chip secondary">
                                        <i class="fas fa-gauge-high"></i>
                                        {{ $difficulty }}
                                    </span>
                                    @if (isset($ex->subject))
                                        <span class="chip accent">
                                            <i class="fas fa-book"></i>
                                            {{ $ex->subject->title ?? ($ex->subject->name ?? 'درس') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4 d-grid gap-2">
                                    <a href="{{ $examShowRoute }}" class="btn-outline-custom text-center">
                                        مشاهده جزئیات
                                        <i class="fas fa-eye ms-1"></i>
                                    </a>
                                    <a href="{{ $examTakeRoute }}" class="btn-start-now pulse-soft">
                                        شروع فوری
                                        <i class="fas fa-play ms-1"></i>
                                        <span class="burst"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card-soft mb-4 text-center" style="padding: 40px 20px;">
                <div class="mb-3" style="font-size: 3rem; color: var(--gray);">📚</div>
                <div class="fw-bold mb-2" style="color: var(--dark);">فعلاً آزمون جدیدی برای پیشنهاد نداریم</div>
                <div class="text-muted">یکم دیگه تمرین کن تا پیشنهادهای بعدی باز بشن 😉</div>
                <a href="{{ $examsIndexRoute }}" class="btn-primary-custom mt-3">
                    برو به آزمون‌ها
                    <i class="fas fa-arrow-left ms-1"></i>
                </a>
            </div>
        @endif

        {{-- ================= FINAL MOTIVATION ================= --}}
        <div class="card-soft text-center">
            <div class="mb-3" style="font-size: 3rem;">💪</div>
            <div class="fw-bold fs-4 mb-2">فقط با «ادامه دادن» قوی می‌شی</div>
            <div class="text-muted mb-4" style="line-height: 1.8; max-width: 500px; margin: 0 auto;">
                مغزت بعد از هر آزمون، سریع‌تر و دقیق‌تر می‌شه.
                همین الان یک آزمون دیگه بده تا موج یادگیری کامل بشه.
            </div>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ $examsIndexRoute }}" class="btn-primary-custom">
                    آزمون جدید بده
                    <i class="fas fa-play ms-1"></i>
                </a>
                <a href="{{ $profileRoute }}" class="btn-outline-custom">
                    پروفایل من
                    <i class="fas fa-user ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // انیمیشن‌های تعاملی
                const startButtons = document.querySelectorAll('.btn-start-now');

                startButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        // افکت کلیک
                        this.classList.add('clicked');
                        setTimeout(() => {
                            this.classList.remove('clicked');
                        }, 700);

                        // لرزش موبایل (اگر پشتیبانی شود)
                        if (navigator.vibrate) {
                            navigator.vibrate(50);
                        }
                    });
                });

                // دکمه‌های چیپ
                const chips = document.querySelectorAll('.chip');
                chips.forEach(chip => {
                    chip.addEventListener('click', function() {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                });

                // میل‌استون‌ها
                const milestones = document.querySelectorAll('.milestone');
                milestones.forEach(stone => {
                    stone.addEventListener('click', function() {
                        const title = this.querySelector('.title').textContent;
                        const sub = this.querySelector('.sub').textContent;

                        // افکت کلیک
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 200);

                        // نمایش مودال ساده
                        if (!this.classList.contains('locked')) {
                            showQuickModal('مرحله', `${title}\n\n${sub}`, 'primary');
                        } else {
                            showQuickModal('قفل شده',
                                `این مرحله هنوز باز نشده\n\nبرای باز شدن نیاز به پیشرفت بیشتر داری`,
                                'secondary');
                        }
                    });
                });

                // تابع نمایش مودال سریع
                function showQuickModal(title, message, type = 'primary') {
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
            border: 3px solid ${type === 'primary' ? '#7B68EE' : type === 'secondary' ? '#FF6B9D' : '#00D4AA'};
        `;

                    modal.innerHTML = `
            <div style="font-size: 2.5rem; margin-bottom: 15px; color: ${type === 'primary' ? '#7B68EE' : type === 'secondary' ? '#FF6B9D' : '#00D4AA'}">
                ${type === 'primary' ? '🎯' : type === 'secondary' ? '🔒' : '✨'}
            </div>
            <h3 style="margin-bottom: 12px; color: var(--dark); font-size: 1.2rem; font-weight: 700;">${title}</h3>
            <p style="color: var(--gray); margin-bottom: 25px; font-size: 0.95rem; line-height: 1.5; white-space: pre-line;">${message}</p>
            <button onclick="this.parentElement.remove(); if (this.parentElement.nextElementSibling) this.parentElement.nextElementSibling.remove();" style="width:100%; padding: 12px; border: none; background: ${type === 'primary' ? '#7B68EE' : type === 'secondary' ? '#FF6B9D' : '#00D4AA'}; color: white; border-radius: 12px; font-weight: 600; font-size: 0.9rem;">باشه!</button>
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

                    setTimeout(() => {
                        if (document.body.contains(modal)) {
                            modal.remove();
                            overlay.remove();
                        }
                    }, 4000);
                }

                // افزودن استایل انیمیشن‌های اضافی
                const style = document.createElement('style');
                style.textContent = `
        @keyframes scaleIn {
            from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
            to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }
    `;
                document.head.appendChild(style);
            });
        </script>
    @endpush
@endsection
