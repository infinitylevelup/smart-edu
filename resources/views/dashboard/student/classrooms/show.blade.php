@extends('layouts.student-app')

@section('title', $classroom->title ?? 'کلاس')

@section('content')
    @php
        $title = $classroom->title ?? ($classroom->name ?? 'کلاس بدون عنوان');
        $joinCode = $classroom->join_code ?? null;

        $teacher = $classroom->teacher ?? null;
        $teacherName = $teacher->display_name ?? ($teacher->name ?? 'نامشخص');

        $gradeTitle = $classroom->grade?->title ?? null;

        $typeLabel = ($classroom->classroom_type ?? 'single') === 'comprehensive' ? 'کلاس جامع' : 'کلاس تک';
        $activeLabel = $classroom->is_active ? 'کلاس فعال' : 'کلاس غیرفعال';

        $exams = $classroom->exams ?? collect();
    @endphp

    <section id="classroom-show" class="mobile-section active">
        <div class="cshow-wrap">

            {{-- Header --}}
            <div class="cshow-header-card">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                    <div>
                        <div class="cshow-title">{{ $title }}</div>
                        <div class="cshow-meta">
                            <span class="badge bg-light text-dark border">{{ $typeLabel }}</span>

                            @if($gradeTitle)
                                <span class="mx-1">•</span>
                                <span class="text-muted">پایه:</span> {{ $gradeTitle }}
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-end gap-2">
                        @if($joinCode)
                            <span class="cshow-chip">
                                <i class="bi bi-key-fill text-primary"></i>
                                {{ $joinCode }}
                            </span>
                        @endif

                        <span class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $activeLabel }}
                        </span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-3">
                    @if (!empty($classroom->description))
                        <div class="small text-muted">{{ $classroom->description }}</div>
                    @else
                        <div class="small text-muted">برای این کلاس توضیحی ثبت نشده است.</div>
                    @endif
                </div>

                {{-- Teacher --}}
                <div class="mt-3 d-flex align-items-center gap-2">
                    <div class="cshow-avatar">
                        <i class="bi bi-person-badge text-secondary"></i>
                    </div>
                    <div>
                        <div class="cshow-tiny text-muted">معلم کلاس</div>
                        <div class="fw-semibold">{{ $teacherName }}</div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <div class="cshow-pill">
                        <i class="bi bi-journal-text me-1 text-primary"></i>
                        آزمون‌ها: {{ $exams->count() }}
                    </div>

                    @if (isset($classroom->students_count))
                        <div class="cshow-pill">
                            <i class="bi bi-people me-1 text-success"></i>
                            اعضا: {{ $classroom->students_count }}
                        </div>
                    @endif
                </div>

                {{-- Quick actions --}}
                <div class="cshow-actions mt-3">
                    <a href="{{ route('student.classrooms.index') }}" class="btn btn-outline-primary cshow-btn">
                        بازگشت به کلاس‌ها
                    </a>

                    <a href="{{ route('student.exams.classroom') }}" class="btn btn-success cshow-btn">
                        آزمون‌های کلاسی ⭐
                    </a>
                </div>
            </div>

            {{-- Exams --}}
            <div class="cshow-card">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-ui-checks-grid text-primary me-1"></i>
                        آزمون‌های این کلاس
                    </h5>

                    <a href="{{ route('student.exams.index') }}" class="btn btn-outline-primary btn-sm">
                        مشاهده همه آزمون‌ها
                    </a>
                </div>

                @if ($exams->isEmpty())
                    <div class="cshow-empty">
                        <div class="cshow-empty-illus">
                            <i class="bi bi-journal-x"></i>
                        </div>
                        <div class="cshow-empty-title">هنوز آزمونی برای این کلاس منتشر نشده</div>
                        <div class="cshow-empty-text">
                            به محض اینکه معلم آزمون جدیدی بسازد یا منتشر کند، همینجا نمایش داده می‌شود.
                        </div>
                    </div>
                @else
                    <div class="cshow-exam-list">
                        @foreach ($exams as $exam)
                            @php
                                $examTitle = $exam->title ?? 'آزمون بدون عنوان';
                                $questionsCount = $exam->questions_count ?? null;
                                $isActive = (bool)($exam->is_active ?? true);
                                $isPublished = (bool)($exam->is_published ?? true);
                            @endphp

                            <div class="cshow-exam-item">
                                <div class="cshow-exam-main">
                                    <div class="cshow-exam-title">{{ $examTitle }}</div>

                                    <div class="cshow-exam-meta">
                                        @if($questionsCount !== null)
                                            <span class="text-muted">سوالات:</span> {{ $questionsCount }}
                                            <span class="mx-1">•</span>
                                        @endif

                                        <span class="text-muted">وضعیت:</span>
                                        {{ $isPublished ? 'منتشر شده' : 'پیش‌نویس' }}
                                        <span class="mx-1">•</span>
                                        {{ $isActive ? 'فعال' : 'غیرفعال' }}
                                    </div>
                                </div>

                                <div class="cshow-exam-actions">
                                    <a class="btn btn-outline-primary btn-sm"
                                       href="{{ route('student.exams.show', $exam) }}">
                                        جزئیات
                                    </a>

                                    {{-- شروع/شرکت (فعلاً از صفحه جزئیات وارد می‌شود) --}}
                                    <a class="btn btn-primary btn-sm"
                                       href="{{ route('student.exams.show', $exam) }}">
                                        ورود
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection
