@extends('layouts.app')
@section('title', 'مسیر یادگیری')

@push('styles')
    <style>
        :root {
            --edu-blue: #2563eb;
            --edu-blue-2: #0ea5e9;
            --edu-blue-soft: #eff6ff;

            --edu-green: #16a34a;
            --edu-green-2: #22c55e;
            --edu-green-soft: #ecfdf3;

            --edu-amber: #f59e0b;
            --edu-amber-2: #fbbf24;
            --edu-amber-soft: #fffbeb;

            --edu-red: #dc2626;
            --edu-red-soft: #fff1f2;

            --edu-gray: #0f172a;
            --edu-muted: #64748b;
            --edu-bg: #f8fafc;

            --radius-xl: 1.25rem;
            --radius-lg: 1rem;
        }

        .lp-page {
            animation: fadeIn .6s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* ===== header hero ===== */
        .lp-hero {
            background: linear-gradient(135deg, var(--edu-blue) 0%, var(--edu-blue-2) 55%, var(--edu-green-2) 100%);
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 1.1rem 1.2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(37, 99, 235, .22);
            max-width: 1200px;
            margin-inline: auto;
        }

        .lp-hero::after {
            content: "";
            position: absolute;
            inset: -40% -20% auto auto;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255, 255, 255, .35), transparent 70%);
            transform: rotate(14deg);
            opacity: .9;
            pointer-events: none;
        }

        .lp-hero h4 {
            font-weight: 900;
            margin: 0;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .lp-hero .sub {
            font-size: .9rem;
            color: rgba(255, 255, 255, .95);
            margin-top: .25rem;
            line-height: 1.8;
        }

        /* ===== cards ===== */
        .card-soft {
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            padding: 1rem 1.1rem;
        }

        /* ===== progress ring ===== */
        .ring {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin-inline: auto;
            background: conic-gradient(var(--edu-green) calc(var(--p)*1%), #e2e8f0 0);
            position: relative;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .12);
        }

        .ring::before {
            content: "";
            position: absolute;
            inset: 12px;
            background: #fff;
            border-radius: 50%;
            box-shadow: inset 0 0 0 2px #f1f5f9;
        }

        .ring .inner {
            position: relative;
            text-align: center;
        }

        .ring .big {
            font-size: 1.9rem;
            font-weight: 900;
            color: var(--edu-gray);
            line-height: 1;
        }

        .ring .small {
            font-size: .8rem;
            font-weight: 800;
            color: var(--edu-muted);
            margin-top: .25rem;
        }

        /* ===== stat chips ===== */
        .chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .7rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: .8rem;
            border: 1px solid transparent;
            background: #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, .06);
            white-space: nowrap;
        }

        .chip.blue {
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            border-color: #bfdbfe;
        }

        .chip.green {
            background: var(--edu-green-soft);
            color: var(--edu-green);
            border-color: #bbf7d0;
        }

        .chip.amber {
            background: var(--edu-amber-soft);
            color: #92400e;
            border-color: #fde68a;
        }

        .chip.dark {
            background: #0f172a;
            color: #fff;
        }

        /* ===== level milestone ===== */
        .milestone {
            display: flex;
            align-items: center;
            gap: .9rem;
            border-radius: 1rem;
            padding: .9rem 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            transition: .2s ease;
        }

        .milestone:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(37, 99, 235, .10);
        }

        .milestone .icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.7rem;
            font-weight: 900;
            flex-shrink: 0;
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            border: 1px solid #bfdbfe;
        }

        .milestone.done .icon {
            background: var(--edu-green-soft);
            color: var(--edu-green);
            border-color: #bbf7d0;
        }

        .milestone.locked .icon {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: #e2e8f0;
        }

        .milestone .title {
            font-weight: 900;
            font-size: 1rem;
        }

        .milestone .sub {
            font-size: .85rem;
            color: var(--edu-muted);
            font-weight: 700;
            margin-top: .2rem;
        }

        .milestone .badge {
            font-size: .75rem;
            font-weight: 900;
            border-radius: 999px;
            padding: .25rem .6rem;
        }

        /* ===== focus list ===== */
        .focus-item {
            border: 1px dashed #cbd5e1;
            border-radius: 1rem;
            padding: .75rem .85rem;
            background: #f8fafc;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 800;
            color: #334155;
        }

        .focus-item i {
            font-size: 1.1rem;
        }

        /* ===== next exam card ===== */
        .next-box {
            border-radius: 1.25rem;
            padding: 1rem 1.1rem;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
        }

        .next-btn {
            border-radius: 1rem;
            font-weight: 900;
            padding: .85rem 1rem;
            background: linear-gradient(135deg, var(--edu-blue), var(--edu-blue-2));
            border: none;
            color: #fff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, .28);
            transition: .2s ease;
        }

        .next-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .38);
        }

        .pulse {
            animation: pulse 1.8s infinite ease-in-out;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.03)
            }
        }

        /* ===== Suggested Exams PRO ===== */
        .suggest-card {
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
            position: relative;
            overflow: hidden;
            transition: .25s ease;
            isolation: isolate;
        }

        .suggest-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--edu-blue), var(--edu-blue-2), var(--edu-green-2), var(--edu-amber-2));
            opacity: .9;
        }

        .suggest-card::after {
            content: "";
            position: absolute;
            inset: -60% -60%;
            background: radial-gradient(circle, rgba(255, 255, 255, .9), transparent 55%);
            transform: translateX(-55%) translateY(-35%) rotate(18deg);
            opacity: 0;
            transition: .35s ease;
            z-index: 0;
        }

        .suggest-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 44px rgba(37, 99, 235, .14);
            border-color: #bfdbfe;
        }

        .suggest-card:hover::after {
            opacity: .85;
            transform: translateX(25%) translateY(10%) rotate(18deg);
        }

        .suggest-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            font-weight: 900;
            flex-shrink: 0;
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            border: 1px solid #bfdbfe;
            box-shadow: inset 0 0 0 2px #eff6ff;
        }

        .suggest-card.amber .suggest-icon {
            background: var(--edu-amber-soft);
            color: #92400e;
            border-color: #fde68a;
        }

        .suggest-card.green .suggest-icon {
            background: var(--edu-green-soft);
            color: var(--edu-green);
            border-color: #bbf7d0;
        }

        .suggest-title {
            font-weight: 900;
            font-size: 1rem;
            color: var(--edu-gray);
        }

        .suggest-desc {
            font-size: .85rem;
            color: var(--edu-muted);
            line-height: 1.9;
            min-height: 52px;
        }

        .recommended-badge {
            position: absolute;
            top: 10px;
            right: -42px;
            transform: rotate(35deg);
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-weight: 900;
            font-size: .78rem;
            padding: .25rem .9rem;
            box-shadow: 0 8px 16px rgba(34, 197, 94, .35);
            z-index: 2;
        }

        .mini-progress {
            height: 8px;
            border-radius: 999px;
            background: #eef2ff;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .mini-progress>div {
            height: 100%;
            width: var(--w, 0%);
            background: linear-gradient(90deg, var(--edu-blue), var(--edu-green-2));
            transition: .4s ease;
        }

        /* Start NOW button with countdown */
        .btn-start-now {
            border-radius: 1rem;
            font-weight: 900;
            padding: .85rem 1rem;
            border: none;
            color: #fff;
            position: relative;
            background: linear-gradient(135deg, var(--edu-blue), var(--edu-blue-2));
            box-shadow: 0 12px 28px rgba(37, 99, 235, .28);
            transition: .2s ease;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .btn-start-now:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, .38);
        }

        .btn-start-now::after {
            content: "";
            position: absolute;
            top: -40%;
            right: -60%;
            width: 55%;
            height: 160%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .7), transparent);
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
                transform: scale(1)
            }

            50% {
                transform: scale(1.03)
            }
        }

        .btn-start-now .burst {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            border-radius: inherit;
        }

        .btn-start-now .burst::before,
        .btn-start-now .burst::after {
            content: "";
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .9);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
        }

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

        .btn-start-now.counting {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15), 0 12px 28px rgba(15, 23, 42, .25);
        }

        .btn-start-now .count-num {
            display: inline-grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            font-weight: 900;
            font-size: .9rem;
            animation: popNum .35s ease both;
        }

        @keyframes popNum {
            from {
                transform: scale(.7);
                opacity: .2
            }

            to {
                transform: scale(1);
                opacity: 1
            }
        }

        .suggest-card.amber:hover {
            box-shadow: 0 22px 50px rgba(245, 158, 11, .22);
            border-color: #fde68a;
        }

        .suggest-card.blue:hover {
            box-shadow: 0 22px 50px rgba(37, 99, 235, .22);
            border-color: #bfdbfe;
        }

        .suggest-card.green:hover {
            box-shadow: 0 22px 50px rgba(34, 197, 94, .22);
            border-color: #bbf7d0;
        }
    </style>
