@extends('layouts.app')
@section('title', 'جزئیات کلاس')

@push('styles')
    <style>
        .hero {
            background: radial-gradient(1100px circle at 100% -20%, rgba(13, 110, 253, .16), transparent 60%), radial-gradient(900px circle at 0% 0%, rgba(32, 201, 151, .12), transparent 55%), linear-gradient(180deg, #fff, #f8fafc);
            border-radius: 1.5rem;
            padding: 1.25rem;
            box-shadow: 0 10px 30px rgba(18, 38, 63, .08);
            position: relative;
            overflow: hidden
        }

        .hero-orb {
            position: absolute;
            inset: auto -90px -90px auto;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(13, 110, 253, .18), transparent 60%);
            filter: blur(2px);
            animation: floaty 7s ease-in-out infinite
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0)
            }

            50% {
                transform: translateY(-10px) translateX(-8px)
            }
        }

        .panel {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem
        }

        .meta-pill {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 999px;
            padding: .25rem .6rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-weight: 800;
            font-size: .85rem;
            color: #475569
        }

        .badge-soft {
            background: rgba(13, 110, 253, .12);
            color: #0d6efd;
            font-weight: 800;
            border-radius: 999px
        }

        .badge-soft-success {
            background: rgba(25, 135, 84, .12);
            color: #198754;
            font-weight: 800;
            border-radius: 999px
        }

        .stat-card {
            background: #fff;
            border: 1px dashed #e2e8f0;
            border-radius: 1rem;
            padding: .9rem;
            display: flex;
            align-items: center;
            justify-content-between
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(13, 110, 253, .12), rgba(13, 110, 253, .02));
            font-size: 1.25rem
        }

        .quick {
            display: flex;
            flex-direction: column;
            gap: .5rem
        }

        .quick a {
            border-radius: 1rem;
            padding: .8rem .9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
            text-decoration: none;
            color: inherit;
            transition: .2s
        }

        .quick a:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(18, 38, 63, .12)
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 0
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #e9ecef;
            font-weight: 800
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- HERO HEADER --}}
        <div class="hero mb-4 fade-up">
            <div class="hero-orb"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 text-end">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h1 class="h4 fw-bold mb-0"><i class="bi bi-people me-1 text-primary"></i> {{ $class->title }}</h1>
                        <span class="badge {{ $class->is_active ?? 1 ? 'badge-soft-success' : 'badge-soft' }} px-2 py-1">
                            {{ $class->is_active ?? 1 ? 'فعال' : 'آرشیو' }}
                        </span>
                    </div>
                    <div class="text-muted small">
                        <i class="bi bi-book me-1"></i> {{ $class->subject ?? 'عمومی' }}
                        <span class="mx-1">•</span>
                        <i class="bi bi-layers me-1"></i> پایه: {{ $class->grade ?? '—' }}
                    </div>
                    @if ($class->description)
                        <div class="text-muted mt-2">{{ $class->description }}</div>
                    @endif
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('teacher.classes.edit', $class) }}"
                        class="btn btn-warning d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-pencil-square"></i> ویرایش کلاس
                    </a>
                    <a href="{{ route('teacher.classes.students', $class) }}"
                        class="btn btn-outline-success d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-person-video3"></i> مدیریت اعضا
                    </a>
                    <a href="{{ route('teacher.exams.create', ['class_id' => $class->id]) }}"
                        class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-plus-circle"></i> آزمون برای کلاس
                    </a>
                    <a href="{{ route('teacher.classes.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>

            {{-- Join code strip --}}
            <div
                class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 p-2 px-3 bg-white rounded-3 shadow-sm">
                <div class="fw-semibold"><i class="bi bi-key me-1"></i> کد ورود دانش‌آموزان:</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="fw-bold fs-5" id="joinCodeText">{{ $class->join_code }}</div>
                    <button class="btn btn-outline-primary btn-sm" id="copyBtn"><i class="bi bi-clipboard"></i>
                        کپی</button>
                    <button class="btn btn-outline-secondary btn-sm" id="regenBtn"><i class="bi bi-shuffle"></i> ساخت کد
                        جدید</button>
                </div>
                <div class="text-muted small">بعد از تغییر کد، دانش‌آموزان باید با کد جدید وارد شوند.</div>
            </div>
        </div>


        <div class="row g-3">

            {{-- Left column main --}}
            <div class="col-lg-8">

                {{-- Stats --}}
                <div class="panel p-3 mb-3 fade-up">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div>
                                    <div class="text-muted small">تعداد دانش‌آموزان</div>
                                    <div class="fs-4 fw-bold">{{ $class->students->count() }}</div>
                                </div>
                                <div class="stat-icon text-primary"><i class="bi bi-people"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div>
                                    <div class="text-muted small">آزمون‌های این کلاس</div>
                                    <div class="fs-4 fw-bold">{{ $class->exams_count ?? 0 }}</div>
                                </div>
                                <div class="stat-icon text-info"
                                    style="background:linear-gradient(135deg,rgba(13,202,240,.16),rgba(13,202,240,.02))"><i
                                        class="bi bi-ui-checks-grid"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div>
                                    <div class="text-muted small">تاریخ ایجاد</div>
                                    <div class="fw-bold">{{ $class->created_at?->format('Y/m/d') }}</div>
                                </div>
                                <div class="stat-icon text-success"
                                    style="background:linear-gradient(135deg,rgba(25,135,84,.16),rgba(25,135,84,.02))"><i
                                        class="bi bi-calendar2-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Students preview list --}}
                <div class="panel p-3 fade-up">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold"><i class="bi bi-person-video3 me-1 text-success"></i> اعضای کلاس</div>
                        <a href="{{ route('teacher.classes.students', $class) }}" class="small text-decoration-none">مدیریت
                            کامل <i class="bi bi-arrow-left ms-1"></i></a>
                    </div>

                    @if ($class->students->count())
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>دانش‌آموز</th>
                                        <th>ایمیل</th>
                                        <th>تاریخ عضویت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students->take(8) as $i => $student)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="d-flex align-items-center gap-2">
                                                <div class="avatar">{{ mb_substr($student->name ?? '—', 0, 1) }}</div>
                                                <div>
                                                    <div class="fw-semibold">{{ $student->name ?? '—' }}</div>
                                                    <div class="text-muted small">
                                                        {{ $student->username ?? ($student->phone ?? '') }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $student->email ?? '—' }}</td>
                                            <td class="small text-muted">
                                                {{ optional($student->pivot)->created_at?->format('Y/m/d') ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted small py-4">
                            <div class="fs-3 text-success mb-2"><i class="bi bi-person-x"></i></div>
                            هنوز دانش‌آموزی عضو این کلاس نشده است.
                        </div>
                    @endif
                </div>
                {{-- Exams of this class   --}}
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                        <span>آزمون‌های این کلاس</span>

                        <a href="{{ route('teacher.exams.create', ['classroom_id' => $class->id]) }}"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> ساخت آزمون برای این کلاس
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($class->exams->count() === 0)
                            <div class="alert alert-warning small mb-0">
                                هنوز آزمونی برای این کلاس ساخته نشده است.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان</th>
                                            <th>مدت</th>
                                            <th>وضعیت</th>
                                            <th class="text-end">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($class->exams as $exam)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="fw-semibold">{{ $exam->title }}</td>
                                                <td class="text-muted small">
                                                    {{ $exam->duration ?? ($exam->duration_minutes ?? '—') }} دقیقه
                                                </td>
                                                <td>
                                                    @if ($exam->is_active)
                                                        <span class="badge bg-success">فعال</span>
                                                    @else
                                                        <span class="badge bg-secondary">غیرفعال</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('teacher.exams.show', $exam) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        جزئیات
                                                    </a>
                                                    <a href="{{ route('teacher.exams.edit', $exam) }}"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        ویرایش
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>


            {{-- Right column quick actions --}}
            <div class="col-lg-4">
                <div class="panel p-3 fade-up">
                    <div class="fw-bold mb-2"><i class="bi bi-lightning me-1 text-warning"></i> عملیات سریع</div>
                    <div class="quick">
                        <a href="{{ route('teacher.classes.edit', $class) }}">
                            <span class="d-flex align-items-center gap-2"><i class="bi bi-pencil-square text-warning"></i>
                                ویرایش اطلاعات</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.classes.students', $class) }}">
                            <span class="d-flex align-items-center gap-2"><i class="bi bi-person-plus text-success"></i>
                                افزودن/حذف اعضا</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.exams.create', ['class_id' => $class->id]) }}">
                            <span class="d-flex align-items-center gap-2"><i class="bi bi-plus-circle text-primary"></i>
                                ساخت آزمون جدید</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </a>
                    </div>

                    <hr>
                    <div class="small text-muted">می‌توانی از کد ورود برای عضویت دانش‌آموزان استفاده کنی.</div>
                </div>

                {{-- Danger zone --}}
                <div class="panel p-3 mt-3 fade-up"
                    style="border:1px solid rgba(220,53,69,.2);background:rgba(220,53,69,.04)">
                    <div class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle me-1"></i> ناحیه خطر</div>
                    <div class="small text-muted mb-2">حذف کلاس باعث حذف ارتباط دانش‌آموزان با این کلاس می‌شود.</div>
                    <form action="{{ route('teacher.classes.destroy', $class) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger w-100" onclick="return confirm('کلاس حذف شود؟')">
                            حذف کلاس
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const copyBtn = document.getElementById('copyBtn');
                const regenBtn = document.getElementById('regenBtn');
                const joinCodeText = document.getElementById('joinCodeText');

                copyBtn?.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(joinCodeText.textContent.trim());
                        copyBtn.classList.remove('btn-outline-primary');
                        copyBtn.classList.add('btn-success');
                        copyBtn.innerHTML = '<i class="bi bi-check2"></i> کپی شد';
                        setTimeout(() => {
                            copyBtn.classList.add('btn-outline-primary');
                            copyBtn.classList.remove('btn-success');
                            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i> کپی';
                        }, 1200);
                    } catch (e) {
                        alert('کپی انجام نشد');
                    }
                });

                regenBtn?.addEventListener('click', () => {
                    // فقط UI؛ کد واقعی را باید در backend عوض کنی اگر خواستی
                    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                    let c = '';
                    for (let i = 0; i < 6; i++) c += chars[Math.floor(Math.random() * chars.length)];
                    joinCodeText.textContent = c;
                    regenBtn.classList.add('btn-success');
                    regenBtn.innerHTML = '<i class="bi bi-check2"></i> ساخته شد';
                    setTimeout(() => {
                        regenBtn.classList.remove('btn-success');
                        regenBtn.innerHTML = '<i class="bi bi-shuffle"></i> ساخت کد جدید';
                    }, 1200);
                });
            });
        </script>
    @endpush
@endsection
