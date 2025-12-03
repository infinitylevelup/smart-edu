@extends('layouts.app')
@section('title', 'نتیجه آزمون')

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

        .result-page {
            animation: pageFade .6s ease both;
        }

        @keyframes pageFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-hero {
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

        .result-hero::after {
            content: "";
            position: absolute;
            inset: -35% -20% auto auto;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255, 255, 255, .35), transparent 70%);
            transform: rotate(15deg);
            opacity: .85;
            pointer-events: none;
        }

        .result-hero h3 {
            font-weight: 900;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            gap: .5rem;
            align-items: center;
        }

        .result-hero .sub {
            font-size: .85rem;
            color: rgba(255, 255, 255, .95);
            margin-top: .25rem;
        }

        .score-ring {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            margin-inline: auto;
            background: conic-gradient(var(--edu-green) calc(var(--percent)*1%), #e2e8f0 0);
            position: relative;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .12);
            animation: ringPop .8s ease both;
        }

        @keyframes ringPop {
            from {
                transform: scale(.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .score-ring::before {
            content: "";
            position: absolute;
            inset: 12px;
            background: #fff;
            border-radius: 50%;
            box-shadow: inset 0 0 0 2px #f1f5f9;
        }

        .score-ring .inner {
            position: relative;
            text-align: center;
        }

        .score-ring .percent {
            font-size: 2rem;
            font-weight: 900;
            color: var(--edu-gray);
            line-height: 1;
        }

        .score-ring .label {
            font-size: .8rem;
            font-weight: 800;
            color: var(--edu-muted);
            margin-top: .25rem;
        }

        .reward-card {
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
            animation: cardIn .6s ease both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reward-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .45rem .7rem;
            border-radius: 999px;
            font-weight: 900;
            font-size: .8rem;
            border: 1px solid transparent;
        }

        .reward-badge.gold {
            background: #fff7ed;
            color: #9a3412;
            border-color: #fed7aa;
        }

        .reward-badge.silver {
            background: #f8fafc;
            color: #334155;
            border-color: #e2e8f0;
        }

        .reward-badge.bronze {
            background: #fffbeb;
            color: #92400e;
            border-color: #fde68a;
        }

        .stat {
            border-radius: var(--radius-lg);
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: .9rem 1rem;
            display: flex;
            gap: .75rem;
            align-items: center;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
        }

        .stat .icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
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
            font-size: .78rem;
            color: var(--edu-muted);
            font-weight: 800;
        }

        .stat .value {
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--edu-gray);
            margin-top: .1rem;
        }

        .motivate {
            border-radius: var(--radius-lg);
            padding: .9rem 1rem;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            font-size: .9rem;
            line-height: 1.9;
            color: #334155;
            display: flex;
            align-items: start;
            gap: .6rem;
        }

        .motivate .emoji {
            font-size: 1.4rem;
            line-height: 1;
        }

        .motivate.good {
            border-color: #bbf7d0;
            background: #fbfffd;
        }

        .motivate.bad {
            border-color: #fde68a;
            background: #fffbeb;
        }

        .btn-cta {
            border-radius: .95rem;
            font-weight: 900;
            padding: .8rem 1rem;
            border: none;
            background: linear-gradient(135deg, var(--edu-blue), var(--edu-blue-2));
            box-shadow: 0 12px 28px rgba(37, 99, 235, .28);
            transition: .2s ease;
        }

        .btn-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .38);
        }

        .btn-ghost {
            border-radius: .95rem;
            font-weight: 900;
            padding: .8rem 1rem;
        }

        .reward-wrap {
            border-radius: 1.25rem;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
            max-width: 1200px;
            margin-inline: auto;
        }

        .reward-wrap::after {
            content: "";
            position: absolute;
            inset: -40% -20% auto auto;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(14, 165, 233, .18), transparent 70%);
            pointer-events: none;
        }

        .level-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-weight: 900;
            font-size: .9rem;
            padding: .45rem .8rem;
            border-radius: 999px;
            background: #0f172a;
            color: #fff;
            box-shadow: 0 10px 22px rgba(15, 23, 42, .22);
        }

        .checkpoint {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-weight: 900;
            font-size: .9rem;
            background: #0ea5e90f;
            color: #0ea5e9;
            border: 1px solid #0ea5e933;
            padding: .6rem .8rem;
            border-radius: 1rem;
        }

        .medal {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .9rem 1rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            transition: .2s ease;
        }

        .medal:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(37, 99, 235, .10);
        }

        .medal .icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        .medal.gold .icon {
            background: #fff7ed;
            color: #f59e0b;
            border: 1px solid #fde68a;
        }

        .medal.silver .icon {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .medal.bronze .icon {
            background: #ecfdf3;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .medal .title {
            font-weight: 900;
            font-size: 1rem;
        }

        .medal .sub {
            font-size: .85rem;
            color: #64748b;
            font-weight: 700;
        }

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
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            border: none;
            color: #fff;
            box-shadow: 0 12px 28px rgba(37, 99, 235, .28);
            transition: .2s ease;
        }

        .next-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .38);
        }

        .pop-star {
            animation: pop .7s ease both;
        }

        @keyframes pop {
            0% {
                transform: scale(.6);
                opacity: 0
            }

            60% {
                transform: scale(1.1);
                opacity: 1
            }

            100% {
                transform: scale(1);
                opacity: 1
            }
        }

        .review-card {
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            overflow: hidden;
            max-width: 1200px;
            margin-inline: auto;
        }

        .review-item {
            border-bottom: 1px dashed #e2e8f0;
            padding: 1rem 1.1rem;
        }

        .review-item:last-child {
            border-bottom: 0;
        }

        .ans-chip {
            font-size: .78rem;
            font-weight: 900;
            padding: .35rem .55rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
        }

        .ans-chip.correct {
            background: var(--edu-green-soft);
            color: var(--edu-green);
            border: 1px solid #bbf7d0;
        }

        .ans-chip.wrong {
            background: var(--edu-red-soft);
            color: var(--edu-red);
            border: 1px solid #fecaca;
        }
    </style>
