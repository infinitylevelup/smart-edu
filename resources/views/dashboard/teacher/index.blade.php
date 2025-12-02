@extends('layouts.app')

@section('title', 'پنل معلم - داشبورد')

@push('styles')
    <style>
        /* ===== Teacher Dashboard Extra Polish ===== */
        .hero-wrap {
            background: radial-gradient(1200px circle at 100% -20%, rgba(13, 110, 253, .15), transparent 60%),
                radial-gradient(900px circle at 0% 0%, rgba(32, 201, 151, .12), transparent 55%),
                linear-gradient(180deg, #ffffff, #f8fafc);
            border-radius: 1.5rem;
            padding: 1.25rem 1.25rem;
            box-shadow: 0 10px 30px rgba(18, 38, 63, .08);
            position: relative;
            overflow: hidden;
        }

        .hero-orb {
            position: absolute;
            inset: auto -80px -80px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(13, 110, 253, .18), transparent 60%);
            filter: blur(2px);
            animation: floaty 7s ease-in-out infinite;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-10px) translateX(-8px);
            }
        }

        .quick-action {
            border-radius: 1rem;
            padding: .9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            box-shadow: 0 8px 20px rgba(18, 38, 63, .06);
            transition: .2s ease;
            text-decoration: none;
            color: inherit;
        }

        .quick-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(18, 38, 63, .12);
        }

        .quick-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(13, 110, 253, .12), rgba(13, 110, 253, .02));
            font-size: 1.2rem;
        }

        .recent-item {
            transition: .2s ease;
            border-left: 0 !important;
            border-right: 0 !important;
        }

        .recent-item:hover {
            background: #f6f9ff;
            transform: translateX(-2px);
        }

        .system-card {
            background: #0b1220;
            color: #e5e7eb;
            border-radius: 1.25rem;
            padding: 1rem 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .system-card::after {
            content: "";
            position: absolute;
            inset: -40% -30% auto auto;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34, 197, 94, .25), transparent 60%);
        }

        .system-bullet {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            font-size: .9rem;
        }

        .system-bullet i {
            margin-top: .15rem;
        }

        .badge-soft {
            background: rgba(13, 110, 253, .1);
            color: #0d6efd;
            font-weight: 800;
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid">

        {{-- ===== HERO HEADER ===== --}}
        <div class="hero-wrap mb-4 fade-up">
            <div class="hero-orb"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-1">
                        <i class="bi bi-speedometer2 me-1 text-primary"></i>
                        داشبورد معلم
                    </h1>
                    <p class="text-muted mb-0">
                        خلاصه‌ای از آزمون‌ها، کلاس‌ها و فعالیت دانش‌آموزان شما.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('teacher.exams.create') }}"
                        class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-plus"></i>
                        ایجاد آزمون جدید
                    </a>
                    <a href="{{ route('teacher.exams.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-ui-checks-grid"></i>
                        مدیریت آزمون‌ها
                    </a>
                </div>
            </div>
        </div>


        {{-- ===== KPI ROW ===== --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3 col-sm-6 fade-up">
                <div class="kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-title">تعداد کل آزمون‌ها</div>
                            <div class="kpi-value">{{ $stats['total_exams'] ?? '—' }}</div>
                        </div>
                        <div class="kpi-icon">
                            <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 fade-up">
                <div class="kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-title">آزمون‌های فعال</div>
                            <div class="kpi-value">{{ $stats['active_exams'] ?? '—' }}</div>
                        </div>
                        <div class="kpi-icon"
                            style="background:linear-gradient(135deg, rgba(25,135,84,.14), rgba(25,135,84,.02))">
                            <i class="bi bi-lightning-charge text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 fade-up">
                <div class="kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-title">تعداد دانش‌آموزان</div>
                            <div class="kpi-value">{{ $stats['students_count'] ?? '—' }}</div>
                        </div>
                        <div class="kpi-icon"
                            style="background:linear-gradient(135deg, rgba(13,202,240,.18), rgba(13,202,240,.02))">
                            <i class="bi bi-mortarboard text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 fade-up">
                <div class="kpi-card p-3 h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="kpi-title">پاسخ‌ بررسی‌نشده</div>
                            <div class="kpi-value">{{ $stats['pending_reviews'] ?? '—' }}</div>
                        </div>
                        <div class="kpi-icon"
                            style="background:linear-gradient(135deg, rgba(255,193,7,.20), rgba(255,193,7,.02))">
                            <i class="bi bi-clipboard-check text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ===== SECOND ROW ===== --}}
        <div class="row g-3">

            {{-- Recent Exams --}}
            <div class="col-lg-8 fade-up">
                <div class="table-modern">
                    <div class="d-flex justify-content-between align-items-center px-3 pt-3 pb-2">
                        <h2 class="h6 mb-0 fw-bold">
                            <i class="bi bi-clock-history me-1 text-primary"></i>
                            آزمون‌های اخیر شما
                        </h2>
                        <a href="{{ route('teacher.exams.index') }}" class="text-decoration-none small">
                            مشاهده همه
                            <i class="bi bi-arrow-left ms-1"></i>
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @php $recentExams = $recentExams ?? []; @endphp

                        @if (count($recentExams))
                            <div class="list-group list-group-flush">
                                @foreach ($recentExams as $exam)
                                    <a href="{{ route('teacher.exams.edit', $exam->id ?? $exam['id']) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center recent-item">

                                        <div>
                                            <div class="fw-semibold d-flex align-items-center gap-2">
                                                <i class="bi bi-journal-text text-primary"></i>
                                                {{ $exam->title ?? ($exam['title'] ?? 'آزمون بدون عنوان') }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-layers me-1"></i>
                                                سطح: {{ $exam->level ?? ($exam['level'] ?? 'نامشخص') }}
                                                &nbsp; • &nbsp;
                                                <i class="bi bi-calendar-event me-1"></i>
                                                تاریخ برگزاری:
                                                {{ \Illuminate\Support\Carbon::parse($exam->start_at ?? ($exam['start_at'] ?? now()))->format('Y/m/d H:i') }}
                                            </div>
                                        </div>

                                        <span class="badge rounded-pill badge-soft">
                                            {{ $exam->attempts_count ?? ($exam['attempts_count'] ?? 0) }}
                                            شرکت‌کننده
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted small">
                                <div class="mb-2 fs-3 text-primary">
                                    <i class="bi bi-clipboard2-x"></i>
                                </div>
                                هنوز آزمونی ثبت نکرده‌اید.
                                <br>
                                <a href="{{ route('teacher.exams.create') }}" class="btn btn-link mt-2">
                                    ایجاد اولین آزمون
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Shortcuts + System status --}}
            <div class="col-lg-4">

                {{-- Quick actions --}}
                <div class="panel-card p-3 mb-3 fade-up">
                    <h2 class="h6 fw-bold mb-3">
                        <i class="bi bi-lightning me-1 text-warning"></i>
                        میانبرهای مهم
                    </h2>

                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.classes.index') }}" class="quick-action">
                            <div class="d-flex align-items-center gap-2">
                                <div class="quick-icon text-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">مدیریت کلاس‌ها</div>
                                    <div class="text-muted small">ساخت، ویرایش و گروه‌بندی</div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>

                        <a href="{{ route('teacher.students.index') }}" class="quick-action">
                            <div class="d-flex align-items-center gap-2">
                                <div class="quick-icon text-success"
                                    style="background:linear-gradient(135deg, rgba(25,135,84,.14), rgba(25,135,84,.02))">
                                    <i class="bi bi-person-video3"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">لیست دانش‌آموزان</div>
                                    <div class="text-muted small">حضور و عملکرد</div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>

                        <a href="{{ route('teacher.reports.index') }}" class="quick-action">
                            <div class="d-flex align-items-center gap-2">
                                <div class="quick-icon text-info"
                                    style="background:linear-gradient(135deg, rgba(13,202,240,.18), rgba(13,202,240,.02))">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">گزارش‌ها و تحلیل‌ها</div>
                                    <div class="text-muted small">نمودار و پیشرفت</div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>

                        <a href="{{ route('teacher.profile') }}" class="quick-action">
                            <div class="d-flex align-items-center gap-2">
                                <div class="quick-icon text-secondary"
                                    style="background:linear-gradient(135deg, rgba(108,117,125,.16), rgba(108,117,125,.02))">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">پروفایل من</div>
                                    <div class="text-muted small">مدیریت حساب</div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>
                    </div>
                </div>

                {{-- System status --}}
                <div class="system-card fade-up">
                    <h2 class="h6 fw-bold mb-2">
                        <i class="bi bi-shield-check me-1 text-success"></i>
                        وضعیت کلی سیستم
                    </h2>
                    <p class="small mb-3 text-secondary" style="color:#94a3b8 !important;">
                        این بخش برای اعلان‌ها و یادآوری‌های مهم شماست.
                    </p>

                    <div class="d-grid gap-2">
                        <div class="system-bullet">
                            <i class="bi bi-bell-fill text-warning"></i>
                            <div>یادآوری: نتایج آزمون‌های تشریحی را بررسی کنید.</div>
                        </div>
                        <div class="system-bullet">
                            <i class="bi bi-stars text-info"></i>
                            <div>پیشنهاد: برای هر کلاس حداقل یک آزمون تمرینی تنظیم کنید.</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
