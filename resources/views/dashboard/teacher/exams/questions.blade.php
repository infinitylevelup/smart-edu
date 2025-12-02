@extends('layouts.app')
@section('title', 'مدیریت سوال‌های آزمون')

@push('styles')
    <style>
        .card-soft {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .exam-chip {
            background: #eef2ff;
            padding: .25rem .6rem;
            border-radius: 999px;
            font-weight: 700;
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

        .q-row:hover {
            background: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header (Questions Management) --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-question-circle me-1 text-primary"></i>
                    مدیریت سوال‌های آزمون
                </h4>

                <div class="text-muted small">
                    آزمون:
                    <span class="exam-chip">{{ $exam->title }}</span>

                    {{-- ✅ subject اگر وجود داشت نمایش بده، نبود مشکلی نیست --}}
                    @if ($exam->relationLoaded('subject') || $exam->subject)
                        <span class="mx-1 text-muted">•</span>
                        موضوع:
                        @if ($exam->subject)
                            <span class="fw-semibold">
                                {{ $exam->subject->title ?? $exam->subject->name }}
                            </span>
                        @else
                            <span class="text-warning">نامشخص</span>
                        @endif
                    @endif

                    @if ($exam->classroom)
                        <span class="mx-1 text-muted">•</span>
                        کلاس:
                        <span class="fw-semibold">
                            {{ $exam->classroom->title ?? $exam->classroom->name }}
                        </span>
                    @endif

                    <span class="mx-1 text-muted">•</span>
                    {{-- ✅ duration فول‌بک --}}
                    مدت: {{ $exam->duration ?? ($exam->duration_minutes ?? '—') }} دقیقه
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('teacher.exams.edit', $exam) }}"
                    class="btn btn-outline-warning d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-pencil-square"></i>
                    ویرایش آزمون
                </a>

                <a href="{{ route('teacher.exams.questions.create', $exam) }}"
                    class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-plus-circle"></i>
                    سوال جدید
                </a>

                <a href="{{ route('teacher.exams.index') }}"
                    class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $questions = $questions ?? ($exam->questions ?? collect());
            $totalScore = $questions->sum('score');
        @endphp

        {{-- Summary --}}
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">تعداد سوال‌ها</div>
                        <div class="fs-4 fw-bold">{{ $questions->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">مجموع امتیاز</div>
                        <div class="fs-4 fw-bold">{{ $totalScore }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">وضعیت آزمون</div>
                        <div class="mt-2">
                            {{-- ✅ is_published → is_active --}}
                            @if ($exam->is_active)
                                <span class="badge bg-success badge-pill">فعال</span>
                            @else
                                <span class="badge bg-secondary badge-pill">غیرفعال</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions Table --}}
        <div class="card card-soft">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>متن سوال</th>
                            <th>نوع</th>
                            <th>امتیاز</th>
                            <th>پاسخ صحیح</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($questions as $q)
                            @php
                                $correct = null;
                                if ($q->type === 'mcq') {
                                    $correct = strtoupper($q->correct_option ?? '');
                                } elseif ($q->type === 'true_false') {
                                    $correct = $q->correct_tf ?? false ? 'صحیح' : 'غلط';
                                } elseif ($q->type === 'fill_blank') {
                                    $correct = is_array($q->correct_answer)
                                        ? implode(' , ', $q->correct_answer)
                                        : $q->correct_answer;
                                } else {
                                    $correct = '— (تشریحی)';
                                }
                            @endphp

                            <tr class="q-row">
                                {{-- شماره سوال --}}
                                <td class="fw-semibold">{{ $loop->iteration }}</td>

                                <td style="max-width:520px">
                                    {{-- ✅ question_text → question --}}
                                    {{ \Illuminate\Support\Str::limit($q->question, 120) }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border badge-pill">
                                        {{ str_replace('_', ' ', $q->type) }}
                                    </span>
                                </td>

                                <td class="fw-bold">{{ $q->score }}</td>
                                <td class="tiny muted">{{ $correct }}</td>

                                <td class="text-end">
                                    <a href="{{ route('teacher.exams.questions.edit', [$exam, $q]) }}"
                                        class="btn btn-sm btn-outline-warning">
                                        ویرایش
                                    </a>

                                    <form method="POST"
                                        action="{{ route('teacher.exams.questions.destroy', [$exam, $q]) }}"
                                        class="d-inline" onsubmit="return confirm('سوال حذف شود؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    هنوز سوالی برای این آزمون ثبت نشده است.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination (اگر paginate استفاده می‌کنی) --}}
        @if ($questions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-3">
                {{ $questions->links() }}
            </div>
        @endif

    </div>
@endsection
