@extends('layouts.app')

@section('title', 'کلاس‌های من')

@push('styles')
    <style>
        /* ------------------------------------------------------------------
             | Page / Layout
             |-------------------------------------------------------------------*/
        .page-wrap {
            padding: 1.5rem 0;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .page-title {
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .soft-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            background: #fff;
        }

        /* ------------------------------------------------------------------
             | Search / Filter Bar
             |-------------------------------------------------------------------*/
        .search-bar {
            background: #f8fafc;
            border-radius: 1rem;
            padding: .7rem;
            display: flex;
            gap: .5rem;
            align-items: center;
        }

        .search-input {
            border: 0;
            background: transparent;
            outline: none;
            width: 100%;
        }

        /* ------------------------------------------------------------------
             | Classroom Cards
             |-------------------------------------------------------------------*/
        .class-card {
            border: 1px solid #eef2f7;
            border-radius: 1.25rem;
            transition: .2s ease;
            overflow: hidden;
            background: #fff;
        }

        .class-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(18, 38, 63, .10);
            border-color: #e5e7eb;
        }

        .class-hero {
            background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
            padding: 1rem 1rem .8rem 1rem;
        }

        .class-chip {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            background: #ffffffb5;
            border: 1px dashed #c7d2fe;
            font-weight: 700;
            padding: .25rem .6rem;
            border-radius: 999px;
            font-size: .82rem;
        }

        .meta {
            font-size: .85rem;
            color: #64748b;
        }

        .tiny {
            font-size: .82rem;
        }

        .progress-soft {
            height: 8px;
            border-radius: 999px;
            background: #f1f5f9;
            overflow: hidden;
        }

        .progress-soft .bar {
            height: 100%;
            background: #0d6efd;
            width: 0%;
        }

        /* ------------------------------------------------------------------
             | Empty State
             |-------------------------------------------------------------------*/
        .empty-wrap {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-illus {
            width: 96px;
            height: 96px;
            border-radius: 2rem;
            background: #eef2ff;
            display: grid;
            place-items: center;
            margin: 0 auto 1rem auto;
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
            margin: 0 auto 1.25rem auto;
        }
    </style>
@endpush


@section('content')
    <div class="container page-wrap">

        {{-- ============================================================
         | Header + Actions
         |============================================================= --}}
        <div class="page-header">
            <div>
                <h4 class="page-title mb-1">
                    <i class="bi bi-easel2-fill text-primary me-1"></i>
                    کلاس‌های من
                </h4>
                <div class="text-muted small">
                    اینجا تمام کلاس‌هایی که عضو هستی دیده می‌شود؛
                    از همینجا می‌تونی وارد کلاس بشی یا آزمون‌هاش رو ببینی.
                </div>
            </div>

            <div class="d-flex gap-2">
                {{-- رفتن به فرم join --}}
                <a href="{{ route('student.classrooms.join.form') }}"
                    class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-box-arrow-in-right"></i>
                    عضویت با کد کلاس
                </a>

                {{-- رفرش لیست --}}
                <a href="{{ route('student.classrooms.index') }}"
                    class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-clockwise"></i>
                    بروزرسانی
                </a>
            </div>
        </div>

        {{-- ============================================================
         | Alerts
         |============================================================= --}}
        @if (session('success'))
            <div class="alert alert-success soft-card p-3">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger soft-card p-3">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ session('error') }}
            </div>
        @endif


        @php
            // اگر کنترلر classrooms را پاس داده باشد:
            $classrooms = $classrooms ?? collect();

            // برای UI بهتر: یک مقدار پیشفرض برای progress
            // (در آینده می‌تونیم اینو واقعی کنیم)
            function fakeProgress($id)
            {
                return ($id * 17) % 100; // صرفاً برای زیبایی ظاهری
            }
        @endphp

        {{-- ============================================================
         | Search / Filter Bar (client-side)
         |============================================================= --}}
        <div class="soft-card p-2 mb-3">
            <div class="search-bar">
                <i class="bi bi-search text-muted"></i>
                <input id="classSearch" type="text" class="search-input"
                    placeholder="جستجو در کلاس‌ها (عنوان، موضوع، پایه، معلم...)">
            </div>
        </div>


        {{-- ============================================================
         | Empty State
         |============================================================= --}}
        @if ($classrooms->count() == 0)

            <div class="soft-card empty-wrap">
                <div class="empty-illus">
                    <i class="bi bi-people-fill"></i>
                </div>

                <h5 class="empty-title">هنوز عضو هیچ کلاسی نیستی</h5>

                <p class="empty-text">
                    برای شروع یادگیری، کد ورود کلاس (join_code) را از معلمت بگیر
                    و همینجا وارد کن تا به کلاس متصل بشی و آزمون‌ها برات فعال بشه.
                </p>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('student.classrooms.join.form') }}"
                        class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-box-arrow-in-right"></i>
                        ورود با کد کلاس
                    </a>

                    <a href="{{ route('student.index') }}"
                        class="btn btn-outline-secondary btn-lg d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-grid"></i>
                        برگشت به داشبورد
                    </a>
                </div>
            </div>
        @else
            {{-- ============================================================
             | Classrooms Grid
             |============================================================= --}}
            <div class="row g-3" id="classGrid">

                @foreach ($classrooms as $classroom)
                    @php
                        $teacher = $classroom->teacher ?? null;

                        // اگر relation exams لود شده باشد:
                        $examsCount = isset($classroom->exams)
                            ? $classroom->exams->count()
                            : $classroom->exams_count ?? 0;

                        $studentsCount = $classroom->students_count ?? null;

                        $progress = fakeProgress($classroom->id);
                    @endphp

                    <div class="col-md-6 col-xl-4 class-item"
                        data-search="{{ strtolower($classroom->title . ' ' . $classroom->subject . ' ' . $classroom->grade . ' ' . ($teacher->name ?? '')) }}">

                        <div class="class-card h-100">

                            {{-- ---------------- Hero / Top  ---------------- --}}
                            <div class="class-hero">

                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold fs-5 mb-1">
                                            {{ $classroom->title }}
                                        </div>

                                        <div class="meta">
                                            {{ $classroom->subject ?? 'موضوع نامشخص' }}
                                            <span class="mx-1">•</span>
                                            پایه: {{ $classroom->grade ?? '—' }}
                                        </div>
                                    </div>

                                    <span class="class-chip">
                                        <i class="bi bi-key-fill text-primary"></i>
                                        {{ $classroom->join_code }}
                                    </span>
                                </div>

                                {{-- Progress (نمایشی برای UX بهتر) --}}
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between tiny text-muted mb-1">
                                        <span>پیشرفت تقریبی</span>
                                        <span>{{ $progress }}%</span>
                                    </div>
                                    <div class="progress-soft">
                                        <div class="bar" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- ---------------- Body / Stats  ---------------- --}}
                            <div class="p-3">

                                {{-- Teacher --}}
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="rounded-circle bg-light d-grid place-items-center"
                                        style="width:36px;height:36px;">
                                        <i class="bi bi-person-badge text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="tiny text-muted">معلم</div>
                                        <div class="fw-semibold">
                                            {{ $teacher->name ?? 'نامشخص' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                @if (!empty($classroom->description))
                                    <p class="small text-muted mb-3" style="min-height: 40px;">
                                        {{ \Illuminate\Support\Str::limit($classroom->description, 95) }}
                                    </p>
                                @else
                                    <p class="small text-muted mb-3" style="min-height: 40px;">
                                        توضیحی برای این کلاس ثبت نشده؛
                                        می‌تونی با ورود به کلاس جزئیات بیشتر رو ببینی.
                                    </p>
                                @endif

                                {{-- Stats Row --}}
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                        <i class="bi bi-journal-text me-1"></i>
                                        آزمون‌ها: {{ $examsCount }}
                                    </span>

                                    @if (!is_null($studentsCount))
                                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                            <i class="bi bi-people me-1"></i>
                                            اعضا: {{ $studentsCount }}
                                        </span>
                                    @endif

                                    <span
                                        class="badge {{ $classroom->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                        {{ $classroom->is_active ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('student.classrooms.show', $classroom) }}"
                                        class="btn btn-outline-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-eye"></i>
                                        ورود به کلاس
                                    </a>

                                    <a href="{{ route('student.exams.index', ['classroom_id' => $classroom->id]) }}"
                                        class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-ui-checks-grid"></i>
                                        آزمون‌ها
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
@endsection


@push('scripts')
    <script>
        // --------------------------------------------------------------
        // Client-side search filter for classrooms
        // --------------------------------------------------------------
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('classSearch');
            const items = document.querySelectorAll('.class-item');

            input?.addEventListener('input', () => {
                const q = input.value.toLowerCase().trim();

                items.forEach(el => {
                    const text = el.dataset.search || '';
                    el.style.display = text.includes(q) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
