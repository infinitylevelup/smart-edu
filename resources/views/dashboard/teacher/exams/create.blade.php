@extends('layouts.app')
@section('title', 'ساخت آزمون جدید')

@push('styles')
    @include('dashboard.teacher.exams.create-style')
@endpush

@section('content')
<div class="exam-container">

    <!-- ================================
         🌟 راهنمای جامع ایجاد آزمون
    ================================== -->
    <div class="guide-card glass-card mb-4">
        <h4 class="mb-3">
            <i class="bi bi-info-circle-fill text-primary me-2"></i>
            راهنمای ایجاد آزمون هوشمند
        </h4>
        
        <div class="row">
            <!-- آزمون آزاد -->
            <div class="col-md-6 mb-3">
                <div class="guide-item p-3">
                    <div class="guide-icon bg-primary">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h6 class="fw-bold">آزمون آزاد (رایگان)</h6>
                    <ul class="small text-muted mb-0">
                        <li>برای معرفی توانایی معلمان</li>
                        <li>دسترسی عمومی و رایگان</li>
                        <li>قابل اشتراک‌گذاری عمومی</li>
                        <li>بدون نیاز به عضویت در کلاس</li>
                    </ul>
                </div>
            </div>
            
            <!-- آزمون کلاسی -->
            <div class="col-md-6 mb-3">
                <div class="guide-item p-3">
                    <div class="guide-icon bg-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h6 class="fw-bold">آزمون کلاسی (پولی)</h6>
                    <ul class="small text-muted mb-0">
                        <li>نیاز به عضویت در کلاس</li>
                        <li>خرید کد ورود از پنل خرید</li>
                        <li>دو زیرنوع: تک‌درس و جامع</li>
                        <li>مدیریت پیشرفته دانش‌آموزان</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- هشدار مهم -->
        <div class="alert alert-warning mt-3 mb-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>
                    <strong class="d-block">توجه مهم:</strong>
                    <span class="small">نوع آزمون پس از ایجاد <strong>غیرقابل تغییر</strong> است. برای استفاده مجدد از آزمون، از <strong>بانک سوالات</strong> خود استفاده کنید.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================
         🌟 Step Bar
    ================================== -->
    <div class="step-bar">
        <div class="step-item active" id="stepIndicator1">
            <div class="step-number">1</div>
            <span class="step-title">نوع آزمون</span>
            <span class="step-desc">آزاد یا کلاسی</span>
        </div>
        <div class="step-item" id="stepIndicator2">
            <div class="step-number">2</div>
            <span class="step-title">دسته‌بندی</span>
            <span class="step-desc">پایه و شاخه</span>
        </div>
        <div class="step-item" id="stepIndicator3">
            <div class="step-number">3</div>
            <span class="step-title">درس‌ها</span>
            <span class="step-desc">تک‌درس یا جامع</span>
        </div>
        <div class="step-item" id="stepIndicator4">
            <div class="step-number">4</div>
            <span class="step-title">تنظیمات</span>
            <span class="step-desc">جزئیات آزمون</span>
        </div>
        <div class="step-item" id="stepIndicator5">
            <div class="step-number">5</div>
            <span class="step-title">پیش‌نمایش</span>
            <span class="step-desc">تایید نهایی</span>
        </div>
    </div>

    <!-- ================================
         🌟 فرم
    ================================== -->
    <form id="examForm" action="{{ route('teacher.exams.store') }}" method="POST">
        @csrf

        <!-- Hidden Inputs -->
        <input type="hidden" name="exam_type" id="exam_type">
        <input type="hidden" name="classroom_id" id="classroom_id">
        <input type="hidden" name="classroom_type" id="classroom_type">
        
        <input type="hidden" name="section_id" id="section_id">
        <input type="hidden" name="grade_id" id="grade_id">
        <input type="hidden" name="branch_id" id="branch_id">
        <input type="hidden" name="field_id" id="field_id">
        <input type="hidden" name="subfield_id" id="subfield_id">

        <input type="hidden" name="subject_type_id" id="subject_type_id">
        <input type="hidden" name="subjects" id="subjects_json">

        <!-- ================================
             🌟 STEP 1 — نوع آزمون
        ================================== -->
        <div class="wizard-step active" id="step1">
            <div class="glass-card">
                <h4 class="mb-3">نوع آزمون را انتخاب کنید</h4>
                <p class="text-muted mb-4">این انتخاب پس از ایجاد غیرقابل تغییر است</p>

                <div class="row">
                    <!-- آزمون آزاد -->
                    <div class="col-md-6 mb-4">
                        <div class="exam-type-card" id="examTypePublic" data-type="public">
                            <div class="exam-type-header bg-primary-gradient">
                                <i class="bi bi-globe"></i>
                                <h5>آزمون آزاد</h5>
                            </div>
                            <div class="exam-type-body">
                                <ul class="exam-features">
                                    <li><i class="bi bi-check-circle-fill text-success"></i> رایگان برای همه</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> بدون محدودیت کلاس</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> برای معرفی توانایی</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> نتیجه عمومی</li>
                                </ul>
                                <div class="text-center mt-3">
                                    <span class="badge bg-warning">پس از ایجاد غیرقابل تغییر</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- آزمون کلاسی -->
                    <div class="col-md-6 mb-4">
                        <div class="exam-type-card" id="examTypeClass" data-type="class">
                            <div class="exam-type-header bg-success-gradient">
                                <i class="bi bi-people-fill"></i>
                                <h5>آزمون کلاسی</h5>
                            </div>
                            <div class="exam-type-body">
                                <ul class="exam-features">
                                    <li><i class="bi bi-check-circle-fill text-success"></i> نیاز به عضویت در کلاس</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> خرید کد ورود</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> مدیریت دانش‌آموزان</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> گزارش‌گیری پیشرفته</li>
                                </ul>
                                <div class="text-center mt-3">
                                    <span class="badge bg-warning">پس از ایجاد غیرقابل تغییر</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- انتخاب نوع کلاس (فقط برای آزمون کلاسی) -->
            <div id="classExamBox" style="display:none;">
                <div class="glass-card mt-4">
                    <h4 class="mb-3">نوع کلاس را انتخاب کنید</h4>
                    
                    <div class="row">
                        <!-- کلاس تک‌درس -->
                        <div class="col-md-6 mb-3">
                            <div class="class-type-card" id="classTypeSingle">
                                <div class="class-type-icon">
                                    <i class="bi bi-book-half"></i>
                                </div>
                                <h5>کلاس تک‌درس</h5>
                                <p class="small text-muted">برای آزمون‌های متمرکز بر یک درس خاص</p>
                            </div>
                        </div>

                        <!-- کلاس جامع -->
                        <div class="col-md-6 mb-3">
                            <div class="class-type-card" id="classTypeComprehensive">
                                <div class="class-type-icon">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <h5>کلاس جامع</h5>
                                <p class="small text-muted">برای آزمون‌های شامل چندین درس مرتبط</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- انتخاب کلاس -->
                <div id="classSelectionArea" style="display:none;">
                    <div class="glass-card mt-3">
                        <h5>انتخاب کلاس</h5>
                        
                        <!-- کلاس‌های تک‌درس -->
                        <div id="singleClassSection" style="display:none;">
                            @php $single = $classrooms->where('classroom_type','single'); @endphp
                            @if($single->count() > 0)
                                <div class="class-list">
                                    @foreach($single as $c)
                                        <div class="class-item" data-id="{{ $c->id }}" data-type="single">
                                            <div class="class-info">
                                                <h6>{{ $c->title }}</h6>
                                                <span class="badge bg-primary">تک‌درس</span>
                                                <small class="text-muted d-block mt-1">
                                                    {{ $c->students_count ?? 0 }} دانش‌آموز
                                                </small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary select-class">
                                                انتخاب
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    هیچ کلاس تک‌درسی وجود ندارد.
                                    <button class="btn btn-sm btn-primary mt-2"
                                            data-bs-toggle="modal" data-bs-target="#createClassModal"
                                            data-class-type="single">
                                        ساخت کلاس تک‌درس
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- کلاس‌های جامع -->
                        <div id="comprehensiveClassSection" style="display:none;">
                            @php $comp = $classrooms->where('classroom_type','comprehensive'); @endphp
                            @if($comp->count() > 0)
                                <div class="class-list">
                                    @foreach($comp as $c)
                                        <div class="class-item" data-id="{{ $c->id }}" data-type="comprehensive">
                                            <div class="class-info">
                                                <h6>{{ $c->title }}</h6>
                                                <span class="badge bg-success">جامع</span>
                                                <small class="text-muted d-block mt-1">
                                                    {{ $c->students_count ?? 0 }} دانش‌آموز
                                                </small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary select-class">
                                                انتخاب
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    هیچ کلاس جامعی وجود ندارد.
                                    <button class="btn btn-sm btn-primary mt-2"
                                            data-bs-toggle="modal" data-bs-target="#createClassModal"
                                            data-class-type="comprehensive">
                                        ساخت کلاس جامع
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================
             🌟 STEP 2 — دسته‌بندی آموزشی
        ================================== -->
        <div class="wizard-step" id="step2">
            <div class="glass-card">
                <h4>دسته‌بندی آموزشی</h4>
                <p class="text-muted mb-4">پایه و شاخه تحصیلی را انتخاب کنید</p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">پایه</label>
                        <select id="gradeSelect" class="form-select">
                            <option value="">انتخاب پایه...</option>
                            @foreach($grades as $g)
                                <option value="{{ $g->id }}" data-section="{{ $g->section_id }}">
                                    {{ $g->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">شاخه</label>
                        <select id="branchSelect" class="form-select" disabled>
                            <option value="">ابتدا پایه را انتخاب کنید...</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">زمینه</label>
                        <select id="fieldSelect" class="form-select" disabled>
                            <option value="">ابتدا شاخه را انتخاب کنید...</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">زیررشته</label>
                        <select id="subfieldSelect" class="form-select" disabled>
                            <option value="">ابتدا زمینه را انتخاب کنید...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================
             🌟 STEP 3 — انتخاب درس‌ها
        ================================== -->
        <div class="wizard-step" id="step3">
            <div class="glass-card">
                <h4>انتخاب نوع درس</h4>
                <p class="text-muted mb-3">نوع درس را انتخاب کنید تا لیست درس‌ها نمایش داده شود</p>
                <select id="subjectTypeSelect" class="form-select mb-4">
                    <option value="">انتخاب نوع درس...</option>
                    @foreach ($subjectTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->name_fa }}</option>
                    @endforeach
                </select>
            </div>

            <div class="glass-card" id="subjectsCard">
                <h4>انتخاب درس‌ها</h4>
                <p class="text-muted mb-3">
                    <span id="subjectSelectionHint">برای آزمون تک‌درس، یک درس انتخاب کنید</span>
                </p>
                <div id="subjectsContainer" class="row"></div>
            </div>
        </div>

        <!-- ================================
             🌟 STEP 4 — تنظیمات آزمون
        ================================== -->
        <div class="wizard-step" id="step4">
            <div class="glass-card">
                <h4>تنظیمات آزمون</h4>
                
                <div class="mb-3">
                    <label class="form-label">عنوان آزمون *</label>
                    <div class="input-group">
                        <input id="title" name="title" type="text" class="form-control"
                               placeholder="مثال: آزمون فصل ۲ ریاضی پایه هفتم" required>
                        <button type="button" id="aiTitleBtn" class="btn btn-outline-primary">
                            پیشنهاد هوشمند
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">توضیحات آزمون</label>
                    <div class="input-group">
                        <textarea id="description" name="description" class="form-control" rows="3"
                                  placeholder="مثال: این آزمون برای سنجش یادگیری دانش‌آموزان طراحی شده است..."></textarea>
                        <button type="button" id="aiDescBtn" class="btn btn-outline-primary">
                            پیشنهاد هوشمند
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">زمان شروع آزمون</label>
                        <input type="text" id="start_at" name="start_at" class="form-control" autocomplete="off">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">زمان پایان آزمون</label>
                        <input type="text" id="end_at" name="end_at" class="form-control" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">مدت آزمون (دقیقه) *</label>
                        <input id="duration_minutes" name="duration_minutes" type="number"
                               class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نمره قبولی (%)</label>
                        <input id="passing_score" name="passing_score" type="number"
                               class="form-control" min="0" max="100">
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    سوالات آزمون را پس از ایجاد، از بخش "مدیریت سوالات" اضافه کنید.
                </div>
            </div>
        </div>

        <!-- ================================
             🌟 STEP 5 — پیش‌نمایش
        ================================== -->
        <div class="wizard-step" id="step5">
            <div class="glass-card">
                <h4>پیش‌نمایش آزمون</h4>
                <p class="text-muted mb-4">اطلاعات وارد شده را بررسی و تایید کنید</p>

                <div class="preview-grid">
                    <div class="preview-item">
                        <strong>نوع آزمون:</strong>
                        <span id="preview_exam_type" class="badge bg-primary">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>نوع کلاس:</strong>
                        <span id="preview_classroom_type" class="badge bg-info">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>کلاس:</strong>
                        <span id="preview_classroom">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>پایه:</strong>
                        <span id="preview_grade">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>شاخه:</strong>
                        <span id="preview_branch">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>زمینه:</strong>
                        <span id="preview_field">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>زیررشته:</strong>
                        <span id="preview_subfield">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>نوع درس:</strong>
                        <span id="preview_subject_type">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>تعداد درس‌ها:</strong>
                        <span id="preview_subjects_count">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>عنوان:</strong>
                        <span id="preview_title">--</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>مدت آزمون:</strong>
                        <span id="preview_duration">-- دقیقه</span>
                    </div>
                    
                    <div class="preview-item">
                        <strong>نمره قبولی:</strong>
                        <span id="preview_passing_score">--</span>
                    </div>
                    
                    <div class="preview-item full-width">
                        <strong>توضیحات:</strong>
                        <p id="preview_description" class="text-muted small mt-1">--</p>
                    </div>
                </div>

                <div class="alert alert-warning mt-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    پس از تایید، نوع آزمون قابل تغییر نخواهد بود.
                </div>
            </div>
        </div>

        <!-- ================================
             🌟 دکمه‌های Wizard
        ================================== -->
        <div class="wizard-buttons">
            <button type="button" class="btn-prev" id="prevBtn">
                <i class="bi bi-chevron-right"></i> مرحله قبل
            </button>

            <div>
                <button type="button" class="btn-next" id="nextBtn">
                    مرحله بعد <i class="bi bi-chevron-left"></i>
                </button>
                <button type="submit" class="btn-submit d-none" id="submitBtn">
                    <i class="bi bi-check-circle"></i> ایجاد آزمون
                </button>
            </div>
        </div>
    </form>

    <!-- Modal ساخت کلاس -->
    <div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-journal-plus me-2"></i>
                        ساخت کلاس جدید
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-info-circle text-primary fs-1 mb-3"></i>
                    <h5 class="mb-3">لطفاً به بخش کلاس‌ها مراجعه کنید</h5>
                    <p class="text-muted mb-4">
                        برای ساخت کلاس جدید نیاز به اطلاعات کامل آموزشی دارید.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i>
                            رفتن به ساخت کلاس
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            ادامه
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    @include('dashboard.teacher.exams.create-script')
@endpush

@endsection