@extends('layouts.app')
@section('title', 'جزئیات آزمون')

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

        .page-wrap {
            animation: pageFade .6s ease both;
        }

        @keyframes pageFade {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-soft {
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-xl);
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .exam-hero {
            background: linear-gradient(135deg, var(--edu-blue) 0%, var(--edu-blue-2) 55%, var(--edu-green-2) 100%);
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 1.15rem 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 14px 35px rgba(37, 99, 235, .2);
            max-width: 1100px;
            margin-inline: auto;
            isolation: isolate;
        }

        .exam-hero::after {
            content: "";
            position: absolute;
            inset: -35% -20% auto auto;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, .35), transparent 70%);
            transform: rotate(18deg);
            opacity: .85;
            pointer-events: none;
            z-index: -1;
        }

        .exam-hero::before {
            content: "";
            position: absolute;
            inset: auto auto -40% -25%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(255, 255, 255, .16), transparent 70%);
            z-index: -1;
        }

        .exam-hero h3 {
            font-weight: 900;
            font-size: 1.32rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .55rem;
        }

        .exam-hero .subtitle {
            font-size: .9rem;
            color: rgba(255, 255, 255, .95);
            margin-top: .35rem;
            line-height: 1.9;
        }

        .mission-badge {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-top: .6rem;
            font-size: .85rem;
            font-weight: 900;
            color: #ffffff;
            opacity: .95;
        }

        .mission-badge .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 0 0 6px rgba(255, 255, 255, .18);
            animation: ping 1.8s infinite ease-in-out;
        }

        @keyframes ping {
            0% {
                transform: scale(1);
                opacity: .9;
            }

            70% {
                transform: scale(1.8);
                opacity: 0;
            }

            100% {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .5rem .75rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: .82rem;
            border: 1px solid transparent;
            white-space: nowrap;
            background: #fff;
            color: var(--edu-gray);
            box-shadow: 0 6px 14px rgba(15, 23, 42, .06);
            transition: .2s ease;
        }

        .chip:hover {
            transform: translateY(-1px);
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

        .stat {
            border-radius: var(--radius-lg);
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: .95rem 1rem;
            display: flex;
            align-items: center;
            gap: .8rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
            transition: .2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, .12);
        }

        .stat .icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.25rem;
            font-weight: 900;
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            flex-shrink: 0;
        }

        .stat.green .icon {
            background: var(--edu-green-soft);
            color: var(--edu-green);
        }

        .stat.amber .icon {
            background: var(--edu-amber-soft);
            color: #92400e;
        }

        .stat .label {
            font-size: .8rem;
            color: var(--edu-muted);
            font-weight: 800;
        }

        .stat .value {
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--edu-gray);
            margin-top: .12rem;
        }

        .stat .subvalue {
            font-size: .78rem;
            color: var(--edu-muted);
            font-weight: 700;
            margin-top: .1rem;
        }

        .difficulty-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .8rem;
            font-weight: 900;
            border-radius: 999px;
            padding: .25rem .6rem;
            border: 1px solid transparent;
        }

        .difficulty-easy {
            background: #ecfdf3;
            color: #166534;
            border-color: #bbf7d0;
        }

        .difficulty-medium {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .difficulty-hard {
            background: #fff1f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .alert-final {
            border: 0;
            border-radius: var(--radius-xl);
            background: linear-gradient(90deg, #fff1f2, #ffe4e6);
            box-shadow: 0 12px 28px rgba(220, 38, 38, .22);
            position: relative;
            overflow: hidden;
            animation: softPulse 1.6s infinite ease-in-out;
            padding: 1rem 1.1rem;
            max-width: 1100px;
            margin-inline: auto;
        }

        .alert-final::before {
            content: "";
            position: absolute;
            top: -55%;
            right: -15%;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(255, 255, 255, .9), transparent 60%);
            animation: floatGlow 2.8s infinite ease-in-out;
        }

        @keyframes softPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }

        @keyframes floatGlow {

            0%,
            100% {
                transform: translate(0, 0);
                opacity: .5;
            }

            50% {
                transform: translate(-25px, 20px);
                opacity: .95;
            }
        }

        .btn-start {
            position: relative;
            border-radius: 1rem;
            font-weight: 900;
            padding: 1rem 1rem;
            border: none;
            background: linear-gradient(135deg, var(--edu-blue), var(--edu-blue-2));
            box-shadow: 0 12px 28px rgba(37, 99, 235, .28);
            transition: .2s ease;
            overflow: hidden;
            letter-spacing: .2px;
        }

        .btn-start::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .35), transparent);
            transform: translateX(-120%);
            animation: shine 2.8s infinite ease-in-out;
        }

        @keyframes shine {
            0% {
                transform: translateX(-120%);
            }

            55% {
                transform: translateX(120%);
            }

            100% {
                transform: translateX(120%);
            }
        }

        .btn-start:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .38);
        }

        .btn-result {
            border-radius: .95rem;
            font-weight: 900;
            padding: .9rem 1rem;
            border: none;
            background: linear-gradient(135deg, var(--edu-green), var(--edu-green-2));
            box-shadow: 0 12px 28px rgba(34, 197, 94, .28);
        }

        .hint {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #334155;
            border-radius: .9rem;
            padding: .8rem .9rem;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            line-height: 1.9;
        }

        .prep-bar {
            height: 8px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
            position: relative;
            margin-top: .7rem;
        }

        .prep-bar .fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #22c55e, #0ea5e9, #2563eb);
            animation: fillup 1.2s ease forwards;
        }

        @keyframes fillup {
            to {
                width: 100%;
            }
        }

        .challenge {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            padding: .9rem 1rem;
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
        }

        .challenge .ico {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: var(--edu-amber-soft);
            color: #92400e;
            font-size: 1.2rem;
            font-weight: 900;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    @php
        $qCount = $exam->questions_count ?? ($exam->questions->count() ?? 0);
        $duration = $exam->duration ?? 0;

        // ✅ difficulty mapping from level (easy/average/hard/tough)
        $diffKey = $exam->level ?? 'average';

        $diffText = match ($diffKey) {
            'easy' => 'آسان',
            'hard' => 'سخت',
            'tough' => 'خیلی سخت',
            default => 'متوسط', // average
        };

        $diffClass = match ($diffKey) {
            'easy' => 'difficulty-easy',
            'hard', 'tough' => 'difficulty-hard',
            default => 'difficulty-medium',
        };

        $motivationLine = match ($diffKey) {
            'easy' => 'گرم‌کن عالی برای شروع 💚',
            'hard' => 'چالش جدی، اما تو از پسش برمیای 🔥',
            'tough' => 'مرحله‌ی حرفه‌ای‌ها — بترکون 💥',
            default => 'سطح استاندارد برای رشد سریع 🚀',
        };

        // ✅ level chip data
        $levelChip = match ($diffKey) {
            'easy' => ['green', 'bi-lightning-fill', 'آسان'],
            'hard' => ['blue', 'bi-bullseye', 'سخت'],
            'tough' => ['amber', 'bi-award-fill', 'خیلی سخت'],
            default => ['blue', 'bi-speedometer2', 'متوسط'],
        };
    @endphp

    <div class="page-wrap container py-3 py-md-4">

        {{-- ================= HERO HEADER ================= --}}
        <div class="exam-hero mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h3>
                        <i class="bi bi-file-earmark-text"></i>
                        {{ $exam->title }}
                    </h3>

                    <div class="subtitle">
                        آماده‌ای یک قدم دیگه به سمت هدف‌هات نزدیک‌تر بشی؟ ✨
                        <br>
                        <span class="fw-bold">{{ $motivationLine }}</span>
                    </div>

                    <div class="mission-badge">
                        <span class="dot"></span>
                        مأموریت امروز: ثبت یک رکورد بهتر از قبل!
                    </div>

                    <div class="prep-bar">
                        <div class="fill"></div>
                    </div>
                </div>

                <a href="{{ route('student.exams.public') }}" class="btn btn-light btn-sm fw-bold">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت به آزمون‌های عمومی
                </a>
            </div>

            {{-- chips row --}}
            <div class="d-flex flex-wrap gap-2 mt-3">
                @if ($exam->scope === 'free')
                    <span class="chip dark"><i class="bi bi-globe2"></i> آزمون عمومی</span>
                @else
                    <span class="chip blue">
                        <i class="bi bi-people-fill"></i>
                        آزمون کلاسی
                        @if ($exam->classroom)
                            - {{ $exam->classroom->title ?? $exam->classroom->name }}
                        @endif
                    </span>
                @endif

                <span class="chip {{ $levelChip[0] }}">
                    <i class="bi {{ $levelChip[1] }}"></i>
                    {{ $levelChip[2] }}
                </span>

                <span class="chip">
                    <i class="bi bi-clock-history"></i>
                    مدت {{ $duration }} دقیقه
                </span>

                <span class="chip {{ $diffClass }}">
                    <i class="bi bi-speedometer2"></i>
                    سختی: {{ $diffText }}
                </span>
            </div>
        </div>


        {{-- ================= FLASH ================= --}}
        @if (session('warning'))
            <div class="alert alert-warning rounded-3 shadow-sm">{{ session('warning') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success rounded-3 shadow-sm">{{ session('success') }}</div>
        @endif


        {{-- ================= STATS (preview) ================= --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <div class="stat">
                    <div class="icon"><i class="bi bi-question-circle"></i></div>
                    <div>
                        <div class="label">تعداد سوالات</div>
                        <div class="value">{{ $qCount }}</div>
                        <div class="subvalue">تقریباً {{ max(1, ceil($qCount / 2)) }} دقیقه تمرکز</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat green">
                    <div class="icon"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <div class="label">سطح آزمون</div>
                        <div class="value">
                            <span class="difficulty-pill {{ $diffClass }}">
                                {{ $diffText }}
                            </span>
                        </div>
                        <div class="subvalue">هدف: بهتر از دفعه‌ی قبل</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat amber">
                    <div class="icon"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <div class="label">قانون آزمون</div>
                        <div class="value">فقط یک‌بار</div>
                        <div class="subvalue">پس با تمرکز شروع کن</div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ================= DESCRIPTION ================= --}}
        <div class="card-soft p-3 p-md-4 mb-3">
            <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-primary"></i>
                توضیحات آزمون
            </div>
            <div class="text-muted" style="line-height:1.9">
                {{ $exam->description ?? 'بدون توضیح' }}
            </div>
        </div>


        {{-- ================= CHALLENGE / MINDSET BOX ================= --}}
        <div class="challenge mb-3">
            <div class="ico"><i class="bi bi-rocket-takeoff"></i></div>
            <div>
                <div class="fw-bold mb-1">قبل از شروع یک نکته‌ی طلایی 👇</div>
                <div class="text-muted small" style="line-height:1.9">
                    سعی کن سوال‌ها رو بدون عجله جواب بدی.
                    اگر جایی گیر کردی رد شو و آخر برگرد.
                    این روش معمولاً باعث افزایش نمره میشه.
                </div>
            </div>
        </div>


        {{-- ================= FINAL / NORMAL STATE ================= --}}
        @if ($isFinalAttempt)
            <div class="alert-final d-flex align-items-center gap-3 mb-3">
                <div class="fs-2">⚠️</div>
                <div>
                    <div class="fw-bold mb-1">
                        شما قبلاً در این آزمون شرکت کرده‌اید.
                    </div>
                    <div class="small text-muted">
                        این آزمون فقط یک‌بار قابل برگزاری است و امکان شرکت مجدد وجود ندارد.
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('student.attempts.result', $lastAttempt->id) }}" class="btn btn-result">
                    مشاهده نتیجه قبلی
                    <i class="bi bi-eye ms-1"></i>
                </a>

                <a href="{{ route('student.exams.public') }}" class="btn btn-outline-secondary fw-bold">
                    بازگشت به لیست آزمون‌های عمومی
                </a>
            </div>
        @else
            <div class="card-soft p-3 p-md-4">
                <div class="hint mb-3">
                    <i class="bi bi-hourglass-split text-primary"></i>
                    با شروع آزمون، تایمر فعال می‌شود و فقط یک‌بار امکان شرکت دارید.
                    <span class="fw-bold">نفس عمیق بکش و شروع کن 😌</span>
                </div>

                <form method="POST" action="{{ route('student.exams.start', $exam->id) }}">
                    @csrf
                    <button class="btn btn-start w-100">
                        شروع مأموریت آزمون
                        <i class="bi bi-play-fill ms-1"></i>
                    </button>
                </form>

                <div class="small text-muted text-center mt-3">
                    بعد از زدن شروع، تایمر بلافاصله فعال میشه. آماده‌ای؟
                </div>
            </div>
        @endif

    </div>
@endsection
