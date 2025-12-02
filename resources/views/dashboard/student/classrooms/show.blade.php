@extends('layouts.app')

@section('title', 'جزئیات کلاس')

@push('styles')
    <style>
        /* ------------------------------------------------------------------
             | Page Shell
             |-------------------------------------------------------------------*/
        .page-wrap {
            padding: 1.5rem 0;
        }

        .soft-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            background: #fff;
        }

        /* ------------------------------------------------------------------
             | Classroom Header
             |-------------------------------------------------------------------*/
        .class-header {
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
            border-radius: 1.25rem;
            padding: 1.25rem 1.25rem 1rem 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .class-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #ffffffcc;
            border: 1px dashed #c7d2fe;
            font-weight: 800;
            padding: .25rem .7rem;
            border-radius: 999px;
            font-size: .85rem;
        }

        .meta {
            color: #64748b;
            font-size: .9rem;
        }

        .tiny {
            font-size: .85rem;
        }

        .stat-pill {
            background: #fff;
            border: 1px solid #eef2f7;
            padding: .45rem .8rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: .85rem;
        }

        /* ------------------------------------------------------------------
             | Exams List
             |-------------------------------------------------------------------*/
        .exam-card {
            border: 1px solid #eef2f7;
            border-radius: 1.1rem;
            transition: .2s ease;
            background: #fff;
        }

        .exam-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(18, 38, 63, .08);
            border-color: #e5e7eb;
        }

        .exam-badge {
            border-radius: 999px;
            font-size: .8rem;
            padding: .3rem .6rem;
            font-weight: 700;
        }

        /* ------------------------------------------------------------------
             | Empty Exams
             |-------------------------------------------------------------------*/
        .empty-wrap {
            text-align: center;
            padding: 2.5rem 1rem;
        }

        .empty-illus {
            width: 90px;
            height: 90px;
            border-radius: 1.75rem;
            background: #eef2ff;
            display: grid;
            place-items: center;
            margin: 0 auto 1rem;
            color: #4f46e5;
            font-size: 2rem;
        }

        .empty-title {
            font-weight: 800;
            margin-bottom: .5rem;
        }

        .empty-text {
            color: #64748b;
            max-width: 520px;
            margin: 0 auto 1.25rem;
        }
    </style>
@endpush


@section('content')
    <div class="container page-wrap">

        {{-- ============================================================
         | Top Actions
         |============================================================= --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="bi bi-easel2-fill text-primary me-1"></i>
                    جزئیات کلاس
                </h4>
                <div class="text-muted small">
                    در این صفحه اطلاعات کلاس و آزمون‌های مربوط به آن نمایش داده می‌شود.
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('student.classrooms.index') }}"
                    class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت به کلاس‌ها
                </a>

                <a href="{{ route('student.classrooms.join.form') }}"
                    class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-box-arrow-in-right"></i>
                    عضویت در کلاس جدید
                </a>
            </div>
        </div>


        {{-- ============================================================
         | Classroom Header Card
         |============================================================= --}}
        <div class="class-header soft-card mb-3">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                    <div class="fw-bold fs-4 mb-1">
                        {{ $classroom->title }}
                    </div>

                    <div class="meta">
                        {{ $classroom->subject ?? 'موضوع نامشخص' }}
                        <span class="mx-1">•</span>
                        پایه: {{ $classroom->grade ?? '—' }}
                    </div>
                </div>

                <div class="d-flex flex-column align-items-end gap-2">
                    <span class="class-chip">
                        <i class="bi bi-key-fill text-primary"></i>
                        {{ $classroom->join_code }}
                    </span>

                    <span class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }} exam-badge">
                        {{ $classroom->is_active ? 'کلاس فعال' : 'کلاس غیرفعال' }}
                    </span>
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-3">
                @if (!empty($classroom->description))
                    <div class="small text-muted">
                        {{ $classroom->description }}
                    </div>
                @else
                    <div class="small text-muted">
                        برای این کلاس توضیحی ثبت نشده است.
                    </div>
                @endif
            </div>

            {{-- Teacher Info --}}
            <div class="mt-3 d-flex align-items-center gap-2">
                <div class="rounded-circle bg-white d-grid place-items-center border" style="width:42px;height:42px;">
                    <i class="bi bi-person-badge text-secondary"></i>
                </div>
                <div>
                    <div class="tiny text-muted">معلم کلاس</div>
                    <div class="fw-semibold">
                        {{ $classroom->teacher->name ?? 'نامشخص' }}
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            @php
                $exams = $classroom->exams ?? collect();
            @endphp

            <div class="d-flex flex-wrap gap-2 mt-3">
                <div class="stat-pill">
                    <i class="bi bi-journal-text me-1 text-primary"></i>
                    آزمون‌ها: {{ $exams->count() }}
                </div>

                @if (isset($classroom->students_count))
                    <div class="stat-pill">
                        <i class="bi bi-people me-1 text-success"></i>
                        اعضا: {{ $classroom->students_count }}
                    </div>
                @endif
            </div>
        </div>


        {{-- ============================================================
         | Exams Section
         |============================================================= --}}
        <div class="soft-card p-3 p-md-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-ui-checks-grid text-primary me-1"></i>
                    آزمون‌های این کلاس
                </h5>

                <a href="{{ route('student.exams.index', ['classroom_id' => $classroom->id]) }}"
                    class="btn btn-outline-primary btn-sm">
                    مشاهده همه آزمون‌ها
                </a>
            </div>

            {{-- Empty exams --}}
            @if ($exams->count() == 0)
                <div class="empty-wrap">
                    <div class="empty-illus">
                        <i class="bi bi-journal-x"></i>
                    </div>

                    <h6 class="empty-title">هنوز آزمونی برای این کلاس منتشر نشده</h6>

                    <p class="empty-text">
                        به محض اینکه معلم آزمون جدیدی بسازد یا منتشر کند،
                        همینجا لیست می‌شود و می‌توانی شرکت کنی.
                    </p>

                    <a href="{{ route('student.classrooms.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-right"></i>
                        برگشت به کلاس‌ها
                    </a>
                </div>
            @else
                {{-- Exams list --}}
                <div class="row g-3">
                    @foreach ($exams as $exam)
                        @php
                            $qCount =
                                $exam->questions_count ?? (isset($exam->questions) ? $exam->questions->count() : null);
                        @endphp

                        <div class="col-md-6 col-xl-4">
                            <div class="exam-card p-3 h-100">

                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-bold mb-1">
                                            {{ $exam->title }}
                                        </div>
                                        <div class="tiny text-muted">
                                            مدت: {{ $exam->duration ?? 0 }} دقیقه
                                            @if (!is_null($qCount))
                                                <span class="mx-1">•</span>
                                                سوال‌ها: {{ $qCount }}
                                            @endif
                                        </div>
                                    </div>

                                    <span class="badge bg-light text-dark border exam-badge">
                                        {{ $exam->is_published ? 'منتشر شده' : 'پیش‌نویس' }}
                                    </span>
                                </div>

                                @if (!empty($exam->description))
                                    <div class="small text-muted mt-2">
                                        {{ \Illuminate\Support\Str::limit($exam->description, 90) }}
                                    </div>
                                @endif

                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('student.exams.show', $exam) }}"
                                        class="btn btn-outline-primary w-100 btn-sm">
                                        جزئیات
                                    </a>

                                    <a href="{{ route('student.exams.take', $exam) }}"
                                        class="btn btn-primary w-100 btn-sm">
                                        شروع آزمون
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
