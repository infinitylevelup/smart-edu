@extends('layouts.app')

@section('title', 'پنل دانش‌آموز - داشبورد')

@section('content')
    <div class="container-fluid">

        {{-- سربرگ داشبورد --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h4 mb-1">داشبورد دانش‌آموز</h1>
                <p class="text-muted mb-0">
                    وضعیت آزمون‌ها، پیشرفت و تحلیل‌های شما در یک نگاه.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('student.exams.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-play ms-1"></i>
                    شروع آزمون
                </a>

                {{-- ✅ اصلاح شد: به‌جای results → reports --}}
                <a href="{{ route('student.reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-chart-simple ms-1"></i>
                    گزارش‌ها و تحلیل‌ها
                </a>
            </div>
        </div>

        {{-- ردیف آمار کلی --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">آزمون‌های در دسترس</div>
                            <div class="fs-4 fw-bold">
                                {{ $stats['available_exams'] ?? '—' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fa-solid fa-file-circle-check text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">آزمون‌های انجام‌شده</div>
                            <div class="fs-4 fw-bold">
                                {{ $stats['taken_exams'] ?? '—' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fa-solid fa-square-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">میانگین نمره</div>
                            <div class="fs-4 fw-bold">
                                {{ $stats['avg_score'] ?? '—' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fa-solid fa-gauge-high text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small mb-1">سطح فعلی</div>
                            <div class="fs-4 fw-bold">
                                {{ $stats['current_level'] ?? '—' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fa-solid fa-layer-group text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ردیف دوم: آزمون‌های پیشنهادی + آخرین گزارش‌ها --}}
        <div class="row g-3">

            {{-- آزمون‌های پیشنهادی --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0">آزمون‌های پیشنهادی</h2>
                        <a href="{{ route('student.exams.index') }}" class="text-decoration-none small">
                            مشاهده همه
                            <i class="fa-solid fa-arrow-left-long ms-1"></i>
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @php $recommendedExams = $recommendedExams ?? []; @endphp

                        @if (count($recommendedExams))
                            <div class="list-group list-group-flush">
                                @foreach ($recommendedExams as $exam)
                                    <a href="{{ route('student.exams.show', $exam->id ?? $exam['id']) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $exam->title ?? ($exam['title'] ?? 'آزمون بدون عنوان') }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                درس:
                                                {{ $exam->subject ?? ($exam['subject'] ?? 'نامشخص') }}
                                                &nbsp; • &nbsp;
                                                سطح:
                                                {{ $exam->level ?? ($exam['level'] ?? 'نامشخص') }}
                                            </div>
                                        </div>

                                        <span class="badge rounded-pill text-bg-light">
                                            {{ $exam->duration ?? ($exam['duration'] ?? 0) }} دقیقه
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted small">
                                فعلاً آزمون پیشنهادی برای شما موجود نیست.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ✅ آخرین گزارش‌ها / تحلیل‌ها (به‌جای results) --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0">آخرین گزارش‌ها</h2>

                        <a href="{{ route('student.reports.index') }}" class="text-decoration-none small">
                            مشاهده کامل
                            <i class="fa-solid fa-arrow-left-long ms-1"></i>
                        </a>
                    </div>

                    <div class="card-body p-0">
                        @php $recentReports = $recentReports ?? []; @endphp

                        @if (count($recentReports))
                            <div class="list-group list-group-flush">
                                @foreach ($recentReports as $rep)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $rep->title ?? ($rep['title'] ?? 'گزارش') }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                تاریخ:
                                                {{ \Illuminate\Support\Carbon::parse($rep->created_at ?? ($rep['created_at'] ?? now()))->format('Y/m/d') }}
                                            </div>
                                        </div>
                                        <span class="badge rounded-pill text-bg-light">
                                            مشاهده
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted small">
                                هنوز گزارشی ثبت نشده است.
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- میانبرها --}}
        <div class="row g-3 mt-1">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h6 mb-3">میانبرهای سریع</h2>

                        <div class="row g-2">
                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('student.exams.index') }}"
                                    class="btn btn-light w-100 d-flex align-items-center justify-content-between">
                                    <span>آزمون‌ها</span>
                                    <i class="fa-solid fa-file-pen text-primary"></i>
                                </a>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                {{-- ✅ اصلاح شد --}}
                                <a href="{{ route('student.reports.index') }}"
                                    class="btn btn-light w-100 d-flex align-items-center justify-content-between">
                                    <span>گزارش‌ها</span>
                                    <i class="fa-solid fa-chart-line text-success"></i>
                                </a>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('student.profile') }}"
                                    class="btn btn-light w-100 d-flex align-items-center justify-content-between">
                                    <span>پروفایل</span>
                                    <i class="fa-solid fa-user-gear text-info"></i>
                                </a>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <a href="{{ route('student.profile') }}"
                                    class="btn btn-light w-100 d-flex align-items-center justify-content-between">
                                    <span>پشتیبانی</span>
                                    <i class="fa-solid fa-headset text-warning"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
