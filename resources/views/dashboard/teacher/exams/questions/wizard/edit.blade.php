@extends('layouts.app')
@section('title', 'ویرایش سوال (ویزارد)')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/question-wizard.css') }}">
@endpush

@section('content')
@php
    $examMode = $exam->exam_mode ?? 'single_subject';
    $isMulti  = $examMode === 'multi_subject';

    $examSubjects = $subjects ?? ($exam->subjects ?? collect());
    $primarySubject = $examSubjects->first();

    $singleSubjectLabel = $primarySubject->title_fa
        ?? $exam->subject
        ?? 'بدون نام';

    // ✅ FIX: مقدار DB را برای UI نرمال کن تا option درست selected شود
    $qtDb = old('question_type', $question->question_type);
    $qt   = \App\Enums\QuestionType::toUi($qtDb);

    $opts     = old('options', $question->options ?? []);
    $ca       = old('correct_answer', $question->correct_answer ?? []);
    $tfVal    = $ca['value']  ?? null;
    $fillVals = $ca['values'] ?? [''];

    $initialScore   = old('score', $question->score);
    $initialActive  = old('is_active', $question->is_active);
    $initialContent = old('content', $question->content);
    $initialExplain = old('explanation', $question->explanation);

    $initialSubjectId = old('subject_id', $question->subject_id ?? null);
@endphp

