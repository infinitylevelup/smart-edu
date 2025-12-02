@extends('layouts.app')
@section('title', 'جزئیات Attempt دانش‌آموز')

@push('styles')
    <style>
        .card-soft {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .q-card {
            border: 1px solid #eef2f7;
            border-radius: 1rem;
            transition: .15s ease;
        }

        .q-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, .05);
        }

        .ans-box {
            background: #f9fafb;
            border-radius: .75rem;
            padding: .75rem 1rem;
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

        .sticky-actions {
            position: sticky;
            bottom: 10px;
            z-index: 20;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">جزئیات تلاش (Attempt)</h4>
                <div class="text-muted small">
                    آزمون:
                    <span class="fw-semibold">{{ $attempt->exam->title ?? '—' }}</span>
                    • دانش‌آموز:
                    <span class="fw-semibold">{{ $attempt->student->phone ?? '—' }}</span>
                    • ارسال:
                    <span class="fw-semibold">
                        {{ optional($attempt->submitted_at)->format('Y/m/d H:i') ?? '—' }}
                    </span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                    بازگشت
                </a>
                <a href="{{ route('teacher.students.show', $attempt->student_id) }}" class="btn btn-outline-primary">
                    پروفایل دانش‌آموز
                </a>
            </div>
        </div>

        @php
            // ✅ answers واقعی (AttemptAnswer) از رابطه
            $answers = $attempt->answers()->with('question')->get();

            $totalScore = $attempt->score_total ?? ($attempt->exam?->questions?->sum('score') ?? 0);

            $obtained = $attempt->score_obtained ?? 0;
            $percent = $attempt->percent ?? 0;
            $status = $attempt->status ?? 'submitted';

            $isPending = $status === 'submitted';

            $essayPendingCount = $answers
                ->filter(function ($a) {
                    return $a->question?->type === 'essay' && $a->graded_at === null;
                })
                ->count();
        @endphp

        {{-- Attempt Summary --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">نمره نهایی</div>
                        <div class="fs-3 fw-bold">
                            {{ $obtained }}
                            <span class="fs-6 muted">/ {{ $totalScore }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">درصد</div>
                        <div class="fs-3 fw-bold">{{ $percent }}%</div>
                        <div class="progress progress-lg mt-2">
                            <div class="progress-bar" style="width: {{ min(100, max(0, $percent)) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft">
                    <div class="card-body">
                        <div class="text-muted small">وضعیت</div>
                        <div class="mt-2">
                            @if ($isPending)
                                <span class="badge bg-warning text-dark badge-pill">
                                    در انتظار تصحیح (Essay باقی‌مانده: {{ $essayPendingCount }})
                                </span>
                            @else
                                <span class="badge bg-success badge-pill">
                                    تصحیح شده
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Questions & Answers --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="fw-bold mb-0">سوال‌ها و پاسخ‌ها</h5>
            <span class="tiny muted">برای باز شدن جزئیات روی «نمایش» بزن</span>
        </div>

        @if ($answers->count())
            <div class="row g-3">

                @foreach ($answers as $i => $ans)
                    @php
                        $q = $ans->question;
                        $type = $q?->type ?? '-';
                        $questionScore = $q?->score ?? 0;

                        // decode student answer json
                        $studentRaw = $ans->answer;
                        $studentAnswer = $studentRaw;

                        if (is_string($studentRaw)) {
                            $decoded = json_decode($studentRaw, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $studentAnswer = $decoded;
                            }
                        }

                        $isEssay = $type === 'essay';
                        $isCorrect = (int) ($ans->is_correct ?? 0) === 1;

                        $awarded = $ans->score_awarded ?? 0;
                        $gradedAt = $ans->graded_at;

                        // correct answer display
                        $correctDisplay = null;
                        if ($type === 'mcq') {
                            $correctDisplay = strtoupper($q->correct_option ?? '');
                        } elseif ($type === 'true_false') {
                            $correctDisplay = $q->correct_tf ?? false ? 'True' : 'False';
                        } elseif ($type === 'fill_blank') {
                            $correctDisplay = is_array($q->correct_answer)
                                ? implode(' , ', $q->correct_answer)
                                : $q->correct_answer;
                        }

                        $mcqOptions = [
                            'a' => $q->option_a,
                            'b' => $q->option_b,
                            'c' => $q->option_c,
                            'd' => $q->option_d,
                        ];
                    @endphp

                    <div class="col-12">
                        <div class="q-card p-3">

                            {{-- Header line --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-light text-dark border badge-pill">
                                        سوال {{ $i + 1 }}
                                    </span>

                                    <span class="badge bg-secondary badge-pill">
                                        {{ str_replace('_', ' ', $type) }}
                                    </span>

                                    <span class="badge bg-info badge-pill">
                                        امتیاز: {{ $awarded }} / {{ $questionScore }}
                                    </span>

                                    @if ($isEssay)
                                        @if (!$gradedAt)
                                            <span class="badge bg-warning text-dark badge-pill">
                                                نیازمند تصحیح
                                            </span>
                                        @else
                                            <span class="badge bg-success badge-pill">
                                                تصحیح شد
                                            </span>
                                        @endif
                                    @else
                                        @if ($isCorrect)
                                            <span class="badge bg-success badge-pill">درست</span>
                                        @else
                                            <span class="badge bg-danger badge-pill">غلط</span>
                                        @endif
                                    @endif
                                </div>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#q{{ $ans->id }}">
                                    نمایش
                                </button>
                            </div>

                            <div class="collapse" id="q{{ $ans->id }}">

                                {{-- Question --}}
                                <div class="mb-3">
                                    <div class="fw-semibold mb-1">صورت سوال:</div>
                                    <div class="ans-box">{{ $q?->question_text ?? '—' }}</div>
                                </div>

                                {{-- MCQ options --}}
                                @if ($type === 'mcq')
                                    <div class="mb-3">
                                        <div class="fw-semibold mb-1">گزینه‌ها:</div>
                                        <div class="row g-2">
                                            @foreach ($mcqOptions as $key => $val)
                                                @if ($val !== null)
                                                    <div class="col-md-6">
                                                        <div
                                                            class="ans-box tiny d-flex justify-content-between align-items-center">
                                                            <span>{{ strtoupper($key) }}) {{ $val }}</span>

                                                            @if (($studentAnswer ?? '') == $key)
                                                                <span class="badge bg-primary">انتخاب دانش‌آموز</span>
                                                            @endif

                                                            @if (($q->correct_option ?? '') == $key)
                                                                <span class="badge bg-success">گزینه صحیح</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Student / Correct --}}
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="fw-semibold mb-1">پاسخ دانش‌آموز:</div>
                                        <div class="ans-box">
                                            @if (is_array($studentAnswer))
                                                <ul class="mb-0">
                                                    @foreach ($studentAnswer as $v)
                                                        <li>{{ $v }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ $studentAnswer ?? '—' }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="fw-semibold mb-1">پاسخ صحیح:</div>
                                        <div class="ans-box">
                                            @if ($isEssay)
                                                <span class="muted">تشریحی است و پاسخ مرجع ندارد.</span>
                                            @else
                                                {{ $correctDisplay ?? '—' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Explanation --}}
                                @if (!empty($q?->explanation))
                                    <div class="mt-3">
                                        <div class="fw-semibold mb-1">توضیح سوال:</div>
                                        <div class="ans-box tiny">{{ $q->explanation }}</div>
                                    </div>
                                @endif

                                {{-- Essay Grading Form --}}
                                @if ($isEssay)
                                    <hr class="my-3">

                                    <form method="POST"
                                        action="{{ route('teacher.attempts.answers.grade', [
                                            'attempt' => $attempt->id,
                                            'answer' => $ans->id,
                                        ]) }}"
                                        class="row g-3">

                                        @csrf

                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">نمره این سوال</label>
                                            <input type="number" name="score_awarded" class="form-control" min="0"
                                                max="{{ $questionScore }}" step="1"
                                                value="{{ old('score_awarded', $awarded) }}" required>
                                            <div class="tiny muted mt-1">
                                                حداکثر: {{ $questionScore }}
                                            </div>
                                        </div>

                                        <div class="col-md-9">
                                            <label class="form-label fw-semibold">بازخورد معلم</label>
                                            <textarea name="teacher_feedback" class="form-control" rows="2" placeholder="بازخورد کوتاه برای دانش‌آموز...">{{ old('teacher_feedback', $ans->teacher_feedback) }}</textarea>
                                        </div>

                                        <div class="col-12 d-flex align-items-center gap-2">
                                            <button class="btn btn-success">
                                                ثبت نمره تشریحی
                                            </button>

                                            @if ($gradedAt)
                                                <span class="tiny muted">
                                                    آخرین تصحیح:
                                                    {{ \Carbon\Carbon::parse($gradedAt)->format('Y/m/d H:i') }}
                                                </span>
                                            @endif
                                        </div>

                                    </form>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-muted mt-3">پاسخی برای نمایش وجود ندارد.</div>
        @endif

    </div>
@endsection
