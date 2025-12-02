@extends('layouts.app')
@section('title', 'اعضای کلاس')

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

        .input-soft {
            border: 0;
            box-shadow: 0 6px 18px rgba(18, 38, 63, .06);
            border-radius: .9rem;
            padding: .7rem .9rem
        }

        .input-soft:focus {
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15)
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #e9ecef;
            font-weight: 800
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 0
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

        .empty-wrap {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            padding: 2rem;
            text-align: center
        }

        .empty-illus {
            width: 84px;
            height: 84px;
            display: grid;
            place-items: center;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(25, 135, 84, .12), rgba(25, 135, 84, .02));
            font-size: 2rem;
            color: #198754
        }

        .invite-card {
            border: 1px dashed #e2e8f0;
            border-radius: 1.25rem;
            background: linear-gradient(180deg, #fff, #f8fafc)
        }

        .copy-btn {
            border-radius: .8rem
        }

        .danger-row {
            background: rgba(220, 53, 69, .05)
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- HERO --}}
        <div class="hero mb-4 fade-up">
            <div class="hero-orb"></div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 text-end">
                <div>
                    <h1 class="h4 fw-bold mb-1"><i class="bi bi-person-video3 me-1 text-success"></i> مدیریت اعضای کلاس</h1>
                    <div class="text-muted">
                        {{ $class->title }}
                        <span class="mx-1">•</span>
                        پایه {{ $class->grade ?? '—' }}
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('teacher.classes.show', $class) }}"
                        class="btn btn-outline-primary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-eye"></i> جزئیات کلاس
                    </a>
                    <a href="{{ route('teacher.classes.edit', $class) }}"
                        class="btn btn-warning d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-pencil-square"></i> ویرایش کلاس
                    </a>
                    <a href="{{ route('teacher.classes.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success fade-up"><i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger fade-up"><i class="bi bi-x-circle-fill me-1"></i>{{ session('error') }}</div>
        @endif

        <div class="row g-3">

            {{-- LEFT: members list --}}
            <div class="col-lg-8">
                <div class="panel p-3 fade-up">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="fw-bold">
                            <i class="bi bi-people me-1 text-success"></i>
                            اعضای فعلی ({{ $class->students->count() }})
                        </div>

                        <div class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control input-soft"
                                    placeholder="جستجو در اعضا...">
                            </div>
                        </div>
                    </div>

                    @if ($class->students->count())
                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>دانش‌آموز</th>
                                        <th>ایمیل/نام‌کاربری</th>
                                        <th>عضویت</th>
                                        <th class="text-end">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($class->students as $i => $student)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td class="d-flex align-items-center gap-2">
                                                <div class="avatar">{{ mb_substr($student->name ?? '—', 0, 1) }}</div>
                                                <div>
                                                    <div class="fw-semibold name-col">{{ $student->name ?? '—' }}</div>
                                                    <div class="text-muted small">
                                                        {{ $student->username ?? ($student->phone ?? '') }}</div>
                                                </div>
                                            </td>
                                            <td class="email-col">{{ $student->email ?? '—' }}</td>
                                            <td class="small text-muted">
                                                {{ optional($student->pivot)->created_at?->format('Y/m/d') ?? '—' }}</td>
                                            <td class="text-end">
                                                <form
                                                    action="{{ route('teacher.classes.students.remove', [$class, $student]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('این دانش‌آموز از کلاس حذف شود؟')">
                                                        <i class="bi bi-person-dash"></i>
                                                        حذف
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-wrap">
                            <div class="empty-illus"><i class="bi bi-person-x"></i></div>
                            <h6 class="fw-bold mb-1">هنوز دانش‌آموزی عضو این کلاس نیست.</h6>
                            <div class="text-muted small">از طریق کد ورود یا افزودن دستی، دانش‌آموزها را اضافه کن.</div>
                        </div>
                    @endif
                </div>
            </div>


            {{-- RIGHT: invite + add manual --}}
            <div class="col-lg-4">

                {{-- Invite by code --}}
                <div class="invite-card p-3 mb-3 fade-up">
                    <div class="fw-bold mb-2"><i class="bi bi-key me-1 text-primary"></i> دعوت با کد ورود</div>
                    <div class="small text-muted mb-2">این کد را در گروه یا کلاس ارسال کن.</div>
                    <div class="input-group">
                        <input type="text" class="form-control input-soft" id="joinCode"
                            value="{{ $class->join_code }}" readonly>
                        <button class="btn btn-outline-primary copy-btn" id="copyBtn"><i
                                class="bi bi-clipboard"></i></button>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="meta-pill"><i class="bi bi-broadcast"></i>
                            {{ $class->is_active ?? 1 ? 'فعال' : 'آرشیو' }}</span>
                        <span class="meta-pill"><i class="bi bi-people"></i> {{ $class->students->count() }} عضو</span>
                    </div>
                </div>

                {{-- Manual add --}}
                <div class="panel p-3 fade-up">
                    <div class="fw-bold mb-2"><i class="bi bi-person-plus me-1 text-success"></i> افزودن دستی دانش‌آموز
                    </div>
                    <div class="small text-muted mb-2">ایمیل یا نام‌کاربری دانش‌آموز را وارد کن.</div>

                    <form action="{{ route('teacher.classes.students.add', $class) }}" method="POST" class="d-grid gap-2">
                        @csrf
                        <input type="text" name="student" class="form-control input-soft"
                            placeholder="email / username / phone" required>
                        <button
                            class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="bi bi-plus-circle"></i>
                            افزودن به کلاس
                        </button>
                    </form>

                    <hr>
                    <div class="small text-muted">
                        اگر دانش‌آموز پیدا نشد، مطمئن شو قبلاً ثبت‌نام کرده باشد.
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Copy join code
                const copyBtn = document.getElementById('copyBtn');
                const joinCode = document.getElementById('joinCode');
                copyBtn?.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(joinCode.value);
                        copyBtn.classList.remove('btn-outline-primary');
                        copyBtn.classList.add('btn-success');
                        copyBtn.innerHTML = '<i class="bi bi-check2"></i>';
                        setTimeout(() => {
                            copyBtn.classList.add('btn-outline-primary');
                            copyBtn.classList.remove('btn-success');
                            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i>';
                        }, 1200);
                    } catch (e) {
                        alert('کپی انجام نشد');
                    }
                });

                // Simple client-side search
                const searchInput = document.getElementById('searchInput');
                const table = document.getElementById('studentsTable');
                searchInput?.addEventListener('input', () => {
                    const q = searchInput.value.toLowerCase();
                    table?.querySelectorAll('tbody tr').forEach(tr => {
                        const name = tr.querySelector('.name-col')?.textContent.toLowerCase() || '';
                        const email = tr.querySelector('.email-col')?.textContent.toLowerCase() || '';
                        tr.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
                    });
                });
            });
        </script>
    @endpush
@endsection
