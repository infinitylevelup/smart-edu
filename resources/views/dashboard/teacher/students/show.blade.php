@extends('layouts.app')
@section('title', 'پروفایل دانش‌آموز')

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
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">پروفایل دانش‌آموز</h4>
                <div class="text-muted small">
                    شناسه: <span class="fw-semibold">#{{ $student->id }}</span>
                    • شماره: <span class="fw-semibold">{{ $student->phone }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    بازگشت
                </a>

                {{-- لینک رفتن به لیست Attemptها --}}
                <a href="{{ route('teacher.students.attempts', $student->id) }}" class="btn btn-primary">
                    نتایج آزمون‌ها
                </a>

                {{--
            |--------------------------------------------------------------------------
            | NOTE:
            |--------------------------------------------------------------------------
            | اگر اسم روتت فرق دارد، اینجا اصلاح کن.
            | مثلا:
            | route('teacher.students.attempts.index', $student->id)
            | یا
            | route('teacher.attempts.student', $student->id)
            |--------------------------------------------------------------------------
            --}}
            </div>
        </div>


        @php
            // اگر attempts از کنترلر نیومده بود، خالی در نظر بگیر
            $attempts = $attempts ?? collect();

            $attemptCount = $attempts->count();
            $avgPercent = $attemptCount ? round($attempts->avg('percent'), 2) : 0;

            $lastAttempt = $attempts->sortByDesc('submitted_at')->first();

            $gradedCount = $attempts->where('status', 'graded')->count();
            $pendingCount = $attempts->where('status', 'submitted')->count(); // منتظر تصحیح تشریحی
        @endphp

        {{-- Summary Stats --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">تعداد تلاش‌ها</div>
                    <div class="fs-3 fw-bold">{{ $attemptCount }}</div>
                    <div class="tiny muted mt-1">
                        graded: {{ $gradedCount }} • submitted: {{ $pendingCount }}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">میانگین درصد</div>
                    <div class="fs-3 fw-bold">{{ $avgPercent }}%</div>
                    <div class="progress mt-2" style="height:12px;border-radius:999px">
                        <div class="progress-bar" style="width: {{ min(100, max(0, $avgPercent)) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">آخرین تلاش</div>
                    @if ($lastAttempt)
                        <div class="fw-bold fs-5 mt-1">
                            {{ $lastAttempt->percent }}%
                        </div>
                        <div class="tiny muted mt-1">
                            تاریخ: {{ optional($lastAttempt->submitted_at)->format('Y/m/d H:i') ?? '—' }}
                        </div>
                    @else
                        <div class="muted mt-2">هنوز تلاشی ثبت نشده</div>
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat">
                    <div class="text-muted small">وضعیت کلی</div>
                    <div class="mt-2">
                        @if ($pendingCount > 0)
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


        {{-- Shared Classrooms --}}
        <div class="card card-soft mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">کلاس‌های مشترک شما با این دانش‌آموز</h6>

                @php
                    $sharedClassrooms = $sharedClassrooms ?? collect();
                @endphp

                @if ($sharedClassrooms->count())
                    <div class="row g-2">
                        @foreach ($sharedClassrooms as $c)
                            <div class="col-md-4">
                                <div class="ans-box d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $c->title }}</div>
                                        <div class="tiny muted">{{ $c->subject ?? '—' }} • {{ $c->grade ?? '—' }}</div>
                                    </div>
                                    <a href="{{ route('teacher.classes.show', $c->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        مشاهده کلاس
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted">کلاسی مشترک یافت نشد.</div>
                @endif
            </div>
        </div>


        {{-- Recent Attempts Preview --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">آخرین تلاش‌ها</h5>
            <a href="{{ route('teacher.students.attempts', $student->id) }}" class="tiny text-decoration-none">
                مشاهده همه →
            </a>
        </div>

        @if ($attempts->count())
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
                                <th>تاریخ ارسال</th>
                                <th class="text-end">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attempts->sortByDesc('submitted_at')->take(5) as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>
                                    <td class="fw-semibold">
                                        {{ $a->exam->title ?? '—' }}
                                    </td>
                                    <td>
                                        {{ $a->score_obtained ?? 0 }} / {{ $a->score_total ?? 0 }}
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
        @else
            <div class="text-muted mt-3">هنوز تلاشی برای این دانش‌آموز ثبت نشده است.</div>
        @endif


        {{--
    |--------------------------------------------------------------------------
    | CONTROLLER EXPECTATION (for your reference)
    |--------------------------------------------------------------------------
    | در TeacherStudentController@show بهتر است این‌ها را بفرستی:
    |
    | $student = User::students()->findOrFail($id);
    |
    | // کلاس‌های مشترک این معلم با دانش‌آموز
    | $sharedClassrooms = Classroom::where('teacher_id', auth()->id())
    |     ->whereHas('students', fn($q) => $q->where('users.id', $student->id))
    |     ->get();
    |
    | // Attemptهای دانش‌آموز در آزمون‌های همین معلم
    | $attempts = Attempt::where('student_id', $student->id)
    |    ->whereHas('exam', fn($q) => $q->where('teacher_id', auth()->id()))
    |    ->with('exam')
    |    ->latest('submitted_at')
    |    ->get();
    |
    | return view('dashboard.teacher.students.show',
    |     compact('student','sharedClassrooms','attempts'));
    |--------------------------------------------------------------------------
    --}}
    </div>
@endsection
