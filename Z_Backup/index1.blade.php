@extends('layouts.app')

@section('title', 'پنل دانش‌آموز - داشبورد')

@push('styles')
    <style>
        :root {
            --p: #4361ee;
            --p2: #3a0ca3;
            --acc: #f72585;
            --ok: #4cc9f0;
            --warn: #f8961e;
            --dark: #0f172a;
            --muted: #64748b;
            --bg: #f8fafc;
            --card: #ffffff;
            --radius: 18px;
            --shadow: 0 10px 30px rgba(0, 0, 0, .06);
            --shadow-hover: 0 18px 40px rgba(0, 0, 0, .10);
            --grad: linear-gradient(135deg, var(--p), var(--p2));
            --grad2: linear-gradient(135deg, #7209b7, #f72585);
            --grad3: linear-gradient(135deg, #4cc9f0, #4895ef);
            --grad4: linear-gradient(135deg, #f8961e, #f9c74f);
        }

        .stu-dashboard {
            background: var(--bg);
            background-image: radial-gradient(#4361ee12 2px, transparent 2px);
            background-size: 40px 40px;
            border-radius: 14px;
            padding: 8px;
            animation: fadeIn .5s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        /* top header actions row (keep your three buttons) */
        .top-actions .btn {
            border-radius: 12px;
            font-weight: 800;
            padding: .6rem .9rem;
        }

        /* Welcome banner */
        .welcome {
            background: var(--card);
            border-radius: 22px;
            box-shadow: var(--shadow);
            border: 1px solid #eef2f7;
            padding: 22px 22px;
            position: relative;
            overflow: hidden;
        }

        .welcome::after {
            content: "";
            position: absolute;
            inset: auto -30% -60% auto;
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, rgba(67, 97, 238, .12), transparent 65%);
            transform: rotate(12deg);
        }

        .welcome h2 {
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--dark);
        }

        .welcome p {
            color: var(--muted);
            line-height: 1.9;
            font-size: .98rem;
            margin: 0;
        }

        .welcome .rocket {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 2rem;
            background: var(--grad);
            box-shadow: 0 8px 20px rgba(67, 97, 238, .25);
            animation: float 2.4s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-8px)
            }
        }

        /* Stat cards */
        .stat-card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid #eef2f7;
            padding: 16px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: .25s ease;
            min-height: 96px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-title {
            color: var(--muted);
            font-size: .85rem;
            font-weight: 700;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--dark);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.35rem;
        }

        .icon-1 {
            background: var(--grad);
        }

        .icon-2 {
            background: var(--grad3);
        }

        .icon-3 {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .icon-4 {
            background: var(--grad4);
        }

        /* main grid cards */
        .dash-card {
            background: var(--card);
            border-radius: 20px;
            box-shadow: var(--shadow);
            border: 1px solid #eef2f7;
            padding: 18px;
            transition: .3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .dash-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
        }

        .dash-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--grad);
            transform: scaleX(0);
            transform-origin: left;
            transition: .4s ease;
        }

        .dash-card:hover::before {
            transform: scaleX(1);
        }

        .dash-card .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .dash-card .card-head h3 {
            font-weight: 900;
            font-size: 1.05rem;
            margin: 0;
            color: var(--dark);
        }

        .dash-card .head-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.4rem;
        }

        /* exam list */
        .exam-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .exam-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 10px;
            border-radius: 10px;
            border-bottom: 1px dashed #eef2f7;
            transition: .2s ease;
        }

        .exam-item:last-child {
            border-bottom: none;
        }

        .exam-item:hover {
            background: rgba(67, 97, 238, .05);
            transform: translateX(4px);
        }

        .exam-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            color: #111827;
            font-size: .95rem;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-ok {
            background: var(--ok);
            box-shadow: 0 0 0 4px rgba(76, 201, 240, .15);
        }

        .dot-warn {
            background: var(--warn);
            box-shadow: 0 0 0 4px rgba(248, 150, 30, .15);
        }

        .dot-acc {
            background: var(--acc);
            box-shadow: 0 0 0 4px rgba(247, 37, 133, .12);
        }

        .exam-meta {
            font-size: .82rem;
            color: var(--muted);
            white-space: nowrap;
        }

        /* skills progress */
        .skill-item {
            margin-bottom: 14px;
        }

        .skill-head {
            display: flex;
            justify-content: space-between;
            font-weight: 800;
            font-size: .9rem;
            color: #111827;
            margin-bottom: 6px;
        }

        .skill-bar {
            height: 9px;
            background: #eef2f7;
            border-radius: 999px;
            overflow: hidden;
        }

        .skill-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--grad);
            width: 0;
            transition: width 1.2s ease;
        }

        /* reports mini stats */
        .mini-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 8px;
        }

        .mini {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            font-weight: 900;
        }

        .mini .val {
            font-size: 1.3rem;
            background: var(--grad);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .mini .lbl {
            font-size: .8rem;
            color: var(--muted);
            font-weight: 700;
        }

        /* advisor */
        .advisor-box {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .advisor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: var(--grad2);
            color: #fff;
            font-size: 1.4rem;
            box-shadow: 0 6px 16px rgba(114, 9, 183, .25);
        }

        .advisor-info h4 {
            margin: 0;
            font-weight: 900;
            font-size: 1rem;
        }

        .advisor-info p {
            margin: 2px 0 0;
            font-size: .86rem;
            color: var(--muted);
            line-height: 1.7;
        }

        .advisor-actions .btn {
            border-radius: 12px;
            font-weight: 900;
            padding: .55rem .8rem;
        }

        .btn-grad {
            background: var(--grad);
            color: #fff;
            border: none;
        }

        .btn-soft {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            font-weight: 800;
        }

        /* quick actions row */
        .quick-row .qa {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 900;
            color: #0f172a;
            box-shadow: var(--shadow);
            transition: .2s ease;
            text-decoration: none;
        }

        .quick-row .qa:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .qa i {
            font-size: 1.1rem;
            color: #334155;
        }

        @media (max-width: 992px) {
            .mini-stats {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid stu-dashboard">

        {{-- ========== TOP ACTIONS (same routes) ========== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 top-actions">
            <div>
                <h1 class="h5 fw-bold mb-1">داشبورد دانش‌آموز</h1>
                <div class="text-muted small">وضعیت آزمون‌ها، پیشرفت و تحلیل‌های شما در یک نگاه.</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('student.exams.public') }}" class="btn btn-primary">
                    <i class="fa-solid fa-globe ms-1"></i> آزمون‌های عمومی
                </a>
                <a href="{{ route('student.exams.classroom') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-people-group ms-1"></i> آزمون‌های کلاسی
                </a>
                <a href="{{ route('student.reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-chart-simple ms-1"></i> گزارش‌ها و تحلیل‌ها
                </a>
            </div>
        </div>

        {{-- ========== WELCOME ========== --}}
        <div class="welcome mb-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2 class="mb-2">سلام {{ auth()->user()->name ?? 'دانش‌آموز' }}! آماده پیشرفت هستی؟ 🚀</h2>
                <p>
                    امروز با یه آزمون کوتاه شروع کن؛ همین قدم‌های کوچیک،
                    تو رو به هدف‌های بزرگ می‌رسونه.
                </p>
            </div>
            <div class="rocket"><i class="fa-solid fa-rocket"></i></div>
        </div>

        {{-- ========== STATS ROW ========== --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">آزمون‌های در دسترس</div>
                        <div class="stat-value">{{ $stats['available_exams'] ?? '—' }}</div>
                    </div>
                    <div class="stat-icon icon-1"><i class="fa-solid fa-file-circle-check"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">آزمون‌های انجام‌شده</div>
                        <div class="stat-value">{{ $stats['taken_exams'] ?? '—' }}</div>
                    </div>
                    <div class="stat-icon icon-3"><i class="fa-solid fa-square-check"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">میانگین نمره</div>
                        <div class="stat-value">{{ $stats['avg_score'] ?? '—' }}</div>
                    </div>
                    <div class="stat-icon icon-2"><i class="fa-solid fa-gauge-high"></i></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div>
                        <div class="stat-title">سطح فعلی</div>
                        <div class="stat-value">{{ $stats['current_level'] ?? '—' }}</div>
                    </div>
                    <div class="stat-icon icon-4"><i class="fa-solid fa-layer-group"></i></div>
                </div>
            </div>
        </div>

        {{-- ========== MAIN GRID (4 cards like sample) ========== --}}
        <div class="row g-3">

            {{-- Advisor --}}
            <div class="col-lg-3">
                <div class="dash-card">
                    <div class="card-head">
                        <h3>پشتیبان آموزشی شما</h3>
                        <div class="head-icon" style="background:var(--grad4)"><i class="fa-solid fa-user-graduate"></i>
                        </div>
                    </div>

                    <div class="advisor-box mb-3">
                        <div class="advisor-avatar"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="advisor-info">
                            <h4>{{ $advisor['name'] ?? 'پشتیبان شما' }}</h4>
                            <p>{{ $advisor['title'] ?? 'مشاور تحصیلی' }}</p>
                            <p class="small">زمان پاسخگویی: {{ $advisor['hours'] ?? '۸ صبح تا ۸ شب' }}</p>
                        </div>
                    </div>

                    <div class="advisor-actions d-flex gap-2">
                        <a href="{{ $advisorChatUrl ?? route('student.support.index') }}" class="btn btn-grad flex-fill">
                            ارسال پیام <i class="fa-solid fa-comment-dots ms-1"></i>
                        </a>
                        <button class="btn btn-soft flex-fill" type="button" disabled>
                            تماس تصویری <i class="fa-solid fa-video ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Skills --}}
            <div class="col-lg-3">
                <div class="dash-card">
                    <div class="card-head">
                        <h3>مهارت‌های درسی</h3>
                        <div class="head-icon" style="background:var(--grad)"><i class="fa-solid fa-brain"></i></div>
                    </div>

                    @php
                        $skills = $skills ?? [
                            ['name' => 'ریاضی و حسابان', 'percent' => 85],
                            ['name' => 'فیزیک', 'percent' => 78],
                            ['name' => 'شیمی', 'percent' => 70],
                            ['name' => 'زیست‌شناسی', 'percent' => 92],
                        ];
                    @endphp

                    @foreach ($skills as $sk)
                        <div class="skill-item">
                            <div class="skill-head">
                                <span>{{ $sk['name'] }}</span>
                                <span class="text-primary">{{ $sk['percent'] }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-fill" style="width: {{ $sk['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Reports --}}
            <div class="col-lg-3">
                <div class="dash-card">
                    <div class="card-head">
                        <h3>آخرین گزارش‌ها</h3>
                        <div class="head-icon" style="background:var(--grad3)"><i class="fa-solid fa-chart-bar"></i></div>
                    </div>

                    <div class="text-muted small mb-2">
                        خلاصه عملکرد اخیر شما:
                    </div>

                    <div class="mini-stats">
                        <div class="mini">
                            <div class="val">{{ $stats['avg_score'] ?? '—' }}%</div>
                            <div class="lbl">میانگین نمرات</div>
                        </div>
                        <div class="mini">
                            <div class="val">{{ $stats['taken_exams'] ?? '—' }}</div>
                            <div class="lbl">آزمون انجام‌شده</div>
                        </div>
                        <div class="mini">
                            <div class="val">{{ $stats['study_hours'] ?? '—' }}</div>
                            <div class="lbl">ساعت مطالعه</div>
                        </div>
                        <div class="mini">
                            <div class="val">{{ $stats['badges'] ?? '—' }}</div>
                            <div class="lbl">نشان‌ها</div>
                        </div>
                    </div>

                    <a href="{{ route('student.reports.index') }}"
                        class="btn btn-outline-primary w-100 mt-3 fw-bold rounded-3">
                        مشاهده گزارش‌ها <i class="fa-solid fa-arrow-left ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Exams status --}}
            <div class="col-lg-3">
                <div class="dash-card">
                    <div class="card-head">
                        <h3>وضعیت آزمون‌ها</h3>
                        <div class="head-icon" style="background:var(--grad2)"><i class="fa-solid fa-clipboard-list"></i>
                        </div>
                    </div>

                    @php
                        $activeExams = $activeExams ?? [];
                    @endphp

                    @if (count($activeExams))
                        <ul class="exam-list">
                            @foreach ($activeExams as $ex)
                                @php
                                    $st = $ex['status'] ?? 'available';
                                    $dotClass =
                                        $st === 'in_progress' ? 'dot-warn' : ($st === 'pending' ? 'dot-acc' : 'dot-ok');
                                    $dateText = $ex['date_text'] ?? 'به‌زودی';
                                @endphp
                                <li class="exam-item">
                                    <div class="exam-name">
                                        <span class="dot {{ $dotClass }}"></span>
                                        <span>{{ $ex['title'] ?? 'آزمون' }}</span>
                                    </div>
                                    <div class="exam-meta">{{ $dateText }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted small py-4">
                            فعلاً آزمون فعالی برای نمایش نیست.
                        </div>
                    @endif

                    <a href="{{ route('student.exams.public') }}" class="btn btn-primary w-100 mt-2 fw-bold rounded-3">
                        شروع آزمون جدید <i class="fa-solid fa-play ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- ========== QUICK ACTIONS (same routes) ========== --}}
        <div class="dash-card mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-bold">میانبرهای سریع</div>
                <div class="text-muted small">سریع‌ترین مسیر برای ادامه دادن</div>
            </div>

            <div class="row g-2 quick-row">
                <div class="col-md-2 col-sm-4 col-6">
                    <a class="qa" href="{{ route('student.exams.public') }}">
                        <i class="fa-solid fa-globe"></i> آزمون‌های عمومی
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <a class="qa" href="{{ route('student.exams.classroom') }}">
                        <i class="fa-solid fa-people-group"></i> آزمون‌های کلاسی
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <a class="qa" href="{{ route('student.reports.index') }}">
                        <i class="fa-solid fa-chart-line"></i> گزارش‌ها
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <a class="qa" href="{{ route('student.profile') }}">
                        <i class="fa-solid fa-user-gear"></i> پروفایل
                    </a>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <a class="qa" href="{{ route('student.support.index') }}">
                        <i class="fa-solid fa-headset"></i> پشتیبانی
                    </a>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // animate skill bars on load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.skill-fill').forEach((el, i) => {
                const w = el.style.width;
                el.style.width = '0%';
                setTimeout(() => el.style.width = w, 200 + i * 120);
            });
        });
    </script>
@endpush
