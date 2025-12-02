@extends('layouts.app')
@section('title', 'ویرایش کلاس')

@push('styles')
    <style>
        .form-shell {
            background: radial-gradient(900px circle at 100% -30%, rgba(13, 110, 253, .12), transparent 55%), linear-gradient(180deg, #fff, #f8fafc);
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 10px 30px rgba(18, 38, 63, .08)
        }

        .form-card {
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

        .meta-pill {
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 999px;
            padding: .25rem .6rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-weight: 700;
            font-size: .85rem;
            color: #475569
        }

        .btn-wizard {
            border-radius: 1rem;
            padding: .7rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }

        .preview-card {
            border: 1px dashed #e2e8f0;
            border-radius: 1.25rem;
            background: linear-gradient(180deg, #fff, #f8fafc)
        }

        .floating {
            position: sticky;
            top: 90px
        }

        .danger-zone {
            border: 1px solid rgba(220, 53, 69, .2);
            background: rgba(220, 53, 69, .04);
            border-radius: 1rem
        }

        .copy-btn {
            border-radius: .8rem
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="form-shell mb-4 fade-up">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 text-end">
                <div>
                    <h1 class="h4 fw-bold mb-1"><i class="bi bi-pencil-square me-1 text-warning"></i> ویرایش کلاس</h1>
                    <p class="text-muted mb-0">اطلاعات کلاس را به‌روزرسانی کن یا آن را آرشیو/حذف کن.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('teacher.classes.show', $class) }}"
                        class="btn btn-outline-primary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-eye"></i> مشاهده کلاس
                    </a>
                    <a href="{{ route('teacher.classes.index') }}"
                        class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="bi bi-arrow-right"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        {{-- Errors / Success --}}
        @if ($errors->any())
            <div class="alert alert-danger fade-up">
                <div class="fw-semibold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> لطفاً خطاهای زیر را بررسی
                    کن:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success fade-up"><i class="bi bi-check-circle-fill me-1"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

            {{-- Form column --}}
            <div class="col-lg-8">
                <div class="form-card p-3 p-md-4 fade-up">

                    <form action="{{ route('teacher.classes.update', $class) }}" method="POST" class="row g-3" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="col-12">
                            <label class="form-label fw-semibold">نام کلاس <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control input-soft" required
                                value="{{ old('title', $class->title) }}" placeholder="مثلاً: ریاضی نهم - کلاس A">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">درس</label>
                            <input type="text" name="subject" class="form-control input-soft"
                                value="{{ old('subject', $class->subject) }}" placeholder="مثلاً ریاضی، علوم، زبان...">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">پایه</label>
                            <select name="grade" class="form-select input-soft">
                                @php $grade = old('grade', (string)($class->grade ?? '')); @endphp
                                <option value="" {{ $grade === '' ? 'selected' : '' }}>انتخاب پایه</option>
                                <option value="7" {{ $grade === '7' ? 'selected' : '' }}>هفتم</option>
                                <option value="8" {{ $grade === '8' ? 'selected' : '' }}>هشتم</option>
                                <option value="9" {{ $grade === '9' ? 'selected' : '' }}>نهم</option>
                                <option value="10" {{ $grade === '10' ? 'selected' : '' }}>دهم</option>
                                <option value="11" {{ $grade === '11' ? 'selected' : '' }}>یازدهم</option>
                                <option value="12" {{ $grade === '12' ? 'selected' : '' }}>دوازدهم</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">توضیحات کلاس</label>
                            <textarea name="description" rows="4" class="form-control input-soft"
                                placeholder="توضیح کوتاه دربارهٔ اهداف، منابع، زمان‌بندی و...">{{ old('description', $class->description) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">وضعیت کلاس</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="activeSwitch" name="is_active"
                                    value="1" {{ old('is_active', $class->is_active ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activeSwitch">کلاس فعال باشد</label>
                            </div>
                            <div class="text-muted small mt-1">کلاس غیرفعال برای آرشیو یا کلاس‌های تمام‌شده مناسب است.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">کد ورود دانش‌آموز</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-soft" id="joinCode"
                                    value="{{ $class->join_code }}" readonly>
                                <button type="button" class="btn btn-outline-secondary copy-btn" id="copyCodeBtn">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="text-muted small mt-1">این کد را برای عضویت دانش‌آموزان ارسال کن.</div>
                        </div>

                        <div class="col-12 d-flex flex-wrap gap-2 mt-2">
                            <button type="submit" class="btn btn-primary btn-wizard shadow-sm">
                                <i class="bi bi-save2"></i> ذخیره تغییرات
                            </button>
                            <a href="{{ route('teacher.classes.show', $class) }}"
                                class="btn btn-outline-primary btn-wizard">
                                مشاهده
                            </a>
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-secondary btn-wizard">
                                انصراف
                            </a>
                        </div>
                    </form>

                    {{-- Danger zone delete --}}
                    <div class="danger-zone p-3 mt-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <div class="fw-bold text-danger"><i class="bi bi-trash3 me-1"></i> حذف کلاس</div>
                                <div class="small text-muted">این عملیات غیرقابل بازگشت است.</div>
                            </div>
                            <form action="{{ route('teacher.classes.destroy', $class) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-wizard"
                                    onclick="return confirm('آیا از حذف این کلاس مطمئن هستید؟')">
                                    حذف قطعی
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Right column preview/help --}}
            <div class="col-lg-4">
                <div class="floating fade-up">

                    <div class="preview-card p-3 mb-3">
                        <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> پیش‌نمایش کلاس</div>
                        <div class="border rounded-3 p-3 bg-white" id="livePreview">
                            <div class="fw-semibold fs-6" id="pvTitle">{{ $class->title }}</div>
                            <div class="text-muted small mt-2" id="pvSubject">درس: {{ $class->subject ?? '—' }}</div>
                            <div class="text-muted small mt-1" id="pvGrade">پایه: {{ $class->grade ?? '—' }}</div>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <span class="meta-pill" id="pvActive"><i class="bi bi-broadcast"></i>
                                    {{ $class->is_active ?? 1 ? 'فعال' : 'آرشیو' }}</span>
                                <span class="meta-pill" id="pvCode"><i class="bi bi-key"></i> کد:
                                    {{ $class->join_code }}</span>
                            </div>
                            @if ($class->description)
                                <div class="text-muted small mt-3" id="pvDesc">{{ $class->description }}</div>
                            @else
                                <div class="text-muted small mt-3" id="pvDesc"></div>
                            @endif
                        </div>
                    </div>

                    <div class="form-card p-3">
                        <div class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i> نکته‌ها</div>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>اگر کلاس تمام شده، آن را آرشیو کن.</li>
                            <li>برای امنیت، کد ورود را فقط به اعضای کلاس بده.</li>
                            <li>می‌توانی با یک آزمون کوتاه سطح کلاس را بسنجی.</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const title = document.querySelector('input[name="title"]');
                const subject = document.querySelector('input[name="subject"]');
                const grade = document.querySelector('select[name="grade"]');
                const desc = document.querySelector('textarea[name="description"]');
                const active = document.getElementById('activeSwitch');

                const pvTitle = document.getElementById('pvTitle');
                const pvSubject = document.getElementById('pvSubject');
                const pvGrade = document.getElementById('pvGrade');
                const pvDesc = document.getElementById('pvDesc');
                const pvActive = document.getElementById('pvActive');

                const joinCode = document.getElementById('joinCode');
                const copyBtn = document.getElementById('copyCodeBtn');

                // live preview
                title?.addEventListener('input', () => {
                    pvTitle.textContent = title.value || 'نام کلاس';
                });
                subject?.addEventListener('input', () => {
                    pvSubject.textContent = 'درس: ' + (subject.value || '—');
                });
                grade?.addEventListener('change', () => {
                    pvGrade.textContent = 'پایه: ' + (grade.value || '—');
                });
                desc?.addEventListener('input', () => {
                    pvDesc.textContent = desc.value || '';
                });
                active?.addEventListener('change', () => {
                    pvActive.innerHTML = active.checked ?
                        '<i class="bi bi-broadcast"></i> فعال' :
                        '<i class="bi bi-archive"></i> آرشیو';
                });

                // copy join code
                copyBtn?.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(joinCode.value);
                        copyBtn.classList.remove('btn-outline-secondary');
                        copyBtn.classList.add('btn-success');
                        copyBtn.innerHTML = '<i class="bi bi-check2"></i>';
                        setTimeout(() => {
                            copyBtn.classList.remove('btn-success');
                            copyBtn.classList.add('btn-outline-secondary');
                            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i>';
                        }, 1200);
                    } catch (e) {
                        alert('کپی انجام نشد.');
                    }
                });
            });
        </script>
    @endpush
@endsection
