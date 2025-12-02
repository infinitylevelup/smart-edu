@extends('layouts.app')
@section('title', 'پنل معلم - کلاس‌های من')

@push('styles')
    <style>
        .hero-wrap {
            background: radial-gradient(1200px circle at 100% -20%, rgba(13, 110, 253, .15), transparent 60%),
                radial-gradient(900px circle at 0% 0%, rgba(32, 201, 151, .12), transparent 55%),
                linear-gradient(180deg, #ffffff, #f8fafc);
            border-radius: 1.5rem;
            padding: 1.25rem;
            box-shadow: 0 10px 30px rgba(18, 38, 63, .08);
            position: relative;
            overflow: hidden;
        }

        .hero-orb {
            position: absolute;
            inset: auto -80px -80px auto;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(13, 110, 253, .18), transparent 60%);
            animation: floaty 7s ease-in-out infinite;
            filter: blur(2px);
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-10px) translateX(-8px);
            }
        }

        .filter-card {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
        }

        .input-soft {
            border: 0;
            box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
            border-radius: .9rem;
            padding: .7rem .9rem
        }

        .input-soft:focus {
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15)
        }

        .class-card {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 22px rgba(18, 38, 63, .06);
            transition: .2s ease;
            position: relative;
            overflow: hidden;
        }

        .class-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 34px rgba(18, 38, 63, .12);
        }

        .class-ribbon {
            position: absolute;
            top: 0;
            left: 0;
            padding: .35rem .75rem;
            font-size: .8rem;
            font-weight: 800;
            border-bottom-right-radius: 1rem;
            background: rgba(13, 110, 253, .1);
            color: #0d6efd;
        }

        .class-meta {
            font-size: .88rem;
            color: #64748b;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .meta-pill {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 999px;
            padding: .25rem .6rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-weight: 600;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            border-radius: .8rem;
        }

        .empty-wrap {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            padding: 2.2rem;
            text-align: center;
        }

        .empty-illus {
            width: 84px;
            height: 84px;
            display: grid;
            place-items: center;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(13, 110, 253, .12), rgba(13, 110, 253, .02));
            font-size: 2rem;
            color: #0d6efd;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- ===== HERO HEADER ===== --}}
        <div class="hero-wrap mb-4 fade-up">
            <div class="hero-orb"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 text-end">
                <div>
                    <h1 class="h4 fw-bold mb-1">
                        <i class="bi bi-people me-1 text-primary"></i>
                        کلاس‌های من
                    </h1>
                    <p class="text-muted mb-0">
                        مدیریت ساخت، ویرایش، اعضا و آزمون‌های هر کلاس.
                    </p>
                </div>

                <div class="d-flex gap-2">
                    {{-- چون create نداریم، فقط وقتی روتش بود نمایش بده --}}
                    @if (\Illuminate\Support\Facades\Route::has('teacher.classes.create'))
                        <a href="{{ route('teacher.classes.create') }}"
                            class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                            <i class="bi bi-plus-circle"></i>
                            کلاس جدید
                        </a>
                    @endif

                    <a href="{{ route('teacher.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-right"></i>
                        بازگشت به داشبورد
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== Filters (همچنان کار می‌کنند حتی اگر backend فعلاً اعمال نکند) ===== --}}
        <div class="filter-card p-3 mb-3 fade-up">
            <form method="GET" action="{{ route('teacher.classes.index') }}" class="row g-2 align-items-end">

                <div class="col-12 col-md-5">
                    <label class="form-label small mb-1">جستجو</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control input-soft"
                            placeholder="نام کلاس، پایه یا کد کلاس..." value="{{ request('q') }}">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label small mb-1">پایه</label>
                    <select name="grade" class="form-select input-soft">
                        @php $grade = request('grade','all'); @endphp
                        <option value="all" {{ $grade === 'all' ? 'selected' : '' }}>همه پایه‌ها</option>
                        <option value="7" {{ $grade === '7' ? 'selected' : '' }}>هفتم</option>
                        <option value="8" {{ $grade === '8' ? 'selected' : '' }}>هشتم</option>
                        <option value="9" {{ $grade === '9' ? 'selected' : '' }}>نهم</option>
                        <option value="10" {{ $grade === '10' ? 'selected' : '' }}>دهم</option>
                        <option value="11" {{ $grade === '11' ? 'selected' : '' }}>یازدهم</option>
                        <option value="12" {{ $grade === '12' ? 'selected' : '' }}>دوازدهم</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label small mb-1">وضعیت</label>
                    <select name="status" class="form-select input-soft">
                        @php $status=request('status','all'); @endphp
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>همه</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>آرشیو</option>
                    </select>
                </div>

                <div class="col-12 col-md-2">
                    <label class="form-label small mb-1">مرتب‌سازی</label>
                    <select name="sort" class="form-select input-soft">
                        @php $sort=request('sort','latest'); @endphp
                        <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>جدیدترین</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>قدیمی‌ترین</option>
                        <option value="students" {{ $sort === 'students' ? 'selected' : '' }}>بیشترین دانش‌آموز</option>
                        <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>نام A→Z</option>
                        <option value="title_desc" {{ $sort === 'title_desc' ? 'selected' : '' }}>نام Z→A</option>
                    </select>
                </div>

                <div class="col-12 col-md-1 d-flex gap-2">
                    <button class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-secondary w-100 shadow-sm">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- ===== Classes List ===== --}}

        @php
            // اگر کنترلر $classes نفرستاد، صفحه خطا نده
            $classes = $classes ?? collect();
            $hasPaginator = method_exists($classes, 'total');
            $totalClasses = $hasPaginator ? $classes->total() : $classes->count();
        @endphp

        @if ($totalClasses == 0)
            <div class="empty-wrap fade-up">
                <div class="empty-illus">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="fw-bold mb-2">هنوز کلاسی نساختی!</h5>
                <p class="text-muted mb-3">
                    وقتی روت ساخت کلاس اضافه بشه، از همینجا می‌تونی کلاس بسازی.
                </p>
            </div>
        @else
            <div class="row g-3">
                @foreach ($classes as $class)
                    @php
                        $studentsCount = $class->students_count ?? ($class->students->count() ?? 0);
                        $examsCount = $class->exams_count ?? 0;
                        $isActive = $class->is_active ?? true;
                        $gradeLabel = $class->grade ?? '—';
                        $code = $class->code ?? ($class->join_code ?? null);
                    @endphp

                    <div class="col-12 col-md-6 col-xl-4 fade-up">
                        <div class="class-card p-3 h-100">

                            <div class="class-ribbon">
                                {{ $isActive ? 'فعال' : 'آرشیو' }}
                            </div>

                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="fw-bold fs-5 d-flex align-items-center gap-2">
                                        <i class="bi bi-mortarboard text-primary"></i>
                                        {{ $class->title ?? ($class->name ?? 'کلاس بدون نام') }}
                                    </div>
                                    <div class="text-muted small mt-1">
                                        {{ $class->subject ?? 'عمومی' }}
                                    </div>
                                </div>

                                {{-- چون edit/destroy نداریم، اینجا فعلاً چیزی نمایش نده --}}
                            </div>

                            <div class="class-meta mb-3">
                                <span class="meta-pill">
                                    <i class="bi bi-layers"></i>
                                    پایه: {{ $gradeLabel }}
                                </span>

                                <span class="meta-pill">
                                    <i class="bi bi-people"></i>
                                    {{ $studentsCount }} دانش‌آموز
                                </span>

                                <span class="meta-pill">
                                    <i class="bi bi-ui-checks-grid"></i>
                                    {{ $examsCount }} آزمون
                                </span>

                                @if ($code)
                                    <span class="meta-pill">
                                        <i class="bi bi-key"></i>
                                        کد ورود: <span class="fw-bold">{{ $code }}</span>
                                    </span>
                                @endif
                            </div>

                            @if (!empty($class->description))
                                <div class="text-muted small mb-3">
                                    {{ \Illuminate\Support\Str::limit($class->description, 110) }}
                                </div>
                            @endif

                            {{-- actions: فقط آزمون‌سازی داریم --}}
                            <div class="d-flex flex-wrap gap-2 mt-auto">
                                <a href="{{ route('teacher.exams.create', ['class_id' => $class->id]) }}"
                                    class="btn btn-primary btn-sm btn-icon shadow-sm">
                                    <i class="bi bi-plus-circle"></i>
                                    آزمون برای کلاس
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            @if (method_exists($classes, 'hasPages') && $classes->hasPages())
                <div class="mt-3 fade-up">
                    {{ $classes->links() }}
                </div>
            @endif
        @endif

    </div>
@endsection
