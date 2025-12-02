@extends('layouts.app')
@section('title', 'جزئیات آزمون')

@push('styles')
    <style>
        /* ------------------------------------------------------------------
           Exam Show (Teacher)
           UI سبک، شفاف و هماهنگ با Bootstrap 5 + داشبورد شما
        ------------------------------------------------------------------ */

        .card-soft {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            background: #fff;
        }

        .chip {
            background: #eef2ff;
            padding: .25rem .6rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .tiny {
            font-size: .85rem;
        }

        .muted {
            color: #6b7280;
        }

        .hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #0d6efd10, #20c99710);
            border: 1px dashed #e5e7eb;
        }

        .hero-orb {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            right: -70px;
            top: -70px;
            background: radial-gradient(circle, #0d6efd22, transparent 60%);
            filter: blur(0px);
        }

        .hero-orb2 {
            position: absolute;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            left: -50px;
            bottom: -50px;
            background: radial-gradient(circle, #20c99722, transparent 60%);
        }

        .q-row:hover {
            background: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- =========================================================
         Header / Hero
         متغیر اصلی این صفحه فقط $exam است
    ========================================================== --}}
        <div class="hero card-soft p-3 p-md-4 mb-4">
            <div class="hero-orb"></div>
            <div class="hero-orb2"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-clipboard-check me-1 text-primary"></i>
                        {{ $exam->title }}
                    </h4>

                    <div class="text-muted small">
                        {{-- کلاس مرتبط --}}
                        کلاس:
                        @if ($exam->classroom)
                            <span class="chip">{{ $exam->classroom->title ?? $exam->classroom->name }}</span>
                        @else
                            <span class="text-warning">نامشخص</span>
                        @endif

                        <span class="mx-1">•</span>

                        {{-- مدت آزمون --}}
                        مدت: {{ $exam->duration }} دقیقه

                        <span class="mx-1">•</span>

                        {{-- وضعیت انتشار --}}
                        وضعیت:
                        @if ($exam->is_published)
                            <span class="badge bg-success">منتشر شده</span>
                        @else
                            <span class="badge bg-secondary">پیش‌نویس</span>
                        @endif
                    </div>

                    {{-- توضیح آزمون --}}
                    @if (!empty($exam->description))
                        <div class="tiny muted mt-2">
                            {{ $exam->description }}
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 flex-wrap">
                    {{-- ویرایش آزمون --}}
                    <a href="{{ route('teacher.exams.edit', $exam) }}"
                        class="btn btn-outline-warning d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-pencil-square"></i>
                        ویرایش آزمون
                    </a>

                    {{-- مدیریت سوالات --}}
                    <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                        class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-question-circle"></i>
                        مدیریت سوال‌ها
                    </a>

                    {{-- بازگشت به لیست آزمون‌ها --}}
                    <a href="{{ route('teacher.exams.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-right"></i>
                        بازگشت
                    </a>
                </div>
            </div>
        </div>

        {{-- پیام موفقیت --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        {{-- =========================================================
         Summary Cards
    ========================================================== --}}
        @php
            $questions = $exam->questions ?? collect();
            $totalScore = $questions->sum('score');
        @endphp

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
                        <div class="text-muted small">شناسه آزمون</div>
                        <div class="fs-5 fw-bold">#{{ $exam->id }}</div>
                    </div>
                </div>
            </div>
        </div>


        {{-- =========================================================
         Questions Preview (read-only)
         اگر سوال نداریم، CTA برای ساخت سوال نمایش می‌دهیم
    ========================================================== --}}
        <div class="card card-soft">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-list-check me-1 text-primary"></i>
                    سوال‌های آزمون
                </span>

                <a href="{{ route('teacher.exams.questions.create', $exam) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> افزودن سوال
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>متن سوال</th>
                            <th>نوع</th>
                            <th>امتیاز</th>
                            <th>پاسخ صحیح</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $q)
                            @php
                                // نمایش خلاصه پاسخ صحیح (طبق ساختار فعلی Question ها)
                                $correct = null;
                                if ($q->type === 'mcq') {
                                    $correct = strtoupper($q->correct_option ?? '');
                                } elseif ($q->type === 'true_false') {
                                    $correct = $q->correct_tf ?? false ? 'True' : 'False';
                                } elseif ($q->type === 'fill_blank') {
                                    $correct = is_array($q->correct_answer)
                                        ? implode(' , ', $q->correct_answer)
                                        : $q->correct_answer ?? '—';
                                } else {
                                    $correct = '— (تشریحی)';
                                }
                            @endphp

                            <tr class="q-row">
                                <td class="fw-semibold">{{ $loop->iteration }}</td>
                                <td style="max-width:520px">
                                    {{ \Illuminate\Support\Str::limit($q->question_text, 140) }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill">
                                        {{ str_replace('_', ' ', $q->type) }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $q->score }}</td>
                                <td class="tiny muted">{{ $correct }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    هنوز هیچ سوالی برای این آزمون ثبت نشده است.
                                    <div class="mt-3">
                                        <a href="{{ route('teacher.exams.questions.create', $exam) }}"
                                            class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i>
                                            اولین سوال را بساز
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
