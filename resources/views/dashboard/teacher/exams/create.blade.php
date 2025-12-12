@extends('layouts.app')
@section('title', 'ساخت آزمون جدید - SmartEdu')

@push('styles')
<style>
    /* تم فیروزه‌ای - آبی دریایی */
    :root {
        --primary: #00CED1;
        --primary-light: rgba(0, 206, 209, 0.1);
        --primary-gradient: linear-gradient(135deg, #00CED1, #20B2AA);
        --secondary: #4682B4;
        --secondary-light: rgba(70, 130, 180, 0.1);
        --accent: #48D1CC;
        --accent-light: rgba(72, 209, 204, 0.1);
        --success: #32CD32;
        --success-light: rgba(50, 205, 50, 0.1);
        --warning: #FFA500;
        --warning-light: rgba(255, 165, 0, 0.1);
        --light: #ffffff;
        --dark: #2F4F4F;
        --dark-light: #4A6F6F;
        --gray: #708090;
        --light-gray: #F0F8FF;
        --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
        --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
    }

    * { font-family: 'Vazirmatn', sans-serif; }

    body {
        background-color: #f8fcfc;
        color: var(--dark);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .create-exam-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 20px 15px 80px;
        animation: fadeIn 0.6s ease both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideInLeft {
        from { transform: translateX(-30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to   { transform: translateY(0); opacity: 1; }
    }

    /* HEADER */
    .page-header {
        background: linear-gradient(135deg, rgba(0, 206, 209, 0.1), rgba(70, 130, 180, 0.1));
        border-radius: var(--radius-xl);
        padding: 25px 30px;
        margin-bottom: 30px;
        border: 2px solid rgba(0, 206, 209, 0.15);
        position: relative;
        overflow: hidden;
        animation: slideInLeft 0.5s ease-out;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0, 206, 209, 0.08), transparent 70%);
        border-radius: 50%;
    }
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 2;
    }
    .header-title h1 {
        font-weight: 900;
        font-size: 1.8rem;
        color: var(--dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .header-title h1::before {
        content: '';
        width: 8px;
        height: 40px;
        background: var(--primary-gradient);
        border-radius: 10px;
    }
    .header-subtitle {
        color: var(--gray);
        font-size: 1.05rem;
        line-height: 1.8;
        max-width: 600px;
    }
    .btn-back {
        padding: 12px 24px;
        border-radius: var(--radius-lg);
        font-weight: 800;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: transparent;
        color: var(--dark);
        border: 2px solid var(--gray);
        transition: all 0.25s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-back:hover {
        background: var(--light-gray);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* PROGRESS */
    .progress-container { margin-bottom: 35px; animation: slideUp 0.5s ease-out; }
    .progress-bar {
        height: 8px;
        background: var(--light-gray);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .progress-fill {
        height: 100%;
        background: var(--primary-gradient);
        border-radius: 4px;
        width: 12.5%;
        transition: width 0.6s ease;
    }
    .progress-steps {
        display: flex;
        justify-content: space-between;
        padding: 0 8px;
        gap: 6px;
        flex-wrap: wrap;
    }
    .step-item { text-align: center; flex: 1; min-width: 105px; }
    .step-number {
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--light);
        border: 2px solid var(--light-gray);
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; color: var(--gray);
        margin: 0 auto 6px;
        transition: all 0.3s;
        font-size: .9rem;
    }
    .step-item.active .step-number {
        background: var(--primary); color: white; border-color: var(--primary); transform: scale(1.08);
    }
    .step-item.completed .step-number {
        background: var(--success); color: white; border-color: var(--success);
    }
    .step-name {
        font-size: 0.82rem; font-weight: 800; color: var(--gray);
        transition: all .3s;
    }
    .step-item.active .step-name { color: var(--primary); font-weight: 900; }

    /* FORM CONTAINER */
    .form-container {
        background: var(--light);
        border-radius: var(--radius-xl);
        padding: 36px;
        box-shadow: var(--shadow-lg);
        border: 2px solid rgba(0, 206, 209, 0.08);
        position: relative;
        overflow: hidden;
        animation: slideUp 0.6s ease-out;
    }

    .form-section { display: none; animation: fadeIn .35s ease; }
    .form-section.active { display: block; }

    .section-header { margin-bottom: 25px; text-align: center; }
    .section-icon { font-size: 2.6rem; margin-bottom: 10px; color: var(--primary); }
    .section-title { font-weight: 900; font-size: 1.45rem; color: var(--dark); margin-bottom: 8px; }
    .section-description {
        color: var(--gray); font-size: 1.02rem; line-height: 1.7;
        max-width: 700px; margin: 0 auto;
    }

    /* SELECTION GRID (reusable cards) */
    .selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }
    @media (max-width: 768px) {
        .selection-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 480px) {
        .selection-grid { grid-template-columns: 1fr; }
    }
    .selection-card {
        border: 3px solid var(--light-gray);
        border-radius: var(--radius-lg);
        padding: 18px 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--light);
        position: relative;
        overflow: hidden;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 6px;
    }
    .selection-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); border-color: rgba(0,206,209,.35); }
    .selection-card.selected {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(0, 206, 209, 0.06), rgba(70, 130, 180, 0.06));
        box-shadow: var(--shadow-md);
    }
    .selection-icon { font-size: 2rem; }
    .selection-name { font-weight: 900; font-size: 1.05rem; color: var(--dark); }
    .selection-description { color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin: 0; }

    /* EXAM TYPE cards (step1) */
    .exam-type-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
    @media (max-width: 768px) { .exam-type-grid { grid-template-columns: 1fr; } }
    .type-card {
        border: 3px solid var(--light-gray);
        border-radius: var(--radius-lg);
        padding: 22px 18px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: var(--light);
        position: relative;
        overflow: hidden;
        min-height: 175px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
    }
    .type-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
    .type-card.selected {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(0, 206, 209, 0.05), rgba(70, 130, 180, 0.05));
        box-shadow: var(--shadow-md);
    }
    .type-icon { font-size: 2.2rem; color: var(--primary); }
    .type-title { font-weight: 900; font-size: 1.1rem; color: var(--dark); }
    .type-description { color: var(--gray); font-size: 0.9rem; line-height: 1.6; margin:0; }
    .type-badge {
        position: absolute; top: 10px; left: 10px;
        background: var(--primary); color: #fff;
        padding: 4px 12px; border-radius: 999px;
        font-size: .75rem; font-weight: 900;
    }

    /* SUBJECTS LIST (step7) */
    .subjects-wrap { margin-top: 8px; }
    .subject-item {
        display: flex; align-items: center; gap: 12px;
        background: var(--light);
        border: 2px solid var(--light-gray);
        border-radius: var(--radius-md);
        padding: 12px 14px;
        margin-bottom: 10px;
        transition: all .2s ease;
    }
    .subject-item:hover { border-color: rgba(0,206,209,.35); box-shadow: var(--shadow-sm); }
    .subject-checkbox input { width: 18px; height: 18px; cursor: pointer; }
    .subject-info { flex:1; text-align:right; }
    .subject-name { font-weight: 900; font-size: 1rem; margin-bottom: 4px; }
    .subject-meta { color: var(--gray); font-size: .85rem; display:flex; gap:10px; flex-wrap:wrap; }
    .subject-code { background: var(--light-gray); padding:2px 8px; border-radius: 6px; }

    /* PREVIEW (step8) */
    .preview-section {
        background: linear-gradient(135deg, rgba(0, 206, 209, 0.05), rgba(70, 130, 180, 0.05));
        border-radius: var(--radius-xl);
        padding: 22px;
        margin-bottom: 25px;
        border: 2px solid var(--primary-light);
    }
    .preview-title {
        font-weight: 900; color: var(--dark);
        margin-bottom: 14px; display:flex; align-items:center; gap:8px;
        font-size: 1.15rem;
    }
    .preview-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px;
    }
    .preview-item {
        background: var(--light); border-radius: var(--radius-md); padding: 12px;
        border: 2px solid var(--light-gray);
    }
    .preview-label { font-size: .85rem; color: var(--gray); font-weight: 700; margin-bottom: 4px; }
    .preview-value { font-weight: 900; color: var(--dark); font-size: 1rem; }

    /* DETAILS FORM (step8) */
    .details-form { max-width: 650px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    .form-label {
        color: var(--dark);
        font-weight: 900; font-size: 1rem; margin-bottom: 10px;
        display:flex; align-items:center; gap:8px;
    }
    .form-label i {
        color: var(--primary);
        background: var(--primary-light);
        width: 34px; height: 34px; border-radius: 10px;
        display:flex; align-items:center; justify-content:center; font-size:1rem;
    }
    .form-input, .form-textarea {
        width: 100%; padding: 14px 16px;
        border: 2px solid var(--light-gray);
        border-radius: var(--radius-md);
        background: var(--light); color: var(--dark);
        font-weight: 700; font-size: 1rem;
        transition: all .25s ease;
    }
    .form-textarea { min-height: 110px; resize: vertical; line-height: 1.7; }
    .form-input:focus, .form-textarea:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(0, 206, 209, 0.2);
    }

    .checkbox-group {
        background: var(--light-gray);
        border-radius: var(--radius-lg);
        padding: 16px;
        margin-top: 12px;
    }
    .form-check { display:flex; align-items:center; gap:10px; }
    .form-check-input { width: 20px; height: 20px; cursor: pointer; }
    .form-check-label { font-weight: 900; font-size: 1rem; cursor: pointer; }
    .form-text { font-size: .9rem; color: var(--gray); margin-top: 8px; }

    /* NAV BUTTONS */
    .nav-buttons {
        display: flex; justify-content: space-between;
        margin-top: 30px; gap: 12px;
    }
    .btn-nav {
        padding: 14px 26px;
        border-radius: var(--radius-lg);
        font-weight: 900; font-size: 1rem;
        display:flex; align-items:center; gap:8px; justify-content:center;
        cursor:pointer; border:2px solid transparent; min-width: 150px;
        transition: all .25s ease;
    }
    .btn-prev { background: transparent; color: var(--dark); border:2px solid var(--gray); }
    .btn-prev:hover { background: var(--light-gray); transform: translateY(-2px); box-shadow: var(--shadow-sm); }
    .btn-next { background: var(--primary-gradient); color:#fff; box-shadow: 0 8px 18px rgba(0,206,209,.3); }
    .btn-next:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,206,209,.4); }
    .btn-submit { background: var(--success); color:#fff; box-shadow: 0 8px 18px rgba(50,205,50,.3); }
    .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(50,205,50,.4); }

    @media (max-width: 768px) {
        .form-container { padding: 22px; }
        .header-title h1 { font-size: 1.5rem; }
        .nav-buttons { flex-direction: column; }
        .btn-nav { width: 100%; min-width: unset; }
    }
