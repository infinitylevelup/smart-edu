@extends('layouts.app')
@section('title', 'جزئیات آزمون')

@push('styles')
    @include('dashboard.teacher.exams.show-style')
@endpush

@section('content')
    <div class="container py-4">

        {{-- Hero Header --}}
        <div class="hero card-soft p-3 p-md-4 mb-4">
            <div class="hero-orb"></div>
            <div class="hero-orb2"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-clipboard-check me-1 text-primary"></i>
                        {{ $exam->title }}
                    </h4>

                    <div class="text-muted small d-flex flex-wrap align-items-center gap-2 mt-2">
                        <span class="mx-1">•</span>

                        نوع:
                        @php
                            $typeClasses = [
                                'public' => 'type-public',
                                'class' => 'type-class',
                                'class_single' => 'type-class-single',
                                'class_comprehensive' => 'type-class-comprehensive'
                            ];
                            $typeClass = $typeClasses[$exam->exam_type] ?? 'type-public';
                        @endphp
                        <span class="exam-type {{ $typeClass }}">
                            <i class="fas {{ $exam->type_icon }}"></i>
                            {{ $exam->type_fa }}
                        </span>

                        کلاس:
                        @if ($exam->classroom)
                            <span class="chip">
                                <i class="bi bi-people-fill"></i>
                                {{ $exam->classroom->title ?? $exam->classroom->name }}
                            </span>
                        @else
                            <span class="text-warning">نامشخص</span>
                        @endif

                        <span class="mx-1">•</span>

                        مدت:
                        <strong>{{ $exam->duration_minutes ?? ($exam->duration ?? '—') }} دقیقه</strong>

                        <span class="mx-1">•</span>

                        وضعیت:
                        @if ($exam->is_published)
                            <span class="badge-status bg-success">منتشر شده</span>
                        @else
                            <span class="badge-status bg-secondary">پیش‌نویس</span>
                        @endif
                    </div>

                    @if (!empty($exam->description))
                        <div class="tiny muted mt-3 p-3 bg-light rounded-2">
                            <i class="bi bi-card-text me-1"></i>
                            {{ $exam->description }}
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('teacher.exams.edit', $exam) }}"
                       class="btn btn-outline-warning btn-action">
                        <i class="bi bi-pencil-square"></i>
                        ویرایش آزمون
                    </a>

                    <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                       class="btn btn-primary btn-action">
                        <i class="bi bi-question-circle"></i>
                        مدیریت سوال‌ها
                    </a>

                    <a href="{{ route('teacher.exams.index') }}"
                       class="btn btn-outline-secondary btn-action">
                        <i class="bi bi-arrow-right"></i>
                        بازگشت
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $questions = $exam->questions ?? collect();
            $totalScore = $questions->sum('score');
        @endphp

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-soft h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center py-4">
                        <div class="text-muted small mb-2">تعداد سوال‌ها</div>
                        <div class="fs-2 fw-bold text-primary">{{ $questions->count() }}</div>
                        <div class="tiny muted mt-2">سوال ثبت شده</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center py-4">
                        <div class="text-muted small mb-2">مجموع امتیاز</div>
                        <div class="fs-2 fw-bold text-success">{{ $totalScore }}</div>
                        <div class="tiny muted mt-2">امتیاز کل</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center py-4">
                        <div class="text-muted small mb-2">شناسه آزمون</div>
                        <div class="fs-2 fw-bold text-dark">#{{ $exam->id }}</div>
                        <div class="tiny muted mt-2">کد یکتا</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions Preview --}}
        <div class="card card-soft">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center py-3">
                <span>
                    <i class="bi bi-list-check me-1 text-primary"></i>
                    سوال‌های آزمون
                </span>

                <a href="{{ route('teacher.exams.questions.create', $exam) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus"></i> افزودن سوال
                </a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>متن سوال</th>
                            <th width="140">نوع</th>
                            <th width="120">امتیاز</th>
                            <th width="200">پاسخ صحیح</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $q)
                            @php
                                $type = $q->question_type;
                                $correct = null;

                                if ($type === 'mcq') {
                                    $correct = is_array($q->correct_answer)
                                        ? strtoupper($q->correct_answer['correct_option'] ?? '')
                                        : '—';
                                } elseif ($type === 'true_false') {
                                    $val = is_array($q->correct_answer)
                                        ? ($q->correct_answer['value'] ?? null)
                                        : null;
                                    $correct = ($val === true || $val === 1 || $val === '1') ? 'True' : 'False';
                                } elseif ($type === 'fill_blank') {
                                    $correct = is_array($q->correct_answer)
                                        ? implode(', ', $q->correct_answer)
                                        : ($q->correct_answer ?? '—');
                                } else {
                                    $correct = '— (تشریحی)';
                                }
                            @endphp

                            <tr class="q-row" data-id="{{ $q->id }}">
                                <td class="fw-semibold text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="question-content">
                                        {{ \Illuminate\Support\Str::limit($q->content, 140) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1">
                                        {{ str_replace('_', ' ', $q->question_type) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-success">{{ $q->score }}</td>
                                <td class="tiny muted correct-answer" title="{{ $correct }}">
                                    {{ \Illuminate\Support\Str::limit($correct, 30) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-question-circle display-4 text-muted mb-3"></i>
                                        <p class="h5 mb-2">هنوز هیچ سوالی ثبت نشده است.</p>
                                        <p class="small mb-4">با افزودن سوال‌ها، آزمون را کامل کنید.</p>
                                        <a href="{{ route('teacher.exams.questions.create', $exam) }}"
                                           class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i>
                                            اولین سوال را ایجاد کنید
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($questions->count())
                <div class="card-footer bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center small text-muted">
                        <span>
                            <i class="bi bi-info-circle me-1"></i>
                            برای مشاهده جزئیات هر سوال، روی آن کلیک کنید.
                        </span>
                        <span>
                            مجموع: {{ $questions->count() }} سوال
                        </span>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    @include('dashboard.teacher.exams.show-script')
@endpush