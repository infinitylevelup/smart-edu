@extends('layouts.app')
@section('title', 'Counselor Dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h4 mb-1">داشبورد مشاور</h1>
                <p class="text-muted mb-0">مدیریت دانش‌آموزها و تحلیل آزمون‌ها</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('counselor.students.index') }}" class="btn btn-primary">
                    <i class="bi bi-people-fill me-1"></i> دانش‌آموزها
                </a>
                <a href="{{ route('counselor.learning-paths.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-signpost-split me-1"></i> مسیرهای پیشنهادی
                </a>
                <a href="{{ route('counselor.reports.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-bar-chart-line me-1"></i> گزارش‌ها
                </a>
            </div>
        </div>

        {{-- Stats row --}}
        @php $stats = $stats ?? []; @endphp
        <div class="row g-3 mb-4">

            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">تعداد دانش‌آموزها</div>
                            <div class="fs-4 fw-bold">{{ $stats['students_count'] ?? 0 }}</div>
                        </div>
                        <i class="bi bi-people fs-2 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">تحلیل‌های در انتظار</div>
                            <div class="fs-4 fw-bold">{{ $stats['pending_analyses'] ?? 0 }}</div>
                        </div>
                        <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">تحلیل‌های کامل‌شده</div>
                            <div class="fs-4 fw-bold">{{ $stats['completed_analyses'] ?? 0 }}</div>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Quick section --}}
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0">آخرین دانش‌آموزهای ثبت‌شده</h2>
                        <a class="small text-decoration-none" href="{{ route('counselor.students.index') }}">
                            مشاهده همه
                        </a>
                    </div>
                    <div class="card-body text-muted small">
                        این بخش در فاز بعدی با لیست واقعی پر می‌شود.
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h2 class="h6 mb-0">Attemptهای آماده تحلیل</h2>
                    </div>
                    <div class="card-body text-muted small">
                        این بخش در فاز بعدی با attemptهای بدون تحلیل پر می‌شود.
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
