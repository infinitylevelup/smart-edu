@extends('layouts.app')
@section('title', 'آزمون‌های عمومی')

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
            --edu-amber-soft: #fffbeb;

            --edu-gray: #0f172a;
            --edu-muted: #64748b;

            --edu-card: #ffffff;
            --edu-bg: #f8fafc;

            --radius-xl: 1.25rem;
            --radius-lg: 1rem;
        }

        .exam-page {
            animation: pageFade .6s ease both;
        }

        @keyframes pageFade {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .exam-header {
            background: linear-gradient(135deg, var(--edu-blue) 0%, var(--edu-blue-2) 55%, var(--edu-green-2) 100%);
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(37, 99, 235, .18);
            max-width: 1200px;
            margin-inline: auto;
        }

        .exam-header::after {
            content: "";
            position: absolute;
            inset: -35% -20% auto auto;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(255, 255, 255, .35), transparent 70%);
            transform: rotate(18deg);
            opacity: .75;
            pointer-events: none;
        }

        .exam-header h4 {
            font-size: 1.15rem;
            font-weight: 900;
            margin-bottom: .25rem;
        }

        .exam-header .subtitle {
            color: rgba(255, 255, 255, .92);
            font-size: .82rem;
            line-height: 1.7;
        }

        .exam-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
            align-items: stretch;
            max-width: 1200px;
            margin-inline: auto;
            margin-top: .75rem;
        }

        .exam-card {
            background: var(--edu-card);
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-lg);
            position: relative;
            overflow: hidden;
            transition: .25s ease;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
            animation: cardIn .55s ease both;
            display: flex;
            flex-direction: column;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(.98)
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1)
            }
        }

        .exam-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 36px rgba(37, 99, 235, .12);
            border-color: #c7d2fe;
        }

        .exam-card .accent {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--edu-blue), var(--edu-green-2));
        }

        .exam-card.done .accent {
            background: linear-gradient(90deg, var(--edu-amber), #fb7185);
        }

        .exam-card.done {
            background: #fffdfa;
            border: 1px dashed #f59e0b66;
        }

        .exam-body {
            padding: 1rem 1rem .9rem;
        }

        .exam-title {
            font-weight: 900;
            color: var(--edu-gray);
            font-size: 1.05rem;
            letter-spacing: .2px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .exam-title i {
            color: var(--edu-blue);
            font-size: 1.15rem;
        }

        .exam-desc {
            color: var(--edu-muted);
            font-size: .9rem;
            line-height: 1.8;
            margin-top: .35rem;
            min-height: 42px;
        }

        .badge-soft {
            padding: .42rem .6rem;
            font-size: .78rem;
            border-radius: 999px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .badge-blue {
            background: var(--edu-blue-soft);
            color: var(--edu-blue);
            border-color: #bfdbfe;
        }

        .badge-green {
            background: var(--edu-green-soft);
            color: var(--edu-green);
            border-color: #bbf7d0;
        }

        .badge-amber {
            background: var(--edu-amber-soft);
            color: #92400e;
            border-color: #fde68a;
        }

        .badge-dark {
            background: #0f172a;
            color: #fff;
        }

        .badge-light2 {
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }

        .motivate {
            background: linear-gradient(90deg, #f8fafc, #eef2ff);
            border: 1px solid #e2e8f0;
            border-radius: .8rem;
            padding: .45rem .6rem;
            font-size: .8rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: .45rem;
            margin-top: .6rem;
        }

        .motivate i {
            color: var(--edu-blue-2);
        }

        .done-alert {
            background: var(--edu-amber-soft);
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: .85rem;
            padding: .5rem .75rem;
            font-size: .84rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-top: .6rem;
            animation: softPulse 2s infinite ease-in-out;
        }

        @keyframes softPulse {

            0%,
            100% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.015)
            }
        }

        .exam-actions {
            padding: 0 1rem 1rem;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .btn-start {
            border-radius: .9rem;
            font-weight: 900;
            padding: .7rem .9rem;
            background: linear-gradient(135deg, var(--edu-blue), var(--edu-blue-2));
            border: none;
            box-shadow: 0 10px 24px rgba(37, 99, 235, .25);
            transition: .2s ease;
        }

        .btn-start:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(37, 99, 235, .35);
        }

        .btn-result {
            border-radius: .9rem;
            font-weight: 900;
            padding: .7rem .9rem;
            background: linear-gradient(135deg, var(--edu-green), var(--edu-green-2));
            border: none;
            box-shadow: 0 10px 24px rgba(34, 197, 94, .25);
        }

        .btn-disabled {
            border-radius: .9rem;
            font-weight: 800;
            background: #f1f5f9 !important;
            color: #334155 !important;
            border: 1px dashed #cbd5e1 !important;
            opacity: .9 !important;
        }

        .exam-card .bg-icon {
            position: absolute;
            left: -10px;
            bottom: -12px;
            font-size: 4.6rem;
            color: #e2e8f0;
            opacity: .35;
            transform: rotate(-8deg);
            pointer-events: none;
        }

        .exam-card.done .bg-icon {
            color: #fde68a;
            opacity: .25;
        }
    </style>
@endpush


@section('content')
    <div class="exam-page">

        {{-- Header --}}
        <div class="exam-header mb-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4>
                    <i class="bi bi-globe2 me-1"></i>
                    آزمون‌های عمومی
                </h4>
                <div class="subtitle">
                    این آزمون‌ها برای همه دانش‌آموزان فعال هستند. هر زمان خواستی امتحان بده و پیشرفتت رو ببین 🚀
                </div>
            </div>
        </div>


        {{-- Empty State --}}
        @if ($exams->count() == 0)
            <div class="alert alert-warning d-flex align-items-center gap-2 rounded-3 shadow-sm">
                <i class="bi bi-emoji-frown fs-4"></i>
                فعلاً آزمون عمومی منتشر نشده است.
            </div>
        @else
            <div class="exam-grid">
                @foreach ($exams as $exam)
                    @php
                        // ✅ safe attempt fetch (loaded or not)
                        $attemptsRelLoaded =
                            method_exists($exam, 'relationLoaded') && $exam->relationLoaded('attempts');

                        $lastAttempt = $attemptsRelLoaded
                            ? $exam->attempts
                                ->where('student_id', auth()->id())
                                ->sortByDesc('created_at')
                                ->first()
                            : $exam
                                ->attempts()
                                ->where('student_id', auth()->id())
                                ->latest()
                                ->first();

                        $isFinalAttempt = $lastAttempt && $lastAttempt->isFinal();

                        // ✅ level icon based on current DB enum: easy/average/hard/tough
                        $levelIcon = match ($exam->level) {
                            'easy' => 'bi-lightning-charge',
                            'hard' => 'bi-bullseye',
                            'tough' => 'bi-award',
                            default => 'bi-speedometer2', // average
                        };

                        $levelBadge = match ($exam->level) {
                            'easy' => ['badge-green', 'آسان', 'bi-lightning-fill'],
                            'hard' => ['badge-blue', 'سخت', 'bi-bullseye'],
                            'tough' => ['badge-amber', 'خیلی سخت', 'bi-award-fill'],
                            default => ['badge-light2', 'متوسط', 'bi-speedometer2'], // average
                        };
                    @endphp

                    <div class="exam-card {{ $isFinalAttempt ? 'done' : '' }}">
                        <div class="accent"></div>

                        {{-- background icon --}}
                        <i class="bi {{ $levelIcon }} bg-icon"></i>

                        <div class="exam-body">
                            {{-- Title + badge --}}
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <div class="exam-title">
                                    <i class="bi bi-file-earmark-text"></i>
                                    {{ $exam->title }}
                                </div>

                                <span class="badge-soft badge-dark">
                                    <i class="bi bi-globe2"></i> عمومی
                                </span>
                            </div>

                            <p class="exam-desc">
                                {{ $exam->description ? \Illuminate\Support\Str::limit($exam->description, 110) : 'بدون توضیح' }}
                            </p>

                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <span class="badge-soft {{ $levelBadge[0] }}">
                                    <i class="bi {{ $levelBadge[2] }}"></i>
                                    {{ $levelBadge[1] }}
                                </span>

                                <span class="badge-soft badge-light2">
                                    <i class="bi bi-clock-history"></i>
                                    {{ $exam->duration }} دقیقه
                                </span>

                                @if ($isFinalAttempt)
                                    <span class="badge-soft badge-amber">
                                        <i class="bi bi-check2-circle"></i> تکمیل‌شده
                                    </span>
                                @endif
                            </div>

                            <div class="motivate">
                                <i class="bi bi-graph-up-arrow"></i>
                                هر آزمون یه قدم به جلوئه — ادامه بده!
                            </div>

                            @if ($isFinalAttempt)
                                <div class="done-alert">
                                    <i class="bi bi-shield-exclamation"></i>
                                    شما قبلاً این آزمون رو داده‌اید. تکرار غیرفعال است.
                                </div>
                            @endif
                        </div>

                        <div class="exam-actions">
                            @if ($isFinalAttempt)
                                <a href="{{ route('student.attempts.result', $lastAttempt->id) }}"
                                    class="btn btn-result w-100">
                                    مشاهده نتیجه <i class="bi bi-eye ms-1"></i>
                                </a>

                                <button class="btn btn-disabled w-100" disabled>
                                    شروع آزمون (غیرفعال) <i class="bi bi-lock-fill ms-1"></i>
                                </button>
                            @else
                                <form method="POST" action="{{ route('student.exams.start', $exam->id) }}">
                                    @csrf
                                    <button class="btn btn-start w-100">
                                        شروع آزمون <i class="bi bi-play-fill ms-1"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($exams instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $exams->links() }}
                </div>
            @endif

        @endif
    </div>
@endsection
