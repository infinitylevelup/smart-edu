@extends('layouts.app')
@section('title', 'Attemptهای دانش‌آموز')

@push('styles')
    <style>
        .card-soft {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .stat {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1rem;
            height: 100%
        }

        .badge-pill {
            border-radius: 999px;
            padding: .35rem .7rem;
            font-weight: 600
        }

        .tiny {
            font-size: .85rem
        }

        .muted {
            color: #6b7280
        }

        .progress-lg {
            height: 12px;
            border-radius: 999px;
        }

        .table thead th {
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">نتایج آزمون‌های دانش‌آموز</h4>
                <div class="text-muted small">
                    دانش‌آموز:
                    <span class="fw-semibold">{{ $student->phone ?? '—' }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-outline-secondary">
                    بازگشت به پروفایل
                </a>
            </div>
        </div>

        @php
            // attempts ممکنه paginate یا collection باشه
            $attemptsCol =
                $attempts instanceof \Illuminate\Pagination\LengthAwarePaginator
                    ? collect($attempts->items())
                    : collect($attempts);

            $attemptCount = $attemptsCol->count();
            $avgPercent = $attemptCount ? round($attemptsCol->avg('percent'), 2) : 0;
            $avgScore = $attemptCount ? round($attemptsCol->avg('score_obtained'), 2) : 0;

            $gradedCount = $attemptsCol->where('status', 'graded')->count();
            $submittedCount = $attemptsCol->where('status', 'submitted')->count();
        @endphp

        {{-- Summary Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">تعداد Attempt</div>
                    <div class="fs-3 fw-bold">{{ $attemptCount }}</div>
                    <div class="tiny muted mt-1">
                        graded: {{ $gradedCount }} • submitted: {{ $submittedCount }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">میانگین درصد</div>
                    <div class="fs-3 fw-bold">{{ $avgPercent }}%</div>
                    <div class="progress progress-lg mt-2">
                        <div class="progress-bar" style="width: {{ min(100, max(0, $avgPercent)) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">میانگین نمره</div>
                    <div class="fs-3 fw-bold">{{ $avgScore }}</div>
                    <div class="tiny muted mt-1">
                        بر اساس score_obtained
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">وضعیت کلی</div>
                    <div class="mt-2">
                        @if ($submittedCount > 0)
                            <span class="badge bg-warning text-dark badge-pill">
                                منتظر تصحیح تشریحی
                            </span>
                        @else
                            <span class="badge bg-success badge-pill">
                                همه تلاش‌ها نهایی شده‌اند
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Attempts Table --}}
        @if ($attemptCount)
            <div class="card card-soft">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>آزمون</th>
                                <th>نمره</th>
                                <th>درصد</th>
                                <th>وضعیت</th>
                                <th>ارسال</th>
                                <th class="text-end">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attempts as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>

                                    <td class="fw-semibold">
                                        {{ $a->exam->title ?? '—' }}
                                        <div class="tiny muted">
                                            سطح: {{ $a->exam->level ?? '—' }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $a->score_obtained ?? 0 }}
                                        /
                                        {{ $a->score_total ?? 0 }}
                                    </td>

                                    <td>
                                        <span class="fw-bold">{{ $a->percent ?? 0 }}%</span>
                                    </td>

                                    <td>
                                        @if ($a->status === 'graded')
                                            <span class="badge bg-success badge-pill">graded</span>
                                        @elseif($a->status === 'submitted')
                                            <span class="badge bg-warning text-dark badge-pill">submitted</span>
                                        @else
                                            <span class="badge bg-secondary badge-pill">{{ $a->status }}</span>
                                        @endif
                                    </td>

                                    <td class="tiny muted">
                                        {{ optional($a->submitted_at)->format('Y/m/d H:i') ?? '—' }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('teacher.attempts.show', $a->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            جزئیات / تصحیح
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($attempts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-3">
                    {{ $attempts->links() }}
                </div>
            @endif
        @else
            <div class="text-muted mt-3">
                هنوز تلاشی برای این دانش‌آموز ثبت نشده است.
            </div>
        @endif

    </div>
@endsection