@endpush

@section('content')
    @php
        $overallPercent = $overallPercent ?? 0;
        $currentLevel = $currentLevel ?? 1;
        $levelProgress = $levelProgress ?? $overallPercent;
        $focusTopics = $focusTopics ?? ['درک مطلب', 'حل مسئله', 'سرعت پاسخ‌گویی'];
        $recommendedLevel =
            $recommendedLevel ??
            ($overallPercent >= 85 ? 'olympiad' : ($overallPercent >= 60 ? 'konkur' : 'taghviyati'));

        if ($overallPercent >= 85) {
            $headline = 'تو آماده‌ی سطح بالاتری هستی! 🏆';
            $subline = 'از اینجا به بعد، چالش‌ها جذاب‌تر میشن. بریم برای مرحله‌ی سخت‌تر.';
            $nextText = 'آزمون المپیادی / سخت‌تر';
            $nextIcon = 'bi-award-fill';
        } elseif ($overallPercent >= 60) {
            $headline = 'خیلی خوب در مسیر رشد هستی! ⚡';
            $subline = 'فقط چند تمرین دیگه تا رکورد جدید فاصله داری.';
            $nextText = 'آزمون مشابه برای رکورد';
            $nextIcon = 'bi-bullseye';
        } else {
            $headline = 'شروع خوبی بوده، ادامه بده! 🚀';
            $subline = 'با چند آزمون کوتاه تقویتی، جهش سریع می‌بینی.';
            $nextText = 'آزمون تقویتی کوتاه';
            $nextIcon = 'bi-lightning-fill';
        }

        $milestones = [
            ['title' => 'شروع مسیر', 'sub' => 'اولین قدم همیشه مهمه', 'min' => 0],
            ['title' => 'ثبات تمرین', 'sub' => '۳ آزمون پشت‌سرهم', 'min' => 35],
            ['title' => 'رکورد شخصی', 'sub' => 'بهبود حداقل ۱۰٪', 'min' => 60],
            ['title' => 'سطح قهرمانی', 'sub' => 'آماده‌ی آزمون‌های سخت', 'min' => 85],
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

        // ✅ مقاوم در برابر array/collection
        $suggestedExams = isset($suggestedExams) ? collect($suggestedExams) : collect();
    @endphp

    <div class="lp-page container py-3 py-md-4">

        {{-- ================= HERO ================= --}}
        <div class="lp-hero mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h4>
                        <i class="bi bi-map-fill"></i>
                        مسیر یادگیری شخصی
                    </h4>
                    <div class="sub">
                        اینجا نقشه‌ی پیشرفت توئه؛ هر آزمون یه قدمه.
                        مسیرت رو ادامه بده تا مرحله‌های جدید باز بشن ✨
                    </div>
                </div>

                <a href="{{ route('student.exams.index') }}" class="btn btn-light btn-sm fw-bold">
                    <i class="bi bi-arrow-right"></i> آزمون‌ها
                </a>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="chip dark"><i class="bi bi-shield-check"></i> مسیر امن و مرحله‌ای</span>
                <span class="chip blue"><i class="bi bi-graph-up"></i> پیشرفت قابل اندازه‌گیری</span>
                <span class="chip green"><i class="bi bi-trophy"></i> پاداش برای رشد</span>
                <span class="chip amber"><i class="bi bi-lightning"></i> چالش برای هیجان</span>
            </div>
        </div>

        {{-- ================= SUMMARY ================= --}}
        <div class="row g-3 mb-3" style="max-width:1200px;margin-inline:auto;">
            <div class="col-12 col-lg-4">
                <div class="card-soft text-center h-100">
                    <div class="ring" style="--p: {{ $overallPercent }};">
                        <div class="inner">
                            <div class="big">{{ round($overallPercent) }}%</div>
                            <div class="small">پیشرفت کلی</div>
                        </div>
                    </div>

                    <div class="fw-bold fs-5 mt-3">{{ $headline }}</div>
                    <div class="text-muted small mt-1" style="line-height:1.9">
                        {{ $subline }}
                    </div>

                    <div class="mt-3">
                        <span class="chip blue">
                            <i class="bi bi-person-badge"></i>
                            سطح فعلی: {{ $currentLevel }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card-soft h-100">
                    <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-flag-fill text-primary"></i>
                        تمرکز همین هفته
                    </div>

                    <div class="row g-2">
                        @foreach ($focusTopics as $t)
                            <div class="col-12 col-md-6">
                                <div class="focus-item">
                                    <i class="bi bi-dot"></i>
                                    {{ $t }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 next-box pulse">
                        <div class="fw-bold d-flex align-items-center gap-2 mb-1">
                            <i class="bi {{ $nextIcon }} text-primary"></i>
                            پیشنهاد مرحله بعدی
                        </div>
                        <div class="text-muted small mb-2">
                            {{ $nextText }} — چون الان دقیقاً بهترین زمان برای ادامه‌ی یادگیریه.
                        </div>
                        <a href="{{ route('student.exams.index', ['level' => $recommendedLevel]) }}"
                            class="btn next-btn w-100">
                            شروع آزمون پیشنهادی
                            <i class="bi bi-play-fill ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MILESTONES ================= --}}
        <div class="card-soft mb-3" style="max-width:1200px;margin-inline:auto;">
            <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                <i class="bi bi-diagram-3 text-primary"></i>
                مرحله‌های مسیر
            </div>
            <div class="text-muted small mb-3">
                این‌ها نقاطی هستن که وقتی بهشون برسی، حس «ارتقاء» می‌گیری و سطح بعدی باز میشه.
            </div>

            <div class="vstack gap-2">
                @foreach ($milestones as $m)
                    @php
                        $done = $overallPercent >= $m['min'];
                        $locked = !$done && $m['min'] > $overallPercent;
                    @endphp

                    <div class="milestone {{ $done ? 'done' : ($locked ? 'locked' : '') }}">
                        <div class="icon">
                            @if ($done)
                                <i class="bi bi-check2-circle"></i>
                            @elseif($locked)
                                <i class="bi bi-lock-fill"></i>
                            @else
                                <i class="bi bi-hourglass-split"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <div class="title">{{ $m['title'] }}</div>
                            <div class="sub">{{ $m['sub'] }}</div>
                        </div>

                        @if ($done)
                            <span class="badge bg-success">باز شد</span>
                        @else
                            <span class="badge bg-light text-dark">نیاز به {{ $m['min'] }}٪</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ================= SUGGESTED EXAMS (REAL FROM DB) ================= --}}
        @if ($suggestedExams->count())
            @php
                $labels = [
                    0 => ['title' => 'چالش سخت‌تر 🔥', 'class' => 'amber', 'icon' => 'bi-fire', 'recommended' => true],
                    1 => [
                        'title' => 'مشابه برای رکورد 🎯',
                        'class' => 'blue',
                        'icon' => 'bi-bullseye',
                        'recommended' => false,
                    ],
                    2 => [
                        'title' => 'کوتاه و تقویتی ⚡',
                        'class' => 'green',
                        'icon' => 'bi-lightning-fill',
                        'recommended' => false,
                    ],
                ];
            @endphp

            <div class="card-soft mb-3" style="max-width:1200px;margin-inline:auto;">
                <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-stars text-primary"></i>
                    پیشنهاد آزمون‌های بعدی برای تو
                </div>

                <div class="text-muted small mb-3">
                    این ۳ آزمون رو هوشمند بر اساس نتیجه‌هات انتخاب کردیم تا سریع‌تر رشد کنی.
                </div>

                <div class="row g-3">
                    @foreach ($suggestedExams as $i => $ex)
                        @php
                            $meta = $labels[$i] ?? [
                                'title' => 'آزمون پیشنهادی',
                                'class' => 'blue',
                                'icon' => 'bi-play',
                                'recommended' => false,
                            ];
                            $qCount = $ex->questions_count ?? ($ex->questions->count() ?? 0);
                            $level = $levelText($ex->level ?? null);
                            $difficulty = $diffText($ex->difficulty ?? null);
                            $fakeProgress = min(100, max(5, $overallPercent + $i * 12));
                        @endphp

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="suggest-card {{ $meta['class'] }} h-100 p-3 position-relative">

                                @if ($meta['recommended'])
                                    <div class="recommended-badge">پیشنهاد ویژه ⭐</div>
                                @endif

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="chip {{ $meta['class'] }}">
                                        <i class="bi {{ $meta['icon'] }}"></i>
                                        {{ $meta['title'] }}
                                    </span>

                                    <span class="chip dark">{{ $level }}</span>
                                </div>

                                <div class="d-flex align-items-start gap-2 mt-1">
                                    <div class="suggest-icon">
                                        <i class="bi {{ $meta['icon'] }}"></i>
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="suggest-title">{{ $ex->title }}</div>
                                        <div class="suggest-desc mt-1">
                                            {{ \Illuminate\Support\Str::limit($ex->description ?? 'بدون توضیح', 80) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div class="small text-muted fw-bold mb-1">
                                        پیشرفت پیشنهادی این مرحله
                                        <span class="text-primary">{{ $fakeProgress }}%</span>
                                    </div>
                                    <div class="mini-progress" style="--w: {{ $fakeProgress }}%;">
                                        <div></div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <span class="chip blue">
                                        <i class="bi bi-question-circle"></i>
                                        {{ $qCount }} سوال
                                    </span>

                                    <span class="chip">
                                        <i class="bi bi-clock-history"></i>
                                        {{ $ex->duration ?? 0 }} دقیقه
                                    </span>

                                    <span class="chip amber">
                                        <i class="bi bi-speedometer2"></i>
                                        {{ $difficulty }}
                                    </span>

                                    @if ($ex->subject)
                                        <span class="chip green">
                                            <i class="bi bi-book"></i>
                                            {{ $ex->subject->title ?? $ex->subject->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 d-grid gap-2">
                                    <a href="{{ route('student.exams.show', $ex->id) }}"
                                        class="btn btn-outline-secondary fw-bold">
                                        مشاهده جزئیات
                                        <i class="bi bi-eye ms-1"></i>
                                    </a>

                                    <form method="POST" action="{{ route('student.exams.start', $ex->id) }}"
                                        class="start-now-form">
                                        @csrf
                                        <button type="submit" class="btn-start-now w-100 pulse-soft" data-count="3">
                                            <span class="btn-label">شروع فوری</span>
                                            <i class="bi bi-play-fill ms-1"></i>
                                            <span class="burst"></span>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card-soft mb-3 text-center text-muted fw-bold" style="max-width:1200px;margin-inline:auto;">
                فعلاً آزمون جدیدی برای پیشنهاد نداریم — یکم دیگه تمرین کن تا پیشنهادهای بعدی باز بشن 😉
            </div>
        @endif

        {{-- ================= FINAL MOTIVATION ================= --}}
        <div class="card-soft text-center" style="max-width:1200px;margin-inline:auto;">
            <div class="fw-bold fs-5 mb-1">
                فقط با «ادامه دادن» قوی میشی 💪
            </div>
            <div class="text-muted small" style="line-height:1.9">
                مغزت بعد از هر آزمون، سریع‌تر و دقیق‌تر میشه.
                همین الان یک آزمون دیگه بده تا موج یادگیری کامل بشه.
            </div>

            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <a href="{{ route('student.exams.index') }}" class="btn btn-primary fw-bold px-4">
                    آزمون جدید بده
                    <i class="bi bi-play-fill ms-1"></i>
                </a>
                <a href="{{ route('student.profile') ?? '#' }}" class="btn btn-outline-secondary fw-bold px-4">
                    پروفایل من
                    <i class="bi bi-person ms-1"></i>
                </a>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            document.querySelectorAll('.start-now-form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const btn = form.querySelector('.btn-start-now');
                    if (!btn) return;

                    e.preventDefault(); // جلوگیری از ارسال فوری

                    // جلوگیری از دوبار کلیک
                    if (btn.dataset.locked === '1') return;
                    btn.dataset.locked = '1';

                    // Burst
                    btn.classList.add('clicked');
                    setTimeout(() => btn.classList.remove('clicked'), 800);

                    const labelEl = btn.querySelector('.btn-label');
                    const iconEl = btn.querySelector('i');
                    let count = parseInt(btn.dataset.count || '3', 10);

                    btn.classList.add('counting');
                    if (iconEl) iconEl.style.display = 'none';

                    const tick = () => {
                        if (labelEl) {
                            labelEl.innerHTML = `شروع در <span class="count-num">${count}</span>`;
                        }

                        if (count <= 1) {
                            if (labelEl) labelEl.textContent = 'در حال شروع...';
                            form.submit(); // ارسال نهایی
                            return;
                        }

                        count--;
                        setTimeout(tick, 900);
                    };

                    tick();
                });
            });
        })();
    </script>
@endpush