@endpush

@section('content')
    @php
        // ====== پایه + fallbacks ======
        $percent = (float) ($attempt->percent ?? ($attempt->percentage ?? 0));

        $scoreObtained = (int) ($attempt->score_obtained ?? ($attempt->score ?? 0));

        $scoreTotal =
            (int) ($attempt->score_total ?? ($attempt->total_score ?? ($exam?->questions?->sum('score') ?? 0)));

        // ====== answers relation vs answers JSON ======
        $attemptAnswerModels = collect($attempt->getRelation('answers') ?? []);

        if ($attemptAnswerModels->isEmpty() && is_array($attempt->answers ?? null)) {
            // answers JSON: [question_id => user_answer]
            $attemptAnswerModels = collect($attempt->answers)->map(function ($userAns, $qid) use ($exam) {
                $q = $exam?->questions?->firstWhere('id', (int) $qid);
                return (object) [
                    'question' => $q,
                    'answer' => $userAns,
                    'is_correct' => null, // چون JSON هست، درست/غلط نداریم
                ];
            });
        }

        $wrongAnswers = $attemptAnswerModels->filter(fn($a) => (int) ($a->is_correct ?? 0) === 0);
        $correctAnswers = $attemptAnswerModels->filter(fn($a) => (int) ($a->is_correct ?? 0) === 1);

        $correctCount = $correctAnswers->count();
        $wrongCount = $wrongAnswers->count();

        // ====== پیام انگیزشی ======
        if ($percent >= 85) {
            $statusTitle = 'فوق‌العاده بودی! 🌟';
            $statusMsg = 'تو واقعاً آماده‌ای سطح بعدی رو بزنی. بریم یه آزمون سخت‌تر؟';
            $badge = 'gold';
            $nextHint = 'چالش امروزت رو کامل کردی؛ سطح بعدی منتظرته!';
        } elseif ($percent >= 60) {
            $statusTitle = 'خیلی خوب پیش رفتی! 💪';
            $statusMsg = 'چندتا نکته مونده تا عالی شی. آزمون بعدی رو بزن تا رکوردت رو بشکنی.';
            $badge = 'silver';
            $nextHint = 'فقط چند قدم تا سطح قهرمانی فاصله داری.';
        } else {
            $statusTitle = 'شروع خوبی بود! 🚀';
            $statusMsg = 'اشتباه‌ها یعنی مسیر یادگیری. یه آزمون کوتاه‌تر بزن تا سریع‌تر قوی شی.';
            $badge = 'bronze';
            $nextHint = 'هر قهرمانی اولش چند بار زمین می‌خوره.';
        }

        // ====== LevelUp / Next exam suggestion ======
        if ($percent >= 85) {
            $levelUpText = 'سطح ۲ باز شد 🎉';
            $medalType = 'gold';
            $medalTitle = 'مدال طلایی گرفتی!';
            $medalSub = 'تو آماده‌ی چالش سخت‌تر هستی.';
            $nextMsg = 'یه آزمون المپیادی/سخت‌تر بزن و رکورد رو بالا ببر.';
            $nextFilter = 'olympiad';
            $nextIcon = 'bi-award-fill';
        } elseif ($percent >= 60) {
            $levelUpText = 'سطح ۱٫۵ فعال شد ✨';
            $medalType = 'silver';
            $medalTitle = 'مدال نقره گرفتی!';
            $medalSub = 'فقط چند قدم تا طلایی شدن فاصله داری.';
            $nextMsg = 'یک آزمون مشابه بزن تا رکوردتو بشکنی.';
            $nextFilter = 'konkur';
            $nextIcon = 'bi-bullseye';
        } else {
            $levelUpText = 'شروع مسیر قهرمانی 🧠';
            $medalType = 'bronze';
            $medalTitle = 'مدال برنزی گرفتی!';
            $medalSub = 'اشتباه یعنی یادگیری؛ این دقیقاً خوبه.';
            $nextMsg = 'یک آزمون تقویتی کوتاه‌تر بزن تا سریع قوی شی.';
            $nextFilter = 'taghviyati';
            $nextIcon = 'bi-lightning-fill';
        }

        // ====== Back route smart ======
        $backRoute =
            $exam?->scope === 'free' || $exam?->is_public
                ? route('student.exams.public')
                : route('student.exams.index');
    @endphp

    <div class="result-page container py-3 py-md-4">

        {{-- ================= HERO ================= --}}
        <div class="result-hero mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h3>
                        <i class="bi bi-trophy"></i>
                        نتیجه آزمون
                    </h3>
                    <div class="sub">
                        آزمون: <span class="fw-bold">{{ $exam?->title ?? '—' }}</span>
                    </div>
                </div>
                <a href="{{ $backRoute }}" class="btn btn-light btn-sm fw-bold">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>

        {{-- ================= SCORE + REWARD SUMMARY ================= --}}
        <div class="row g-3 align-items-stretch mb-3" style="max-width:1200px;margin-inline:auto;">
            <div class="col-12 col-lg-4">
                <div class="reward-card h-100 text-center">
                    <div class="score-ring mx-auto" style="--percent: {{ $percent }};">
                        <div class="inner">
                            <div class="percent">{{ round($percent) }}%</div>
                            <div class="label">درصد شما</div>
                        </div>
                    </div>

                    <div class="mt-3 fw-bold fs-5">{{ $statusTitle }}</div>
                    <div class="text-muted small mt-1">{{ $statusMsg }}</div>

                    <div class="small text-muted mt-2 fw-bold">
                        درست: <span class="text-success">{{ $correctCount }}</span>
                        |
                        غلط: <span class="text-danger">{{ $wrongCount }}</span>
                    </div>

                    <div class="mt-3">
                        <span class="reward-badge {{ $badge }}">
                            <i class="bi bi-award-fill"></i>
                            نشان امروز
                            @if ($badge == 'gold')
                                طلایی
                            @elseif($badge == 'silver')
                                نقره‌ای
                            @else
                                برنزی
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="reward-card h-100">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="stat green">
                                <div class="icon"><i class="bi bi-check-circle"></i></div>
                                <div>
                                    <div class="label">تعداد درست</div>
                                    <div class="value">{{ $correctCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="stat">
                                <div class="icon"><i class="bi bi-x-circle"></i></div>
                                <div>
                                    <div class="label">تعداد غلط</div>
                                    <div class="value">{{ $wrongCount }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="stat amber">
                                <div class="icon"><i class="bi bi-stars"></i></div>
                                <div>
                                    <div class="label">امتیاز کل</div>
                                    <div class="value">{{ $scoreObtained }} / {{ $scoreTotal }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="motivate good mt-3">
                        <div class="emoji">🎯</div>
                        <div>
                            <div class="fw-bold">نکته‌ی مهم:</div>
                            {{ $nextHint }}
                        </div>
                    </div>

                    @if ($percent < 60)
                        <div class="motivate bad mt-2">
                            <div class="emoji">🔥</div>
                            <div>
                                <div class="fw-bold">چالش بعدی برای رشد سریع:</div>
                                یک آزمون تقویتی کوتاه بزن؛ معمولاً بعد از آزمون دوم، جهش نمره می‌بینی.
                            </div>
                        </div>
                    @else
                        <div class="motivate good mt-2">
                            <div class="emoji">⚡</div>
                            <div>
                                <div class="fw-bold">بزن رکورد جدید!</div>
                                اگر همین الان یک آزمون دیگه بدی، مغزت هنوز در حالت «یادگیری فعال»ه و بهتر جواب می‌دی.
                            </div>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ $backRoute }}" class="btn btn-cta">
                            رفتن به آزمون‌های بعدی
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>

                        <a href="{{ route('student.classrooms.index') }}" class="btn btn-outline-secondary btn-ghost">
                            دیدن کلاس‌ها
                            <i class="bi bi-people ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CHECKPOINT + NEXT EXAM ================= --}}
        <div class="reward-wrap mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="level-pill pop-star">
                    <i class="bi bi-stars"></i>
                    {{ $levelUpText }}
                </div>

                <div class="checkpoint pop-star">
                    <i class="bi bi-graph-up-arrow"></i>
                    درست: {{ $correctCount }} | غلط: {{ $wrongCount }}
                </div>
            </div>

            <div class="medal {{ $medalType }}">
                <div class="icon pop-star">
                    @if ($medalType === 'gold')
                        <i class="bi bi-trophy-fill"></i>
                    @elseif($medalType === 'silver')
                        <i class="bi bi-gem"></i>
                    @else
                        <i class="bi bi-shield-fill-check"></i>
                    @endif
                </div>
                <div>
                    <div class="title">{{ $medalTitle }}</div>
                    <div class="sub mt-1">{{ $medalSub }}</div>
                </div>
            </div>

            <div class="next-box mt-3">
                <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                    <i class="bi {{ $nextIcon }} text-primary"></i>
                    پیشنهاد مرحله‌ی بعدی
                </div>
                <div class="text-muted small mb-3" style="line-height:1.9">
                    {{ $nextMsg }}
                </div>

                {{-- query-string فیلتر سطح --}}
                <a href="{{ $backRoute }}?level={{ $nextFilter }}" class="btn next-btn w-100">
                    شروع آزمون بعدی
                    <i class="bi bi-play-fill ms-1"></i>
                </a>

                <div class="small text-muted text-center mt-2">
                    هر آزمون یک قدم به قهرمانی نزدیک‌ترت می‌کنه 💪
                </div>
            </div>
        </div>

        {{-- ================= WRONG ONLY ================= --}}
        <div class="review-card mt-3">
            <div class="p-3 p-md-4 border-bottom">
                <div class="fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-fire text-warning"></i>
                    سوالاتی که نیاز به تمرین بیشتر دارند
                </div>
                <div class="small text-muted mt-1">
                    فقط سوالاتی که اشتباه جواب دادی اینجا هستند —
                    <span class="fw-bold text-danger">{{ $wrongCount }}</span> سوال.
                </div>
            </div>

            @if ($wrongCount == 0)
                <div class="p-3 p-md-4 text-center text-success fw-bold">
                    👏 عالی! هیچ سوال غلطی نداری.
                </div>
            @else
                @foreach ($wrongAnswers as $ans)
                    @php $q = $ans->question ?? null; @endphp

                    <div class="review-item">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <div class="fw-bold">
                                سوال {{ $loop->iteration }}
                            </div>
                            <span class="ans-chip wrong">
                                <i class="bi bi-x-circle"></i> نیاز به تمرین
                            </span>
                        </div>

                        <div class="text-muted" style="line-height:1.9">
                            {!! nl2br(e($q->question_text ?? ($q->question ?? '—'))) !!}
                        </div>

                        @if (!empty($q?->explanation))
                            <div class="mt-2 p-2 rounded-3" style="background:#f8fafc; border:1px dashed #e2e8f0;">
                                <div class="small fw-bold mb-1">
                                    <i class="bi bi-lightbulb"></i> توضیح:
                                </div>
                                <div class="small text-muted">{{ $q->explanation }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- ================= CORRECT ONLY ================= --}}
        <div class="review-card mt-3">
            <div class="p-3 p-md-4 border-bottom">
                <div class="fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle text-success"></i>
                    سوالاتی که درست جواب دادی
                </div>
                <div class="small text-muted mt-1">
                    این‌ها سوالاتی‌ان که درست زدی —
                    <span class="fw-bold text-success">{{ $correctCount }}</span> سوال.
                </div>
            </div>

            @if ($correctCount == 0)
                <div class="p-3 p-md-4 text-center text-muted fw-bold">
                    هنوز سوال درست ثبت نشده.
                </div>
            @else
                @foreach ($correctAnswers as $ans)
                    @php $q = $ans->question ?? null; @endphp

                    <div class="review-item">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <div class="fw-bold">
                                سوال {{ $loop->iteration }}
                            </div>
                            <span class="ans-chip correct">
                                <i class="bi bi-check2-circle"></i> درست
                            </span>
                        </div>

                        <div class="text-muted" style="line-height:1.9">
                            {!! nl2br(e($q->question_text ?? ($q->question ?? '—'))) !!}
                        </div>

                        @if (!empty($q?->explanation))
                            <div class="mt-2 p-2 rounded-3" style="background:#fbfffd; border:1px dashed #bbf7d0;">
                                <div class="small fw-bold mb-1 text-success">
                                    <i class="bi bi-lightbulb"></i> توضیح تکمیلی:
                                </div>
                                <div class="small text-muted">{{ $q->explanation }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>
@endsection
