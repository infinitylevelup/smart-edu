{{-- resources/views/dashboard/teacher/exams/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'ویرایش آزمون')

@push('styles')
    <style>
        .form-card {
            border: 0;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06)
        }

        .step-pill {
            border-radius: 999px;
            padding: .35rem .8rem;
            font-weight: 700;
            font-size: .85rem;
            background: #f8fafc;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: .4rem
        }

        .step-pill .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, .12)
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

        .hint {
            font-size: .85rem;
            color: #6c757d
        }

        .preview-card {
            border: 1px dashed #e2e8f0;
            border-radius: 1.25rem;
            background: linear-gradient(180deg, #fff, #f8fafc)
        }

        .preview-badge {
            background: rgba(16, 185, 129, .12);
            color: #059669;
            font-weight: 800
        }

        .btn-wizard {
            border-radius: 1rem;
            padding: .7rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem
        }

        .floating-help {
            position: sticky;
            top: 90px
        }

        .danger-zone {
            border: 1px solid rgba(220, 53, 69, .2);
            background: rgba(220, 53, 69, .04);
            border-radius: 1rem
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2 fade-up">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-pencil-square me-1 text-warning"></i> ویرایش آزمون</h4>
                <div class="text-muted small">
                    تغییرات را اعمال کن و ذخیره بزن. وضعیت انتشار و تاریخ را هم می‌توانی آپدیت کنی.
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                   class="btn btn-outline-primary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-question-circle"></i>
                    مدیریت سوال‌ها
                </a>
                <a href="{{ route('teacher.exams.index') }}"
                   class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-arrow-right"></i>
                    بازگشت
                </a>
            </div>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger fade-up">
                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    لطفاً خطاهای زیر را بررسی کن:
                </div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success fade-up">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

            {{-- Form column --}}
            <div class="col-lg-8">
                <div class="form-card p-3 p-md-4 fade-up">

                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                        <div class="step-pill"><span class="dot"></span> ویرایش اطلاعات آزمون</div>
                        <div class="hint">* فیلدهای ستاره‌دار ضروری‌اند.</div>
                    </div>

                    <form action="{{ route('teacher.exams.update', $exam) }}" method="POST" class="row g-3" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">عنوان آزمون <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="title"
                                   class="form-control input-soft"
                                   placeholder="مثلاً: آزمون فصل ۱ ریاضی"
                                   value="{{ old('title', $exam->title) }}"
                                   required>
                        </div>

                        {{-- Scope + Classroom --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">نوع آزمون <span class="text-danger">*</span></label>

@php
    // 1) اگر فرم با خطا برگشته، مقدار همان old('scope')
    $scopeVal = old('scope');

    // 2) اگر هنوز مقدار تعیین نشده:
    if (!$scopeVal) {
        if (!empty($exam->scope)) {
            // اگر توی DB scope داریم، همان را بگیر
            $scopeVal = $exam->scope;
        } else {
            // اگر scope خالی است، از نوع آزمون حدس بزن
            // public = آزاد    |   بقیه = کلاسی
            $scopeVal = $exam->exam_type === 'public' ? 'free' : 'classroom';
        }
    }
@endphp

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       type="radio"
                                       name="scope"
                                       id="editScopeClassroom"
                                       value="classroom"
                                    {{ $scopeVal === 'classroom' ? 'checked' : '' }}>
                                <label class="form-check-label" for="editScopeClassroom">کلاسی</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       type="radio"
                                       name="scope"
                                       id="editScopeFree"
                                       value="free"
                                    {{ $scopeVal === 'free' ? 'checked' : '' }}>
                                <label class="form-check-label" for="editScopeFree">آزاد</label>
                            </div>

                            <div class="hint mt-1">اگر آزمون آزاد باشد، به هیچ کلاسی وصل نیست.</div>
                        </div>

                        <div class="col-md-6" id="editClassroomBox">
                            <label class="form-label fw-semibold">کلاس مربوطه <span class="text-danger">*</span></label>
                            <select name="classroom_id" class="form-select input-soft">
                                <option value="">انتخاب کلاس...</option>
                                @foreach ($classrooms as $c)
                                    <option value="{{ $c->id }}"
                                        {{ (string) old('classroom_id', $exam->classroom_id) === (string) $c->id ? 'selected' : '' }}>
                                        {{ $c->title ?? $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Subject & Level --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">درس / موضوع <span class="text-danger">*</span></label>
                            <select name="subject" class="form-select input-soft" required>
                                <option value="" disabled {{ old('subject', $exam->subject) ? '' : 'selected' }}>انتخاب درس</option>
                                @foreach ($subjects ?? [] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('subject', $exam->subject) === $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">سطح آزمون <span class="text-danger">*</span></label>
                            @php $lvl = old('level', $exam->level ?? 'taghviyati'); @endphp
                            <select name="level" class="form-select input-soft" required>
                                <option value="taghviyati" {{ $lvl === 'taghviyati' ? 'selected' : '' }}>تقویتی</option>
                                <option value="konkur" {{ $lvl === 'konkur' ? 'selected' : '' }}>کنکور</option>
                                <option value="olympiad" {{ $lvl === 'olympiad' ? 'selected' : '' }}>المپیاد</option>
                            </select>
                        </div>

                        {{-- Duration & Start time --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">مدت آزمون (دقیقه) <span class="text-danger">*</span></label>
                            <input type="number"
                                   min="1"
                                   name="duration"
                                   class="form-control input-soft"
                                   value="{{ old('duration', $exam->duration_minutes) }}"
                                   required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">تاریخ و زمان شروع</label>
                            <input type="datetime-local"
                                   name="start_at"
                                   class="form-control input-soft"
                                   value="{{ old('start_at', optional($exam->start_at)->format('Y-m-d\TH:i')) }}">
                            <div class="hint mt-1">تاریخ/زمان را برای هماهنگی برگزاری تنظیم کن.</div>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">توضیحات</label>
                            <textarea name="description"
                                      rows="4"
                                      class="form-control input-soft"
                                      placeholder="توضیحات کوتاه درباره هدف آزمون...">{{ old('description', $exam->description) }}</textarea>
                        </div>

                        {{-- Publish toggle --}}
                        <div class="col-12">
                            @php $pub = old('is_published', $exam->is_published); @endphp
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="publishSwitch"
                                       name="is_published"
                                       value="1"
                                    {{ $pub ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="publishSwitch">آزمون منتشر باشد</label>
                            </div>
                            <div class="hint mt-1">در صورت انتشار، آزمون برای دانش‌آموزها قابل مشاهده می‌شود.</div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 d-flex flex-wrap gap-2 mt-2">
                            <button type="submit" class="btn btn-primary btn-wizard shadow-sm">
                                <i class="bi bi-save2"></i>
                                ذخیره تغییرات
                            </button>
                            <a href="{{ route('teacher.exams.questions.index', $exam) }}"
                               class="btn btn-outline-primary btn-wizard shadow-sm">
                                <i class="bi bi-question-circle"></i>
                                رفتن به سوال‌ها
                            </a>
                        </div>

                    </form>

                    {{-- Danger zone --}}
                    <div class="danger-zone p-3 mt-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <div class="fw-bold text-danger">
                                    <i class="bi bi-trash3 me-1"></i> حذف آزمون
                                </div>
                                <div class="small text-muted">این عملیات غیرقابل بازگشت است.</div>
                            </div>
                            <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-wizard"
                                        onclick="return confirm('آیا از حذف آزمون مطمئن هستید؟')">
                                    حذف قطعی
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Preview / tips column --}}
            <div class="col-lg-4">
                <div class="floating-help fade-up">

                    <div class="preview-card p-3 mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="fw-bold">
                                <i class="bi bi-eye me-1"></i> پیش‌نمایش سریع
                            </div>
                            <span class="badge preview-badge">Live</span>
                        </div>
                        <div class="small text-muted mb-2">با تغییر فرم، این کارت آپدیت می‌شود.</div>

                        <div class="border rounded-3 p-3 bg-white" id="livePreview">
                            <div class="fw-semibold" id="pvTitle">{{ $exam->title }}</div>
                            <div class="text-muted small mt-1" id="pvMeta">
                                {{ $exam->level }} • {{ $exam->subject }} • {{ $exam->duration_minutes }} دقیقه
                            </div>
                            <div class="text-muted small mt-2" id="pvStart">
                                شروع: {{ optional($exam->start_at)->format('Y/m/d H:i') }}
                            </div>
                            <div class="mt-2">
                                @if ($exam->is_published)
                                    <span class="badge bg-success">
                                        <i class="bi bi-broadcast-pin me-1"></i> منتشر شده
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-pencil-square me-1"></i> پیش‌نویس
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-card p-3">
                        <div class="fw-bold mb-2">
                            <i class="bi bi-lightbulb me-1 text-warning"></i> پیشنهادهای بهبود
                        </div>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>قبل از انتشار، حداقل ۵ سؤال اضافه کن.</li>
                            <li>اگر زمان برگزاری تغییر کرد، اطلاعیه بفرست.</li>
                            <li>می‌توانی بعداً مدت و سطح را اصلاح کنی.</li>
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
                const subject = document.querySelector('select[name="subject"]');
                const level = document.querySelector('select[name="level"]');
                const duration = document.querySelector('input[name="duration"]');
                const startAt = document.querySelector('input[name="start_at"]');

                const pvTitle = document.getElementById('pvTitle');
                const pvMeta = document.getElementById('pvMeta');
                const pvStart = document.getElementById('pvStart');

                function updatePreview() {
                    if (pvTitle) pvTitle.textContent = title?.value?.trim() || 'عنوان آزمون';
                    const s = subject?.value || 'درس';
                    const l = level?.value || 'سطح';
                    const d = duration?.value || '—';
                    if (pvMeta) pvMeta.textContent = `${l} • ${s} • ${d} دقیقه`;
                    if (pvStart) pvStart.textContent = startAt?.value
                        ? `شروع: ${startAt.value.replace('T', ' ')}`
                        : 'زمان شروع';
                }

                [title, subject, level, duration, startAt].forEach(el =>
                    el?.addEventListener('input', updatePreview)
                );
                updatePreview();
            });

            // toggle classroom box
            const editScopeFree = document.getElementById('editScopeFree');
            const editScopeClassroom = document.getElementById('editScopeClassroom');
            const editClassroomBox = document.getElementById('editClassroomBox');
            const editClassroomSelect = editClassroomBox?.querySelector('select[name="classroom_id"]');

            function toggleEditClassroom() {
                const isFree = editScopeFree?.checked;
                if (editClassroomBox) editClassroomBox.style.display = isFree ? 'none' : 'block';
                if (editClassroomSelect) editClassroomSelect.required = !isFree;
            }

            editScopeFree?.addEventListener('change', toggleEditClassroom);
            editScopeClassroom?.addEventListener('change', toggleEditClassroom);
            toggleEditClassroom();
        </script>
    @endpush
@endsection
