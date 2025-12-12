@extends('layouts.app')
@section('title', 'سوال جدید (ویزارد)')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/question-wizard.css') }}">
@endpush

@section('content')
@php
    $examMode = $exam->exam_mode ?? 'single_subject';
    $isMulti  = $examMode === 'multi_subject';

    // ✅ اگر کنترلر subjects پاس داده باشد، همان را استفاده کن
    $examSubjects = $subjects ?? ($exam->subjects ?? collect());
    $primarySubject = $examSubjects->first();

    // برای نمایش نام درس در حالت تک‌درس
    $singleSubjectLabel = $primarySubject->title_fa
        ?? $exam->subject
        ?? 'بدون نام';
@endphp

<div class="qw-container container-fluid">

    {{-- HEADER --}}
    <div class="qw-page-header">
        <div class="qw-header-content">
            <div class="qw-header-title">
                <h1>
                    <span class="qw-gradient-text">افزودن سوال جدید</span> 📝
                </h1>
                <p class="qw-header-subtitle">
                    سوال جدید برای آزمون
                    <strong>«{{ $exam->title ?? 'بدون عنوان' }}»</strong>
                    ثبت می‌شود.
                </p>

                <div class="qw-exam-meta">
                    <span class="badge bg-primary-subtle text-primary qw-badge-pill">
                        حالت آزمون:
                        {{ $examMode === 'multi_subject' ? 'چنددرسی (جامع)' : 'تک‌درس' }}
                    </span>

                    @if($examMode === 'single_subject')
                        <span class="badge bg-info-subtle text-info qw-badge-pill">
                            درس آزمون: {{ $singleSubjectLabel }}
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary qw-badge-pill">
                            تعداد درس‌های آزمون: {{ $examSubjects->count() }}
                        </span>
                    @endif
                </div>
            </div>

            <a href="{{ route('teacher.exams.questions.index', $exam) }}" class="qw-btn-back">
                <i class="bi bi-arrow-right"></i>
                بازگشت به سوال‌ها
            </a>
        </div>
    </div>

    {{-- WIZARD WRAPPER --}}
    <div class="row g-3">

        {{-- LEFT: FORM --}}
        <div class="col-lg-8">
            <div class="qw-card">

                {{-- STEPS PROGRESS --}}
                <div class="qw-steps-wrapper">
                    <div class="qw-progress-bar">
                        <div class="qw-progress-fill" id="qwProgressFill"></div>
                    </div>
                    <div class="qw-steps">
                        <div class="qw-step active" data-step="1">
                            <div class="qw-step-number">۱</div>
                            <div class="qw-step-label">اطلاعات کلی</div>
                        </div>
                        <div class="qw-step" data-step="2">
                            <div class="qw-step-number">۲</div>
                            <div class="qw-step-label">متن و نوع سوال</div>
                        </div>
                        <div class="qw-step" data-step="3">
                            <div class="qw-step-number">۳</div>
                            <div class="qw-step-label">پاسخ‌ها و کلید</div>
                        </div>
                        <div class="qw-step" data-step="4">
                            <div class="qw-step-number">۴</div>
                            <div class="qw-step-label">متادیتا و منابع</div>
                        </div>
                    </div>
                </div>

                {{-- ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger qw-alert">
                        <div class="fw-semibold mb-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            لطفاً موارد زیر را اصلاح کن:
                        </div>
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    id="questionWizardForm"
                    action="{{ route('teacher.exams.questions.store', $exam) }}"
                    method="POST"
                    novalidate
                >
                    @csrf

                    {{-- STEP 1 --}}
                    <div class="qw-step-content active" data-step="1">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">📌</div>
                            <h2 class="qw-section-title">اطلاعات کلی سوال</h2>
                            <p class="qw-section-desc">
                                حالت آزمون، درس (در صورت چنددرسی) و وضعیت فعال بودن سوال را مشخص کن.
                            </p>
                        </div>

                        <div class="row g-3">

                            {{-- Exam mode (read-only) --}}
                            <div class="col-md-6">
                                <label class="form-label qw-label">حالت آزمون</label>
                                <input
                                    type="text"
                                    class="form-control qw-input"
                                    value="{{ $examMode === 'multi_subject' ? 'چنددرسی (جامع)' : 'تک‌درس' }}"
                                    disabled
                                >
                                <div class="form-text qw-hint">
                                    در صورت چنددرسی، باید برای هر سوال درس را مشخص کنی.
                                </div>
                            </div>

                            {{-- Subject select --}}
                            <div class="col-md-6">
                                <label class="form-label qw-label">
                                    درس سوال
                                    @if($isMulti) <span class="text-danger">*</span> @endif
                                </label>

                                @if($isMulti)
                                    <select name="subject_id" id="qwSubjectSelect" class="form-select qw-input">
                                        <option value="">انتخاب درس...</option>
                                        @foreach($examSubjects as $subj)
                                            <option
                                                value="{{ $subj->id }}"
                                                data-title="{{ $subj->title_fa }}"
                                                {{ old('subject_id') == $subj->id ? 'selected' : '' }}
                                            >
                                                {{ $subj->title_fa }}
                                                @if($subj->code) ({{ $subj->code }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text qw-hint">
                                        فقط درس‌هایی نمایش داده می‌شوند که برای این آزمون تعریف شده‌اند.
                                    </div>
                                @else
                                    <input type="text" class="form-control qw-input" value="{{ $singleSubjectLabel }}" disabled>
                                    <div class="form-text qw-hint">
                                        در حالت تک‌درس، تمام سوال‌ها به همین درس متصل می‌شوند.
                                    </div>
                                @endif
                            </div>

                            {{-- Score --}}
                            <div class="col-md-4">
                                <label class="form-label qw-label">امتیاز سوال</label>
                                <input
                                    type="number"
                                    name="score"
                                    id="qwScoreInput"
                                    class="form-control qw-input @error('score') is-invalid @enderror"
                                    value="{{ old('score', 1) }}"
                                    min="0"
                                    step="0.25"
                                >
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text qw-hint">می‌توانی بعداً امتیاز را ویرایش کنی.</div>
                            </div>

                            {{-- Active toggle --}}
                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check form-switch qw-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        role="switch"
                                        name="is_active"
                                        id="qwIsActive"
                                        value="1"
                                        {{ old('is_active', 1) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label fw-semibold" for="qwIsActive">
                                        سوال فعال باشد
                                    </label>
                                </div>
                            </div>

                            {{-- Difficulty (future) --}}
                            <div class="col-md-4">
                                <label class="form-label qw-label">سطح دشواری (اختیاری - آینده)</label>
                                <select name="difficulty" id="qwDifficulty" class="form-select qw-input" disabled>
                                    <option value="">(به‌زودی)</option>
                                    <option value="easy">آسان</option>
                                    <option value="normal">متوسط</option>
                                    <option value="hard">سخت</option>
                                </select>
                                <div class="form-text qw-hint">برای نسخهٔ فعلی هنوز به بک‌اند وصل نشده است.</div>
                            </div>

                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="qw-step-content" data-step="2">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">✏️</div>
                            <h2 class="qw-section-title">متن سوال و نوع آن</h2>
                            <p class="qw-section-desc">
                                متن کامل سوال را بنویس و نوع آن را مشخص کن تا بخش مناسب پاسخ‌ها فعال شود.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">متن سوال <span class="text-danger">*</span></label>
                            <textarea
                                name="content"
                                id="qwContent"
                                rows="5"
                                class="form-control qw-input @error('content') is-invalid @enderror"
                                placeholder="متن کامل سوال را بنویس..."
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text qw-hint">می‌توانی از LaTeX / فرمول‌ها در نسخه‌های بعدی پشتیبانی اضافه کنی.</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label qw-label">نوع سوال <span class="text-danger">*</span></label>
                                <select
                                    name="question_type"
                                    id="qwQuestionType"
                                    class="form-select qw-input @error('question_type') is-invalid @enderror"
                                >
                                    <option value="">انتخاب کنید...</option>
                                    <option value="mcq"        {{ old('question_type') === 'mcq' ? 'selected' : '' }}>تستی (چهارگزینه‌ای)</option>
                                    <option value="true_false" {{ old('question_type') === 'true_false' ? 'selected' : '' }}>درست / نادرست</option>
                                    <option value="fill_blank" {{ old('question_type') === 'fill_blank' ? 'selected' : '' }}>جای خالی</option>
                                    <option value="essay"      {{ old('question_type') === 'essay' ? 'selected' : '' }}>تشریحی</option>
                                </select>
                                @error('question_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text qw-hint">براساس این انتخاب، مرحلهٔ بعد فرم پاسخ‌ها تغییر می‌کند.</div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    @php
                        $oldOptions = old('options', []);
                        $oldCorrectOpt = old('correct_answer.correct_option');
                        $oldTF = old('correct_answer.value');
                        $oldFill = old('correct_answer.values', ['']);
                    @endphp

                    <div class="qw-step-content" data-step="3">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">✅</div>
                            <h2 class="qw-section-title">پاسخ‌ها و کلید سوال</h2>
                            <p class="qw-section-desc">
                                براساس نوع سوال، گزینه‌ها، پاسخ صحیح یا لیست پاسخ‌های صحیح را تنظیم کن.
                            </p>
                        </div>

                        {{-- MCQ --}}
                        <div id="qwMcqBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">گزینه‌های سوال تستی</div>
                            <div class="qw-hint mb-2">چهار گزینه وارد کن و یکی را به عنوان پاسخ صحیح انتخاب کن.</div>

                            @foreach(['a','b','c','d'] as $opt)
                                <div class="input-group mb-2">
                                    <span class="input-group-text">{{ strtoupper($opt) }}</span>
                                    <input
                                        type="text"
                                        name="options[{{ $opt }}]"
                                        class="form-control qw-input"
                                        value="{{ $oldOptions[$opt] ?? '' }}"
                                        placeholder="متن گزینه {{ strtoupper($opt) }}"
                                    >
                                    <span class="input-group-text bg-white">
                                        <input
                                            class="form-check-input mt-0"
                                            type="radio"
                                            name="correct_answer[correct_option]"
                                            value="{{ $opt }}"
                                            {{ $oldCorrectOpt === $opt ? 'checked' : '' }}
                                        >
                                        <span class="ms-1 small">صحیح</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- True/False --}}
                        <div id="qwTfBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">جواب صحیح (درست / نادرست)</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer[value]" id="qwTfTrue" value="1" {{ $oldTF === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="qwTfTrue">درست</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer[value]" id="qwTfFalse" value="0" {{ $oldTF === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="qwTfFalse">نادرست</label>
                                </div>
                            </div>
                        </div>

                        {{-- Fill blank --}}
                        <div id="qwFillBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">جواب‌های صحیح (جای خالی)</div>
                            <div class="qw-hint mb-2">برای هر جواب قابل قبول، یک خط جدا ثبت کن.</div>

                            <div id="qwFillContainer">
                                @foreach($oldFill as $idx => $val)
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">جواب {{ $idx + 1 }}</span>
                                        <input type="text" name="correct_answer[values][]" class="form-control qw-input" value="{{ $val }}">
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-outline-secondary btn-sm mt-1" id="qwAddFillAnswer">
                                <i class="bi bi-plus-circle"></i>
                                افزودن جواب دیگر
                            </button>
                        </div>

                        {{-- Essay --}}
                        <div id="qwEssayInfo" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">سوال تشریحی</div>
                            <div class="qw-hint">
                                برای سوال‌های تشریحی پاسخ از دانش‌آموز دریافت می‌شود و کلید را می‌توان در بخش «توضیح / راهنما» درج کرد.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="qw-step-content" data-step="4">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">📚</div>
                            <h2 class="qw-section-title">اطلاعات آموزشی و منابع</h2>
                            <p class="qw-section-desc">
                                توضیح سوال و لینک‌های آموزشی مرتبط را وارد کن تا تحلیل آزمون غنی‌تر شود.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">توضیح / راهنمای سوال (اختیاری)</label>
                            <textarea
                                name="explanation"
                                id="qwExplanation"
                                rows="3"
                                class="form-control qw-input"
                                placeholder="می‌توانی راه‌حل، نکات کلیدی یا توضیح تصحیح را اینجا بنویسی."
                            >{{ old('explanation') }}</textarea>
                            <div class="form-text qw-hint">
                                این متن در بخش تحلیل نتایج برای هنرجوها و یا معلم قابل استفاده است.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">لینک‌های آموزشی مرتبط (اختیاری، نامحدود)</label>
                            <div class="qw-hint mb-2">می‌توانی ویدیو، مقاله، جزوه و ... را اضافه کنی.</div>

                            <div id="qwLinksContainer"></div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="qwAddLink">
                                <i class="bi bi-link-45deg"></i>
                                افزودن لینک آموزشی
                            </button>

                            <div class="form-text qw-hint mt-1">
                                لینک‌ها در قالب JSON به بک‌اند ارسال می‌شوند.
                            </div>
                        </div>
                    </div>

                    {{-- NAV BUTTONS --}}
                    <div class="qw-nav-buttons">
                        <button type="button" class="btn qw-btn-nav qw-btn-prev" id="qwPrevBtn">
                            <i class="bi bi-arrow-right"></i> مرحله قبل
                        </button>

                        <button type="button" class="btn qw-btn-nav qw-btn-next" id="qwNextBtn">
                            مرحله بعد <i class="bi bi-arrow-left"></i>
                        </button>

                        <button type="submit" class="btn qw-btn-nav qw-btn-submit" id="qwSubmitBtn">
                            <i class="bi bi-check-circle"></i> ذخیره سوال
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- RIGHT: LIVE PREVIEW --}}
        <div class="col-lg-4 d-none d-lg-block">
            <div class="qw-preview-card">
                <div class="qw-preview-header">
                    <div>
                        <div class="qw-preview-title">
                            <i class="bi bi-eye me-1"></i>
                            پیش‌نمایش سوال
                        </div>
                        <div class="qw-preview-subtitle">
                            با تغییر فرم، این پیش‌نمایش به‌طور زنده آپدیت می‌شود.
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success fw-bold">Live</span>
                </div>

                <div class="qw-preview-body" id="qwPreview">
                    <div class="qw-preview-badge-row">
                        <span class="badge bg-primary-subtle text-primary" id="qwPreviewTypeBadge">نوع سوال: —</span>
                        <span class="badge bg-info-subtle text-info" id="qwPreviewScore">امتیاز: 1</span>
                        <span class="badge bg-secondary-subtle text-secondary" id="qwPreviewStatus">وضعیت: فعال</span>
                    </div>

                    <div class="qw-preview-question" id="qwPreviewContent">
                        هنوز متنی برای سوال وارد نشده است.
                    </div>

                    <div class="qw-preview-meta">
                        <div class="qw-preview-meta-item">
                            <span class="qw-preview-meta-label">درس:</span>
                            <span class="qw-preview-meta-value" id="qwPreviewSubject">
                                {{ $examMode === 'multi_subject' ? 'انتخاب نشده' : $singleSubjectLabel }}
                            </span>
                        </div>
                        <div class="qw-preview-meta-item">
                            <span class="qw-preview-meta-label">حالت آزمون:</span>
                            <span class="qw-preview-meta-value">
                                {{ $examMode === 'multi_subject' ? 'چنددرسی (جامع)' : 'تک‌درس' }}
                            </span>
                        </div>
                    </div>

                    <div class="qw-preview-answers">
                        <div class="qw-preview-answers-title">پیش‌نمایش پاسخ‌ها</div>
                        <div id="qwPreviewAnswersInner">نوع سوال را انتخاب کن تا پیش‌نمایش پاسخ‌ها نمایش داده شود.</div>
                    </div>

                    <div class="qw-preview-explanation">
                        <div class="qw-preview-explanation-title">توضیح / راهنما</div>
                        <div id="qwPreviewExplanation" class="qw-preview-explanation-body">هنوز توضیحی ثبت نشده است.</div>
                    </div>

                    <div class="qw-preview-links">
                        <div class="qw-preview-links-title">لینک‌های آموزشی</div>
                        <div id="qwPreviewLinks" class="qw-preview-links-body">لینکی ثبت نشده است.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/question-wizard.js') }}"></script>
@endpush