</style>
@endpush

@section('content')
<div class="create-exam-container">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>
                    <span style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
                                 -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        ساخت آزمون جدید
                    </span>
                    📝
                </h1>
                <p class="header-subtitle">آزمون خود را به صورت مرحله‌ای و با دقت ایجاد کنید.</p>
            </div>

            <a href="{{ route('teacher.exams.index') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i>
                بازگشت به لیست آزمون‌ها
            </a>
        </div>
    </div>

    {{-- PROGRESS --}}
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-steps">
            <div class="step-item active" data-step="1">
                <div class="step-number">۱</div>
                <div class="step-name">نوع آزمون</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-number">۲</div>
                <div class="step-name">پایه تحصیلی</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-number">۳</div>
                <div class="step-name">شاخه تحصیلی</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-number">۴</div>
                <div class="step-name">زمینه فنی</div>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-number">۵</div>
                <div class="step-name">زیررشته</div>
            </div>
            <div class="step-item" data-step="6">
                <div class="step-number">۶</div>
                <div class="step-name">دسته درسی</div>
            </div>
            <div class="step-item" data-step="7">
                <div class="step-number">۷</div>
                <div class="step-name">انتخاب درس</div>
            </div>
            <div class="step-item" data-step="8">
                <div class="step-number">۸</div>
                <div class="step-name">جزئیات</div>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="form-container">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form method="POST" action="{{ route('teacher.exams.store') }}" id="examForm" onsubmit="return validateFinalStep()">
            @csrf

            {{-- Hidden Inputs (JS fills these) --}}
            <input type="hidden" name="exam_type" id="examType" value="public">
            <input type="hidden" name="classroom_id" id="classroomId" value="{{ $selectedClassroomId ?? '' }}">

            <input type="hidden" name="section_id" id="sectionId">
            <input type="hidden" name="grade_id" id="gradeId">
            <input type="hidden" name="branch_id" id="branchId">
            <input type="hidden" name="field_id" id="fieldId">
            <input type="hidden" name="subfield_id" id="subfieldId">
            <input type="hidden" name="subject_type_id" id="subjectTypeId">
            <input type="hidden" name="subjects" id="subjectsInput">

            {{-- STEP 1 --}}
            <div class="form-section active" id="step1">
                <div class="section-header">
                    <div class="section-icon">🎯</div>
                    <h2 class="section-title">نوع آزمون را انتخاب کنید</h2>
                    <p class="section-description">یکی از گزینه‌های زیر را انتخاب نمایید.</p>
                </div>

                <div class="exam-type-grid">
                    <div class="type-card" data-type="public" onclick="selectExamType('public')">
                        <div class="type-icon">🌐</div>
                        <div class="type-title">آزمون عمومی</div>
                        <p class="type-description">برای تمام هنرجویان قابل دسترسی است.</p>
                        <div class="type-badge">عمومی</div>
                    </div>

                    <div class="type-card" data-type="class_single" onclick="selectExamType('class_single')">
                        <div class="type-icon">📚</div>
                        <div class="type-title">کلاسی تک‌درس</div>
                        <p class="type-description">فقط برای یک کلاس و یک درس مشخص.</p>
                        <div class="type-badge">تخصصی</div>
                    </div>

                    <div class="type-card" data-type="class_comprehensive" onclick="selectExamType('class_comprehensive')">
                        <div class="type-icon">🎓</div>
                        <div class="type-title">کلاسی جامع</div>
                        <p class="type-description">برای یک کلاس شامل همه دروس پایه.</p>
                        <div class="type-badge">جامع</div>
                    </div>
                </div>

                {{-- Classroom selection (only for class exams) --}}
                <div id="classroomSelectionSection" style="display:none; margin-top:24px;">
                    <div class="section-header" style="margin-bottom:12px;">
                        <h3 class="section-title" style="font-size:1.2rem;">کلاس را انتخاب کنید</h3>
                        <p class="section-description">کلاس‌های شما از سیستم بارگذاری می‌شوند.</p>
                    </div>

                    <div class="selection-grid" id="existingClassroomsContainer"></div>

                    <div style="text-align:center; margin-top:14px;">
                        <button type="button" class="btn-nav btn-prev" onclick="createNewClassroom()" style="border-color:var(--primary); color:var(--primary);">
                            <i class="fas fa-plus-circle"></i>
                            ایجاد کلاس جدید
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2 --}}
            <div class="form-section" id="step2">
                <div class="section-header">
                    <div class="section-icon">📊</div>
                    <h2 class="section-title">پایه تحصیلی را انتخاب کنید</h2>
                    <p class="section-description">پایهٔ مورد نظر آزمون را انتخاب نمایید.</p>
                </div>

                <div class="selection-grid" id="gradesGrid"></div>
            </div>

            {{-- STEP 3 --}}
            <div class="form-section" id="step3">
                <div class="section-header">
                    <div class="section-icon">🎓</div>
                    <h2 class="section-title">شاخه تحصیلی را انتخاب کنید</h2>
                    <p class="section-description">شاخهٔ آموزشی را انتخاب نمایید.</p>
                </div>

                <div class="selection-grid" id="branchesGrid"></div>
            </div>

            {{-- STEP 4 --}}
            <div class="form-section" id="step4">
                <div class="section-header">
                    <div class="section-icon">🏭</div>
                    <h2 class="section-title">زمینه آموزشی را انتخاب کنید</h2>
                    <p class="section-description">زمینهٔ آموزشی مرتبط را انتخاب نمایید.</p>
                </div>

                <div class="selection-grid" id="fieldsGrid"></div>
            </div>

            {{-- STEP 5 --}}
            <div class="form-section" id="step5">
                <div class="section-header">
                    <div class="section-icon">🔬</div>
                    <h2 class="section-title">زیررشته را انتخاب کنید</h2>
                    <p class="section-description">زیررشتهٔ مورد نظر را مشخص نمایید.</p>
                </div>

                <div class="selection-grid" id="subfieldGrid"></div>
            </div>

            {{-- STEP 6 --}}
            <div class="form-section" id="step6">
                <div class="section-header">
                    <div class="section-icon">📚</div>
                    <h2 class="section-title">دسته درسی را انتخاب کنید</h2>
                    <p class="section-description">دستهٔ درسی آزمون را انتخاب نمایید.</p>
                </div>

                <div class="selection-grid" id="subjectTypesGrid"></div>
            </div>

            {{-- STEP 7 --}}
            <div class="form-section" id="step7">
                <div class="section-header">
                    <div class="section-icon">📖</div>
                    <h2 class="section-title">درس(ها) را انتخاب کنید</h2>
                    <p class="section-description">بر اساس نوع آزمون، درس‌های مورد نظر را انتخاب نمایید.</p>
                </div>

                <div class="subjects-wrap" id="subjectsContainer"></div>
            </div>

            {{-- STEP 8 --}}
            <div class="form-section" id="step8">
                <div class="section-header">
                    <div class="section-icon">✏️</div>
                    <h2 class="section-title">جزئیات آزمون را تکمیل کنید</h2>
                    <p class="section-description">اطلاعات تکمیلی آزمون را وارد نمایید.</p>
                </div>

                {{-- Preview --}}
                <div class="preview-section">
                    <div class="preview-title">
                        <i class="fas fa-eye"></i>
                        پیش‌نمایش آزمون
                    </div>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <div class="preview-label">نوع آزمون</div>
                            <div class="preview-value" id="previewExamType">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">پایه تحصیلی</div>
                            <div class="preview-value" id="previewGrade">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">شاخه تحصیلی</div>
                            <div class="preview-value" id="previewBranch">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">زمینه فنی</div>
                            <div class="preview-value" id="previewField">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">زیررشته</div>
                            <div class="preview-value" id="previewSubfield">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">دسته درسی</div>
                            <div class="preview-value" id="previewSubjectType">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">تعداد درس‌های انتخابی</div>
                            <div class="preview-value" id="previewSubjectsCount">--</div>
                        </div>
                        <div class="preview-item">
                            <div class="preview-label">تعداد سوالات پیشنهادی</div>
                            <div class="preview-value" id="previewTotalQuestions">--</div>
                        </div>
                    </div>
                </div>

                {{-- Details Form --}}
                <div class="details-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-heading"></i>
                            عنوان آزمون
                        </label>
                        <input type="text" name="title" class="form-input"
                               value="{{ old('title') }}"
                               placeholder="مثال: آزمون فصل ۱ شبکه"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clock"></i>
                            مدت زمان آزمون (دقیقه)
                        </label>
                        <input type="number" name="duration" class="form-input"
                               value="{{ old('duration', 60) }}"
                               min="5" max="300" step="5" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left"></i>
                            توضیحات آزمون
                        </label>
                        <textarea name="description" class="form-textarea" rows="4"
                                  placeholder="هدف آزمون، نکات مهم و ...">{{ old('description') }}</textarea>
                    </div>

                    <div class="checkbox-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" value="1" checked>
                            <label class="form-check-label" for="activeCheck">آزمون بلافاصله فعال شود</label>
                        </div>
                        <div class="form-text">
                            در صورت عدم انتخاب، آزمون به صورت پیش‌نویس ذخیره می‌شود.
                        </div>
                    </div>
                </div>
            </div>

{{-- ========== NAVIGATION BUTTONS ========== --}}
<div class="nav-buttons">
    <button type="button" class="btn-nav btn-prev" onclick="prevStep()">
        <i class="fas fa-arrow-right"></i>
        مرحله قبل
    </button>

    <button type="button" class="btn-nav btn-next" onclick="nextStep()">
        مرحله بعد
        <i class="fas fa-arrow-left"></i>
    </button>

    <button type="submit" class="btn-nav btn-submit">
        <i class="fas fa-check"></i>
        ایجاد آزمون
    </button>
</div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/exam-wizard.js') }}"></script>
<script src="{{ asset('assets/js/classroom-modal.js') }}"></script>
@endpush