<div class="qw-container container-fluid">

    {{-- HEADER --}}
    <div class="qw-page-header">
        <div class="qw-header-content">
            <div class="qw-header-title">
                <h1><span class="qw-gradient-text">ویرایش سوال</span> ✏️</h1>
                <p class="qw-header-subtitle">
                    در حال ویرایش سوال مربوط به آزمون
                    <strong>«{{ $exam->title ?? 'بدون عنوان' }}»</strong>
                    هستی.
                </p>

                <div class="qw-exam-meta">
                    <span class="badge bg-primary-subtle text-primary qw-badge-pill">
                        حالت آزمون: {{ $examMode === 'multi_subject' ? 'چنددرسی (جامع)' : 'تک‌درس' }}
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

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="qw-card">

                {{-- STEPS --}}
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
                            لطفاً خطاهای زیر را بررسی کن:
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
                    action="{{ route('teacher.exams.questions.update', [$exam, $question]) }}"
                    method="POST"
                    novalidate
                >
                    @csrf
                    @method('PUT')

                    {{-- STEP 1 --}}
                    <div class="qw-step-content active" data-step="1">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">📌</div>
                            <h2 class="qw-section-title">اطلاعات کلی سوال</h2>
                            <p class="qw-section-desc">
                                می‌توانی درس سوال (در آزمون چنددرسی)، امتیاز و وضعیت فعال بودن را تنظیم کنی.
                            </p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label qw-label">حالت آزمون</label>
                                <input type="text" class="form-control qw-input"
                                       value="{{ $examMode === 'multi_subject' ? 'چنددرسی (جامع)' : 'تک‌درس' }}" disabled>
                            </div>

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
                                                {{ (string)$initialSubjectId === (string)$subj->id ? 'selected' : '' }}
                                            >
                                                {{ $subj->title_fa }}
                                                @if($subj->code) ({{ $subj->code }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control qw-input" value="{{ $singleSubjectLabel }}" disabled>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label class="form-label qw-label">امتیاز سوال</label>
                                <input type="number" name="score" id="qwScoreInput"
                                       class="form-control qw-input @error('score') is-invalid @enderror"
                                       value="{{ $initialScore }}" min="0" step="0.25">
                                @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check form-switch qw-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           name="is_active" id="qwIsActive" value="1"
                                           {{ $initialActive ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="qwIsActive">سوال فعال باشد</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label qw-label">سطح دشواری (آینده)</label>
                                <select name="difficulty" id="qwDifficulty" class="form-select qw-input" disabled>
                                    <option value="">(به‌زودی)</option>
                                    <option value="easy">آسان</option>
                                    <option value="normal">متوسط</option>
                                    <option value="hard">سخت</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="qw-step-content" data-step="2">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">✏️</div>
                            <h2 class="qw-section-title">متن سوال و نوع آن</h2>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">متن سوال <span class="text-danger">*</span></label>
                            <textarea name="content" id="qwContent" rows="5"
                                      class="form-control qw-input @error('content') is-invalid @enderror">{{ $initialContent }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label qw-label">نوع سوال <span class="text-danger">*</span></label>
                                <select name="question_type" id="qwQuestionType"
                                        class="form-select qw-input @error('question_type') is-invalid @enderror">
                                    <option value="mcq"        {{ $qt === 'mcq' ? 'selected' : '' }}>تستی (چهارگزینه‌ای)</option>
                                    <option value="true_false" {{ $qt === 'true_false' ? 'selected' : '' }}>درست / نادرست</option>
                                    <option value="fill_blank" {{ $qt === 'fill_blank' ? 'selected' : '' }}>جای خالی</option>
                                    <option value="essay"      {{ $qt === 'essay' ? 'selected' : '' }}>تشریحی</option>
                                </select>
                                @error('question_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="qw-step-content" data-step="3">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">✅</div>
                            <h2 class="qw-section-title">پاسخ‌ها و کلید سوال</h2>
                        </div>

                        <div id="qwMcqBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">گزینه‌های سوال تستی</div>
                            @foreach(['a','b','c','d'] as $opt)
                                <div class="input-group mb-2">
                                    <span class="input-group-text">{{ strtoupper($opt) }}</span>
                                    <input type="text" name="options[{{ $opt }}]" class="form-control qw-input"
                                           value="{{ $opts[$opt] ?? '' }}" placeholder="متن گزینه {{ strtoupper($opt) }}">
                                    <span class="input-group-text bg-white">
                                        <input class="form-check-input mt-0" type="radio"
                                               name="correct_answer[correct_option]" value="{{ $opt }}"
                                               {{ ($ca['correct_option'] ?? null) === $opt ? 'checked' : '' }}>
                                        <span class="ms-1 small">صحیح</span>
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div id="qwTfBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">جواب صحیح (درست / نادرست)</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer[value]"
                                           id="qwTfTrue" value="1" {{ (string)$tfVal === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="qwTfTrue">درست</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer[value]"
                                           id="qwTfFalse" value="0" {{ (string)$tfVal === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="qwTfFalse">نادرست</label>
                                </div>
                            </div>
                        </div>

                        <div id="qwFillBlock" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">جواب‌های صحیح (جای خالی)</div>
                            <div id="qwFillContainer">
                                @foreach($fillVals as $idx => $val)
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

                        <div id="qwEssayInfo" class="qw-answer-block" style="display:none;">
                            <div class="qw-block-title">سوال تشریحی</div>
                            <div class="qw-hint">کلید و نکات تصحیح را در بخش توضیح بنویس.</div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="qw-step-content" data-step="4">
                        <div class="qw-section-header">
                            <div class="qw-section-icon">📚</div>
                            <h2 class="qw-section-title">اطلاعات آموزشی و منابع</h2>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">توضیح / راهنمای سوال (اختیاری)</label>
                            <textarea name="explanation" id="qwExplanation" rows="3" class="form-control qw-input">{{ $initialExplain }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label qw-label">لینک‌های آموزشی مرتبط</label>
                            <div id="qwLinksContainer"></div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="qwAddLink">
                                <i class="bi bi-link-45deg"></i>
                                افزودن لینک آموزشی
                            </button>
                        </div>
                    </div>

                    {{-- NAV --}}
                    <div class="qw-nav-buttons">
                        <button type="button" class="btn qw-btn-nav qw-btn-prev" id="qwPrevBtn">
                            <i class="bi bi-arrow-right"></i> مرحله قبل
                        </button>

                        <button type="button" class="btn qw-btn-nav qw-btn-next" id="qwNextBtn">
                            مرحله بعد <i class="bi bi-arrow-left"></i>
                        </button>

                        <button type="submit" class="btn qw-btn-nav qw-btn-submit" id="qwSubmitBtn">
                            <i class="bi bi-save2"></i> ذخیره تغییرات
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- RIGHT: PREVIEW --}}
        <div class="col-lg-4 d-none d-lg-block">
            <div class="qw-preview-card">
                <div class="qw-preview-header">
                    <div>
                        <div class="qw-preview-title"><i class="bi bi-eye me-1"></i> پیش‌نمایش سوال</div>
                        <div class="qw-preview-subtitle">تغییرات فرم، به‌صورت زنده اینجا نمایش داده می‌شود.</div>
                    </div>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Edit</span>
                </div>

                <div class="qw-preview-body" id="qwPreview">
                    <div class="qw-preview-badge-row">
                        <span class="badge bg-primary-subtle text-primary" id="qwPreviewTypeBadge">
                            نوع سوال: {{ $qt ?: '—' }}
                        </span>
                        <span class="badge bg-info-subtle text-info" id="qwPreviewScore">
                            امتیاز: {{ $initialScore ?? 0 }}
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary" id="qwPreviewStatus">
                            وضعیت: {{ $initialActive ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </div>

                    <div class="qw-preview-question" id="qwPreviewContent">
                        {{ $initialContent ?: 'متنی برای سوال ثبت نشده است.' }}
                    </div>

                    <div class="qw-preview-explanation">
                        <div class="qw-preview-explanation-title">توضیح / راهنما</div>
                        <div id="qwPreviewExplanation" class="qw-preview-explanation-body">
                            {{ $initialExplain ?: 'هنوز توضیحی ثبت نشده است.' }}
                        </div>
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
