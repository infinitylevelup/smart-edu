@extends('layouts.app')
@section('title', 'ساخت آزمون جدید - فنی و حرفه‌ای')

@push('styles')
    <style>
        :root {
            --primary: #00CED1;
            --primary-light: rgba(0, 206, 209, 0.1);
            --primary-gradient: linear-gradient(135deg, #00CED1, #20B2AA);
            --secondary: #4682B4;
            --secondary-light: rgba(70, 130, 180, 0.1);
            --success: #32CD32;
            --warning: #FFA500;
            --light: #ffffff;
            --dark: #2F4F4F;
            --gray: #708090;
            --light-gray: #F0F8FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --radius-xl: 24px;
            --radius-lg: 20px;
            --radius-md: 16px;

            --industry: #4682B4;
            --art: #9B59B6;
            --services: #27AE60;
            --agriculture: #8B4513;

            --base-competency: #3498db;
            --non-tech-competency: #e74c3c;
            --tech-competency: #2ecc71;
            --general-subjects: #f39c12;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f8fcfc;
            color: var(--dark);
        }

        .create-exam-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px 80px;
        }

        .page-header {
            background: linear-gradient(135deg, rgba(0, 206, 209, 0.1), rgba(70, 130, 180, 0.1));
            border-radius: var(--radius-xl);
            padding: 25px 30px;
            margin-bottom: 30px;
            border: 2px solid rgba(0, 206, 209, 0.15);
            position: relative;
            overflow: hidden;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title h1 {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .header-subtitle {
            color: var(--gray);
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 500px;
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
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .progress-container {
            margin-bottom: 40px;
        }

        .progress-bar {
            height: 8px;
            background: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary-gradient);
            border-radius: 4px;
            width: 14%;
            transition: width 0.6s ease;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
        }

        .step-item {
            text-align: center;
            position: relative;
            flex: 1;
        }

        .step-number {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--light);
            border: 2px solid var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: var(--gray);
            margin: 0 auto 8px;
            transition: all 0.3s;
        }

        .step-item.active .step-number {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: var(--success);
            color: white;
            border-color: var(--success);
        }

        .step-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--gray);
        }

        .step-item.active .step-name {
            color: var(--primary);
            font-weight: 900;
        }

        .form-container {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 40px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(0, 206, 209, 0.08);
            position: relative;
            overflow: hidden;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .section-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .section-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .section-description {
            color: var(--gray);
            font-size: 1.05rem;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto;
        }

        .exam-type-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .exam-type-grid {
                grid-template-columns: 1fr;
            }
        }

        .type-card {
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--light);
            position: relative;
            overflow: hidden;
        }

        .type-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .type-card.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(0, 206, 209, 0.05), rgba(70, 130, 180, 0.05));
            box-shadow: var(--shadow-md);
        }

        .type-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .type-title {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .type-description {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        .type-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 900;
        }

        .selection-grid {
            display: grid;
            gap: 20px;
        }

        .selection-card {
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--light);
            position: relative;
            overflow: hidden;
        }

        .selection-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .selection-card.selected {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .selection-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .selection-name {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .selection-description {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        .selection-card.industry.selected {
            border-color: var(--industry);
            background: rgba(70, 130, 180, 0.1);
        }

        .selection-card.art.selected {
            border-color: var(--art);
            background: rgba(155, 89, 182, 0.1);
        }

        .selection-card.services.selected {
            border-color: var(--services);
            background: rgba(39, 174, 96, 0.1);
        }

        .selection-card.agriculture.selected {
            border-color: var(--agriculture);
            background: rgba(139, 69, 19, 0.1);
        }

        .selection-card.base-competency.selected {
            border-color: var(--base-competency);
            background: rgba(52, 152, 219, 0.1);
        }

        .selection-card.non-tech-competency.selected {
            border-color: var(--non-tech-competency);
            background: rgba(231, 76, 60, 0.1);
        }

        .selection-card.tech-competency.selected {
            border-color: var(--tech-competency);
            background: rgba(46, 204, 113, 0.1);
        }

        .selection-card.general-subjects.selected {
            border-color: var(--general-subjects);
            background: rgba(243, 156, 18, 0.1);
        }

        .subject-group {
            margin-bottom: 30px;
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .group-title {
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .subject-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: white;
            border-radius: var(--radius-md);
            margin-bottom: 10px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .subject-item:hover {
            border-color: var(--primary-light);
        }

        .subject-checkbox {
            margin-left: 15px;
        }

        .subject-info {
            flex: 1;
        }

        .subject-name {
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .subject-meta {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: var(--gray);
            flex-wrap: wrap;
        }

        .subject-code {
            background: var(--light-gray);
            padding: 2px 8px;
            border-radius: 4px;
            font-family: monospace;
        }

        .coefficient-settings {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin: 30px 0;
        }

        .coefficient-item {
            background: white;
            border-radius: var(--radius-md);
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid var(--primary);
        }

        .coefficient-item.base-competency {
            border-left-color: var(--base-competency);
        }

        .coefficient-item.non-tech-competency {
            border-left-color: var(--non-tech-competency);
        }

        .coefficient-item.tech-competency {
            border-left-color: var(--tech-competency);
        }

        .coeff-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .coeff-badge {
            background: var(--primary);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .coeff-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--light-gray);
        }

        .coeff-row:last-child {
            border-bottom: none;
        }

        .total-calculation {
            background: white;
            border-radius: var(--radius-md);
            padding: 20px;
            margin-top: 20px;
            border: 2px solid var(--primary);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            font-size: 1.1rem;
        }

        .preview-section {
            background: linear-gradient(135deg, rgba(0, 206, 209, 0.05), rgba(70, 130, 180, 0.05));
            border-radius: var(--radius-xl);
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid var(--primary-light);
        }

        .preview-title {
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .preview-item {
            background: var(--light);
            border-radius: var(--radius-md);
            padding: 15px;
            border: 2px solid var(--light-gray);
        }

        .preview-label {
            font-size: 0.9rem;
            color: var(--gray);
            margin-bottom: 5px;
            font-weight: 700;
        }

        .preview-value {
            font-weight: 900;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: var(--dark);
            font-weight: 900;
            font-size: 1rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 206, 209, 0.2);
        }

        .form-textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            font-size: 1.05rem;
            transition: all 0.3s;
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .btn-nav {
            padding: 16px 30px;
            border-radius: var(--radius-lg);
            font-weight: 900;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            cursor: pointer;
            border: 2px solid transparent;
            min-width: 150px;
            justify-content: center;
        }

        .btn-prev {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-prev:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .btn-next {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 206, 209, 0.3);
        }

        .btn-next:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 206, 209, 0.4);
        }

        .btn-submit {
            background: var(--success);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(50, 205, 50, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(50, 205, 50, 0.4);
        }

        @media (max-width: 768px) {
            .create-exam-container {
                padding: 15px 10px 60px;
            }

            .page-header {
                padding: 20px;
            }

            .form-container {
                padding: 25px;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .nav-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn-nav {
                width: 100%;
            }

            .selection-grid {
                grid-template-columns: 1fr !important;
            }

            .preview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="create-exam-container">
        {{-- ========== HEADER ========== --}}
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>
                        <span
                            style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            ساخت آزمون جدید - فنی و حرفه‌ای
                        </span> 🔧
                    </h1>
                    <p class="header-subtitle">
                        آزمون خود را برای هنرجویان فنی و حرفه‌ای به صورت مرحله‌ای ایجاد کنید.
                    </p>
                </div>
                <a href="{{ route('teacher.exams.index') }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به لیست آزمون‌ها
                </a>
            </div>
        </div>

        {{-- ========== PROGRESS BAR ========== --}}
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 14%;"></div>
            </div>
            <div class="progress-steps">
                @php
                    $steps = [
                        1 => 'نوع آزمون',
                        2 => 'پایه تحصیلی',
                        3 => 'شاخه تحصیلی',
                        4 => 'زمینه فنی',
                        5 => 'زیررشته',
                        6 => 'دسته درسی',
                        7 => 'انتخاب درس',
                        8 => 'جزئیات',
                    ];
                @endphp
                @foreach ($steps as $num => $name)
                    <div class="step-item @if ($num == 1) active @endif" data-step="{{ $num }}">
                        <div class="step-number">{{ $num }}</div>
                        <div class="step-name">{{ $name }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ========== FORM CONTAINER ========== --}}
        <div class="form-container">
            <form method="POST" action="{{ route('teacher.exams.store') }}" id="examForm"
                onsubmit="return validateFinalStep()">
                @csrf

                {{-- Hidden Inputs --}}
                <input type="hidden" name="exam_type" id="examType" value="">
                <input type="hidden" name="classroom_id" id="classroomId" value="{{ $selectedClassroomId ?? '' }}">
                <input type="hidden" name="grade" id="grade" value="">
                <input type="hidden" name="branch" id="branch" value="">
                <input type="hidden" name="field" id="field" value="">
                <input type="hidden" name="subfield" id="subfield" value="">
                <input type="hidden" name="subject_type" id="subjectType" value="">
                <input type="hidden" name="subjects" id="subjectsInput" value="">

                {{-- ===== STEP 1: EXAM TYPE ===== --}}
                <div class="form-section active" id="step1">
                    <div class="section-header">
                        <div class="section-icon">🎯</div>
                        <h2 class="section-title">نوع آزمون را انتخاب کنید</h2>
                        <p class="section-description">
                            بر اساس نیاز آموزشی خود، یکی از گزینه‌های زیر را انتخاب نمایید.
                        </p>
                    </div>

                    <div class="exam-type-grid">
                        <div class="type-card" data-type="public" onclick="selectExamType('public')">
                            <div class="type-icon">🌐</div>
                            <div class="type-title">آزمون عمومی</div>
                            <p class="type-description">برای تمام دانش‌آموزان قابل دسترسی است. نیازی به عضویت در کلاس ندارد.
                            </p>
                            <div class="type-badge">عمومی</div>
                        </div>

                        <div class="type-card" data-type="class_single" onclick="selectExamType('class_single')">
                            <div class="type-icon">📚</div>
                            <div class="type-title">کلاسی تک درس</div>
                            <p class="type-description">برای یک کلاس خاص و فقط یک درس مشخص. تمرکز کامل بر یک موضوع درسی.</p>
                            <div class="type-badge">تخصصی</div>
                        </div>

                        <div class="type-card" data-type="class_comprehensive"
                            onclick="selectExamType('class_comprehensive')">
                            <div class="type-icon">🎓</div>
                            <div class="type-title">کلاسی جامع</div>
                            <p class="type-description">برای یک کلاس شامل تمام دروس پایه. ارزیابی کامل دانش‌آموزان.</p>
                            <div class="type-badge">جامع</div>
                        </div>
                    </div>

                    {{-- انتخاب کلاس --}}
                    <div id="classroomSelectionSection" style="display: none; margin-top: 30px;">
                        <div class="section-header" style="margin-bottom: 20px;">
                            <h3 class="section-title">انتخاب کلاس</h3>
                            <p class="section-description">
                                لطفاً کلاس مورد نظر را انتخاب کنید یا یک کلاس جدید ایجاد نمایید.
                            </p>
                        </div>

                        <div id="existingClassroomsContainer" class="selection-grid"
                            style="grid-template-columns: repeat(2, 1fr);">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                                در حال بارگذاری کلاس‌ها...
                            </div>
                        </div>

                        <div id="createNewClassContainer" style="margin-top: 25px; text-align: center;">
                            <div class="type-card" onclick="createNewClassroom()"
                                style="max-width: 400px; margin: 0 auto; cursor: pointer; background: linear-gradient(135deg, rgba(0, 206, 209, 0.1), rgba(70, 130, 180, 0.1));">
                                <div class="type-icon">➕</div>
                                <div class="type-title">ایجاد کلاس جدید</div>
                                <p class="type-description">هنوز کلاسی ندارید؟ یک کلاس جدید ایجاد کنید.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 2: GRADE ===== --}}
                <div class="form-section" id="step2">
                    <div class="section-header">
                        <div class="section-icon">📊</div>
                        <h2 class="section-title">پایه تحصیلی را انتخاب کنید</h2>
                        <p class="section-description">پایه مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>

                    <div class="selection-grid" style="grid-template-columns: repeat(3, 1fr);">
                        @foreach ([10, 11, 12] as $grade)
                            <div class="selection-card" onclick="selectGrade(event, {{ $grade }})">
                                <div class="selection-icon">
                                    @if ($grade == 10)
                                        📘
                                    @elseif($grade == 11)
                                        📗
                                    @else
                                        📙
                                    @endif
                                </div>
                                <div class="selection-name">پایه {{ $grade }}</div>
                                <p class="selection-description">
                                    @if ($grade == 10)
                                        دروس پایه و مقدماتی
                                    @elseif($grade == 11)
                                        دروس تخصصی و کارگاهی
                                    @else
                                        پروژه و کارآموزی
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ===== STEP 3: BRANCH ===== --}}
                <div class="form-section" id="step3">
                    <div class="section-header">
                        <div class="section-icon">🎓</div>
                        <h2 class="section-title">شاخه تحصیلی را انتخاب کنید</h2>
                        <p class="section-description">شاخه مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>

                    <div class="selection-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="selection-card" onclick="selectBranch(event, 'technical')">
                            <div class="selection-icon">🔧</div>
                            <div class="selection-name">فنی و حرفه‌ای</div>
                            <p class="selection-description">تمرکز بر مهارت‌های عملی و کارگاهی</p>
                        </div>

                        <div class="selection-card" onclick="selectBranch(event, 'vocational')">
                            <div class="selection-icon">🛠️</div>
                            <div class="selection-name">کاردانش</div>
                            <p class="selection-description">آموزش مهارت‌های کاربردی بازار کار</p>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 4: FIELD ===== --}}
                <div class="form-section" id="step4">
                    <div class="section-header">
                        <div class="section-icon">🏭</div>
                        <h2 class="section-title">زمینه فنی را انتخاب کنید</h2>
                        <p class="section-description">زمینه مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>

                    <div class="selection-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="selection-card industry" onclick="selectField(event,'industry')">
                            <div class="selection-icon">⚙️</div>
                            <div class="selection-name">صنعت</div>
                            <p class="selection-description">برق، مکانیک، ساختمان، خودرو، مواد</p>
                        </div>

                        <div class="selection-card services" onclick="selectField(event,'services')">
                            <div class="selection-icon">💼</div>
                            <div class="selection-name">خدمات</div>
                            <p class="selection-description">مدیریت، کامپیوتر، حسابداری، گردشگری</p>
                        </div>

                        <div class="selection-card art" onclick="selectField(event,'art')">
                            <div class="selection-icon">🎨</div>
                            <div class="selection-name">هنر</div>
                            <p class="selection-description">هنرهای تجسمی، نمایشی، موسیقی، پوشاک</p>
                        </div>

                        <div class="selection-card agriculture" onclick="selectField(event,'agriculture')">
                            <div class="selection-icon">🌱</div>
                            <div class="selection-name">کشاورزی</div>
                            <p class="selection-description">زراعت، باغبانی، دامپروری</p>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 5: SUBFIELD ===== --}}
                <div class="form-section" id="step5">
                    <div class="section-header">
                        <div class="section-icon">🔬</div>
                        <h2 class="section-title">زیررشته را انتخاب کنید</h2>
                        <p class="section-description">زیررشته مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>

                    <div class="selection-grid" id="subfieldGrid" style="grid-template-columns: repeat(2, 1fr);">
                        {{-- دینامیک --}}
                    </div>
                </div>

                {{-- ===== STEP 6: SUBJECT CATEGORY ===== --}}
                <div class="form-section" id="step6">
                    <div class="section-header">
                        <div class="section-icon">📚</div>
                        <h2 class="section-title">دسته درسی را انتخاب کنید</h2>
                        <p class="section-description">دسته درسی مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>

                    <div class="selection-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="selection-card base-competency" onclick="selectSubjectType(event,'base_competency')">
                            <div class="selection-icon">🔢</div>
                            <div class="selection-name">شایستگی پایه</div>
                            <p class="selection-description">
                                ریاضی، شیمی، فیزیک
                                <br><strong>ضریب: ۶ | وزن: ۳۳.۳۳٪</strong>
                            </p>
                        </div>

                        <div class="selection-card non-tech-competency"
                            onclick="selectSubjectType(event,'non_technical_competency')">
                            <div class="selection-icon">💼</div>
                            <div class="selection-name">شایستگی غیرفنی</div>
                            <p class="selection-description">
                                الزامات محیط کار، کارآفرینی
                                <br><strong>ضریب: ۳ | وزن: ۹.۵٪</strong>
                            </p>
                        </div>

                        <div class="selection-card tech-competency"
                            onclick="selectSubjectType(event,'technical_competency')">
                            <div class="selection-icon">💻</div>
                            <div class="selection-name">شایستگی فنی</div>
                            <p class="selection-description">
                                برنامه‌نویسی، شبکه، پایگاه داده
                                <br><strong>ضریب: ۱۲ | وزن: ۵۷.۱۴٪</strong>
                            </p>
                        </div>

                        <div class="selection-card general-subjects" onclick="selectSubjectType(event,'general')">
                            <div class="selection-icon">📖</div>
                            <div class="selection-name">دروس عمومی</div>
                            <p class="selection-description">
                                ادبیات، دینی، عربی، زبان، تربیت بدنی
                            </p>
                        </div>

                        <div class="selection-card" onclick="selectSubjectType(event,'all')">
                            <div class="selection-icon">✅</div>
                            <div class="selection-name">همه دروس این پایه</div>
                            <p class="selection-description">انتخاب تمام دروس پایه انتخابی</p>
                        </div>

                        <div class="selection-card" onclick="selectSubjectType(event,'specialized_competency')">
                            <div class="selection-icon">🎯</div>
                            <div class="selection-name">شایستگی‌های تخصصی</div>
                            <p class="selection-description">دروس تخصصی رشته انتخابی</p>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 7: SUBJECTS ===== --}}
                <div class="form-section" id="step7">
                    <div class="section-header">
                        <div class="section-icon">📖</div>
                        <h2 class="section-title">درس‌های آزمون را انتخاب کنید</h2>
                        <p class="section-description">درس‌های مورد نظر را از لیست زیر انتخاب نمایید.</p>
                    </div>

                    <div class="subject-selection">
                        <div id="subjectsContainer">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                                در حال بارگذاری دروس...
                            </div>
                        </div>

                        <div class="coefficient-settings" id="coefficientSettings"></div>
                    </div>
                </div>

                {{-- ===== STEP 8: DETAILS ===== --}}
                <div class="form-section" id="step8">
                    <div class="section-header">
                        <div class="section-icon">📋</div>
                        <h2 class="section-title">جزئیات آزمون را تکمیل کنید</h2>
                        <p class="section-description">اطلاعات تکمیلی آزمون خود را وارد نمایید.</p>
                    </div>

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
                                <div class="preview-label">تعداد دروس</div>
                                <div class="preview-value" id="previewSubjectsCount">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">کل سوالات</div>
                                <div class="preview-value" id="previewTotalQuestions">--</div>
                            </div>
                        </div>
                    </div>

                    <div class="details-form">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-heading"></i>عنوان آزمون</label>
                            <input type="text" name="title" class="form-input" id="examTitle"
                                placeholder="مثال: آزمون کارگاه برق صنعتی - پایه یازدهم" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i>مدت زمان آزمون (دقیقه)</label>
                            <input type="number" name="duration" class="form-input" value="90" min="15"
                                max="300" step="5" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-balance-scale"></i>تنظیمات سوالات</label>
                            <div id="finalCoefficientSettings"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-align-left"></i>توضیحات آزمون</label>
                            <textarea name="description" class="form-textarea" rows="4"
                                placeholder="هدف آزمون، وسایل مورد نیاز، نکات ایمنی، منابع مطالعاتی..."></textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck"
                                    value="1" checked>
                                <label class="form-check-label" for="activeCheck">آزمون بلافاصله فعال شود</label>
                            </div>
                            <small class="form-text">
                                در صورت عدم انتخاب، آزمون به صورت پیش‌نویس ذخیره شده و باید بعداً فعال شود.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- NAV BUTTONS --}}
                <div class="nav-buttons">
                    <button type="button" class="btn-nav btn-prev" onclick="prevStep()" style="display: none;">
                        <i class="fas fa-arrow-right"></i> مرحله قبل
                    </button>
                    <button type="button" class="btn-nav btn-next" onclick="nextStep()">
                        مرحله بعد <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="submit" class="btn-nav btn-submit" style="display: none;">
                        <i class="fas fa-check"></i> ایجاد آزمون
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        console.log("Swal:", typeof Swal);
        console.log("createNewClassroom:", typeof createNewClassroom);

        function createNewClassroom() {
            Swal.fire({
                title: 'ایجاد کلاس جدید',
                html: `
        <div style="text-align: right;">
            <div class="form-group">
                <label style="display:block;margin-bottom:8px;font-weight:bold;">🎓 مقطع</label>
                <select id="newClassSection" class="swal2-input">
                    <option value="متوسطه دوم فنی" selected>متوسطه دوم فنی</option>
                    <option value="متوسطه دوم نظری">متوسطه دوم نظری</option>
                    <option value="متوسطه دوم کاردانش">متوسطه دوم کاردانش</option>
                    <option value="دبستان">دبستان</option>
                    <option value="متوسطه اول">متوسطه اول</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block;margin-bottom:8px;font-weight:bold;">📊 پایه</label>
                <select id="newClassBase" class="swal2-input">
                    <option value="دهم" selected>دهم</option>
                    <option value="یازدهم">یازدهم</option>
                    <option value="دوازدهم">دوازدهم</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block;margin-bottom:8px;font-weight:bold;">🔧 رشته</label>
                <select id="newClassCourse" class="swal2-input">
                    <option value="شبکه و نرم افزار رایانه (صنعت)" selected>شبکه و نرم افزار رایانه (صنعت)</option>
                    <option value="الکتروتکنیک (صنعت)">الکتروتکنیک (صنعت)</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block;margin-bottom:8px;font-weight:bold;">📖 درس</label>
                <select id="newClassLesson" class="swal2-input">
                    <option value="">-- انتخاب درس --</option>
                    <option value="توسعه برنامه سازی و پایگاه داده">توسعه برنامه سازی و پایگاه داده</option>
                    <option value="دانش فنی پایه">دانش فنی پایه</option>
                </select>
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block;margin-bottom:8px;font-weight:bold;">🏷️ نام کلاس</label>
                <input type="text" id="newClassName" class="swal2-input"
                       placeholder="مثال: کلاس دهم شبکه - برنامه‌سازی">
            </div>
        </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'ایجاد کلاس',
                cancelButtonText: 'انصراف',
                reverseButtons: true,
                width: '600px',
                preConfirm: () => {
                    const className = document.getElementById('newClassName').value.trim();
                    const section = document.getElementById('newClassSection').value;
                    const base = document.getElementById('newClassBase').value;
                    const course = document.getElementById('newClassCourse').value;
                    const lesson = document.getElementById('newClassLesson').value;

                    if (!className) {
                        Swal.showValidationMessage('نام کلاس الزامی است');
                        return false;
                    }
                    if (!lesson) {
                        Swal.showValidationMessage('لطفاً درس را انتخاب کنید');
                        return false;
                    }

                    const gradeNumber =
                        base === 'دهم' ? 10 :
                        base === 'یازدهم' ? 11 :
                        base === 'دوازدهم' ? 12 : 10;

                    return {
                        title: className,
                        grade: gradeNumber,
                        subject: `${section} - ${base} - ${course}`,
                        description: `مقطع: ${section} | پایه: ${base} | رشته: ${course} | درس: ${lesson}`,
                        lesson,
                        section,
                        course,
                        base // ✅ حتما برگردون
                    };
                }
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                const data = result.value;

                Swal.fire({
                    title: 'در حال ایجاد کلاس...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    // ✅ ارسال مطمئن با FormData (سازگار با Laravel)
                    const fd = new FormData();
                    fd.append('title', data.title);
                    fd.append('grade', data.grade);
                    fd.append('subject', data.subject);
                    fd.append('description', data.description);
                    fd.append('is_active', 1);
                    fd.append('metadata', JSON.stringify({
                        section: data.section,
                        base: data.base,
                        course: data.course,
                        lesson: data.lesson
                    }));

                    const res = await fetch("{{ route('teacher.classes.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        },
                        body: fd
                    });

                    const responseData = await res.json();
                    Swal.close();

                    if (responseData.success) {
                        Swal.fire({
                            title: "✅ موفقیت!",
                            html: `
                    <div style="text-align:right;">
                        <p><strong>کلاس ایجاد شد:</strong></p>
                        <p>🎓 مقطع: ${data.section}</p>
                        <p>📊 پایه: ${data.base}</p>
                        <p>🔧 رشته: ${data.course}</p>
                        <p>📖 درس: ${data.lesson}</p>
                    </div>
                    `,
                            icon: "success",
                            confirmButtonText: "باشه"
                        }).then(() => {
                            loadExistingClassrooms();

                            if (responseData.classroom) {
                                setTimeout(() => {
                                    selectClassroom({
                                            target: document.querySelector(
                                                `[data-classroom-id="${responseData.classroom.id}"]`
                                            )
                                        },
                                        responseData.classroom.id,
                                        responseData.classroom.title
                                    );
                                }, 300);
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "❌ خطا!",
                            text: responseData.message || "خطا در ایجاد کلاس",
                            icon: "error"
                        });
                    }

                } catch (e) {
                    console.error(e);
                    Swal.close();
                    Swal.fire({
                        title: "❌ خطای شبکه یا پاسخ نامعتبر",
                        text: "سرور پاسخ JSON نداد یا ارتباط مشکل دارد.",
                        icon: "error"
                    });
                }
            });
        }

        function selectClassroom(classroomId, classroomName, el = null) {

            // حذف انتخاب قبلی
            document.querySelectorAll('#existingClassroomsContainer .selection-card')
                .forEach(card => card.classList.remove('selected'));

            // انتخاب کارت کلیک‌شده
            if (el) {
                el.classList.add('selected');
            } else {
                const card = document.querySelector(
                    `#existingClassroomsContainer .selection-card[data-classroom-id="${classroomId}"]`
                );
                if (card) card.classList.add('selected');
            }

            // ذخیره در formData
            formData.classroomId = classroomId;
            formData.classroomName = classroomName;
            document.getElementById('classroomId').value = classroomId;

            // فعال کردن دکمه مرحله بعد
            const nextBtn = document.querySelector('.btn-next');
            nextBtn.disabled = false;
            nextBtn.classList.remove('disabled');

            updatePreview();
            saveToLocalStorage();

            // ✅ رفتن خودکار به مرحله بعد
            nextStep();
        }
    </script>
@endpush

@push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ========== DATA ==========
        const examTypeNames = {
            'public': 'آزمون عمومی',
            'class_single': 'کلاسی تک درس',
            'class_comprehensive': 'کلاسی جامع'
        };

        const branchNames = {
            'technical': 'فنی و حرفه‌ای',
            'vocational': 'کاردانش'
        };

        const fieldNames = {
            'industry': 'صنعت',
            'art': 'هنر',
            'services': 'خدمات',
            'agriculture': 'کشاورزی'
        };

        const subjectTypeNames = {
            'base_competency': 'شایستگی پایه',
            'non_technical_competency': 'شایستگی غیرفنی',
            'technical_competency': 'شایستگی فنی',
            'general': 'دروس عمومی',
            'all': 'همه دروس',
            'specialized_competency': 'شایستگی‌های تخصصی'
        };

        const subjectTypeData = {
            'base_competency': {
                name: 'شایستگی پایه',
                coefficient: 6,
                weight: 33.33,
                question_count: 35,
                icon: '🔢',
                description: 'ریاضی، شیمی، فیزیک'
            },
            'non_technical_competency': {
                name: 'شایستگی غیرفنی',
                coefficient: 3,
                weight: 9.5,
                question_count: 20,
                icon: '💼',
                description: 'الزامات محیط کار، کارآفرینی، اخلاق حرفه‌ای'
            },
            'technical_competency': {
                name: 'شایستگی فنی',
                coefficient: 12,
                weight: 57.14,
                question_count: 60,
                icon: '💻',
                description: 'برنامه‌نویسی، شبکه، پایگاه داده، طراحی وب'
            },
            'general': {
                name: 'دروس عمومی',
                coefficient: 1,
                weight: 0,
                question_count: 0,
                icon: '📖',
                description: 'ادبیات، دینی، عربی، زبان'
            },
            'all': {
                name: 'همه دروس',
                coefficient: 21,
                weight: 100,
                question_count: 115,
                icon: '✅',
                description: 'تمام دروس پایه انتخابی'
            }
        };

        const subfieldsData = {
            'services': [{
                    id: 'computer',
                    name: 'کامپیوتر',
                    icon: '💻',
                    description: 'برنامه‌نویسی، شبکه، طراحی وب'
                },
                {
                    id: 'accounting',
                    name: 'حسابداری',
                    icon: '📊',
                    description: 'حسابداری مالی، هزینه، مالیاتی'
                },
                {
                    id: 'management',
                    name: 'مدیریت',
                    icon: '👨‍💼',
                    description: 'مدیریت بازرگانی، اداری'
                },
                {
                    id: 'tourism',
                    name: 'گردشگری',
                    icon: '🏨',
                    description: 'هتلداری، راهنمای تور'
                }
            ],
            'industry': [{
                    id: 'electric',
                    name: 'برق',
                    icon: '⚡',
                    description: 'برق صنعتی، الکترونیک'
                },
                {
                    id: 'mechanical',
                    name: 'مکانیک',
                    icon: '🔧',
                    description: 'مکانیک خودرو، صنعتی'
                },
                {
                    id: 'construction',
                    name: 'ساختمان',
                    icon: '🏗️',
                    description: 'ساختمان، تأسیسات'
                },
                {
                    id: 'automotive',
                    name: 'خودرو',
                    icon: '🚗',
                    description: 'تعمیرات خودرو'
                }
            ],
            'art': [{
                    id: 'graphic',
                    name: 'گرافیک',
                    icon: '🎨',
                    description: 'گرافیک کامپیوتری، تصویرسازی'
                },
                {
                    id: 'music',
                    name: 'موسیقی',
                    icon: '🎵',
                    description: 'نوازندگی، آهنگسازی'
                },
                {
                    id: 'clothing',
                    name: 'پوشاک',
                    icon: '👕',
                    description: 'طراحی و دوخت لباس'
                }
            ],
            'agriculture': [{
                    id: 'farming',
                    name: 'زراعت',
                    icon: '🌾',
                    description: 'کشاورزی، باغبانی'
                },
                {
                    id: 'livestock',
                    name: 'دامپروری',
                    icon: '🐄',
                    description: 'دامداری، طیور'
                }
            ]
        };

        const subjectsData = {
            'computer': {
                'base_competency': [{
                        id: 1,
                        name: 'ریاضی ۱',
                        code: 'CP101',
                        hours: 3
                    },
                    {
                        id: 2,
                        name: 'ریاضی ۲',
                        code: 'CP102',
                        hours: 3
                    },
                    {
                        id: 3,
                        name: 'ریاضی ۳',
                        code: 'CP103',
                        hours: 3
                    },
                    {
                        id: 4,
                        name: 'شیمی',
                        code: 'CP104',
                        hours: 2
                    },
                    {
                        id: 5,
                        name: 'فیزیک',
                        code: 'CP105',
                        hours: 2
                    }
                ],
                'non_technical_competency': [{
                        id: 6,
                        name: 'الزامات محیط کار',
                        code: 'CP201',
                        hours: 2
                    },
                    {
                        id: 7,
                        name: 'کاربرد فناوری‌های نوین',
                        code: 'CP202',
                        hours: 2
                    },
                    {
                        id: 8,
                        name: 'کارگاه نوآوری و کارآفرینی',
                        code: 'CP203',
                        hours: 3
                    },
                    {
                        id: 9,
                        name: 'اخلاق حرفه‌ای',
                        code: 'CP204',
                        hours: 1
                    }
                ],
                'technical_competency': [{
                        id: 10,
                        name: 'دانش فنی پایه',
                        code: 'CP301',
                        hours: 4
                    },
                    {
                        id: 11,
                        name: 'دانش فنی تخصصی',
                        code: 'CP302',
                        hours: 4
                    },
                    {
                        id: 12,
                        name: 'نصب و راه‌اندازی سیستم‌های رایانه‌ای',
                        code: 'CP303',
                        hours: 6
                    },
                    {
                        id: 13,
                        name: 'تولید محتوای الکترونیک و برنامه‌سازی',
                        code: 'CP304',
                        hours: 6
                    },
                    {
                        id: 14,
                        name: 'توسعه برنامه‌سازی و پایگاه داده',
                        code: 'CP305',
                        hours: 6
                    },
                    {
                        id: 15,
                        name: 'پیاده‌سازی سیستم‌های اطلاعاتی و طراحی وب',
                        code: 'CP306',
                        hours: 6
                    },
                    {
                        id: 16,
                        name: 'نصب و نگهداری تجهیزات شبکه و سخت‌افزار',
                        code: 'CP307',
                        hours: 6
                    },
                    {
                        id: 17,
                        name: 'تجارت الکترونیک و امنیت شبکه',
                        code: 'CP308',
                        hours: 4
                    }
                ],
                'general': [{
                        id: 18,
                        name: 'ادبیات فارسی',
                        code: 'GEN101',
                        hours: 3
                    },
                    {
                        id: 19,
                        name: 'زبان عربی',
                        code: 'GEN102',
                        hours: 2
                    },
                    {
                        id: 20,
                        name: 'دین و زندگی',
                        code: 'GEN103',
                        hours: 2
                    },
                    {
                        id: 21,
                        name: 'زبان انگلیسی',
                        code: 'GEN104',
                        hours: 2
                    }
                ]
            },
            'accounting': {
                'base_competency': [{
                        id: 22,
                        name: 'ریاضی ۱',
                        code: 'AC101',
                        hours: 3
                    },
                    {
                        id: 23,
                        name: 'ریاضی ۲',
                        code: 'AC102',
                        hours: 3
                    },
                    {
                        id: 24,
                        name: 'اقتصاد',
                        code: 'AC103',
                        hours: 2
                    },
                    {
                        id: 25,
                        name: 'حقوق و مقررات',
                        code: 'AC104',
                        hours: 2
                    }
                ],
                'non_technical_competency': [{
                        id: 26,
                        name: 'الزامات محیط کار',
                        code: 'AC201',
                        hours: 2
                    },
                    {
                        id: 27,
                        name: 'کاربرد فناوری‌های نوین',
                        code: 'AC202',
                        hours: 2
                    },
                    {
                        id: 28,
                        name: 'کارگاه نوآوری و کارآفرینی',
                        code: 'AC203',
                        hours: 3
                    }
                ],
                'technical_competency': [{
                        id: 29,
                        name: 'اصول حسابداری',
                        code: 'AC301',
                        hours: 5
                    },
                    {
                        id: 30,
                        name: 'حسابداری صنعتی',
                        code: 'AC302',
                        hours: 5
                    },
                    {
                        id: 31,
                        name: 'حسابداری مالیاتی',
                        code: 'AC303',
                        hours: 4
                    },
                    {
                        id: 32,
                        name: 'حسابداری کامپیوتری',
                        code: 'AC304',
                        hours: 6
                    },
                    {
                        id: 33,
                        name: 'حسابرسی',
                        code: 'AC305',
                        hours: 4
                    }
                ],
                'general': [{
                        id: 34,
                        name: 'ادبیات فارسی',
                        code: 'GEN101',
                        hours: 3
                    },
                    {
                        id: 35,
                        name: 'زبان عربی',
                        code: 'GEN102',
                        hours: 2
                    },
                    {
                        id: 36,
                        name: 'دین و زندگی',
                        code: 'GEN103',
                        hours: 2
                    }
                ]
            }
        };

        // ========== STATE ==========
        let currentStep = 1;
        let formData = {
            examType: '',
            classroomId: null,
            classroomName: '',
            grade: '',
            branch: '',
            field: '',
            subfield: '',
            subjectType: '',
            selectedSubjects: [],
            coefficients: {},
            totalQuestions: 0,
            totalCoefficient: 0,
            totalWeight: 0
        };

        // ========== INIT ==========
        document.addEventListener('DOMContentLoaded', () => {
            updateProgress();
            updateNavigationButtons();
            loadFromLocalStorage();
        });

        // ========== TOAST ==========
        function showToast(message, icon = 'error') {
            Swal.fire({
                toast: true,
                position: 'top-start',
                icon,
                title: message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }

        // ========== STEP 1 ==========
        function selectExamType(type) {
            document.querySelectorAll('.type-card').forEach(card => card.classList.remove('selected'));
            document.querySelector(`.type-card[data-type="${type}"]`)?.classList.add('selected');

            formData.examType = type;
            document.getElementById('examType').value = type;

            const classroomSection = document.getElementById('classroomSelectionSection');
            const nextBtn = document.querySelector('.btn-next');

            if (type === 'public') {
                classroomSection.style.display = 'none';
                formData.classroomId = null;
                document.getElementById('classroomId').value = '';
                nextBtn.disabled = false;
                nextBtn.classList.remove('disabled');
            } else {
                classroomSection.style.display = 'block';
                loadExistingClassrooms();
                nextBtn.disabled = true;
                nextBtn.classList.add('disabled');
            }

            updatePreview();
            saveToLocalStorage();
        }

        function loadExistingClassrooms() {
            const container = document.getElementById('existingClassroomsContainer');

            container.innerHTML = `
                <div class="loading-spinner" style="grid-column: 1 / -1; text-align: center; padding: 20px;">
                    <i class="fas fa-spinner fa-spin"></i>
                    در حال بارگذاری کلاس‌ها...
                </div>
            `;

            fetch('/dashboard/teacher/classes?ajax=1', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    container.innerHTML = '';

                    if (data.classrooms && data.classrooms.length > 0) {
                        data.classrooms.forEach(classroom => {
                            const card = document.createElement('div');
                            card.className = 'selection-card';
                            card.dataset.classroomId = classroom.id;
                            card.innerHTML = `
                            <div class="selection-icon">🏫</div>
                            <div class="selection-name">${classroom.title}</div>
                            <p class="selection-description">
                                <small>${classroom.grade || 'بدون پایه'} - ${classroom.subject || 'بدون موضوع'}</small>
                                <br>
                                <strong>${classroom.students_count || 0} هنرجو</strong>
                            </p>
                        `;
                            card.onclick = (e) => selectClassroom(e, classroom.id, classroom.title);
                            container.appendChild(card);
                        });

                        if (formData.classroomId) {
                            container.querySelector(`[data-classroom-id="${formData.classroomId}"]`)?.classList.add(
                                'selected');
                            document.querySelector('.btn-next').disabled = false;
                            document.querySelector('.btn-next').classList.remove('disabled');
                        }
                    } else {
                        container.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--gray);">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px;"></i>
                            <h4>کلاسی یافت نشد</h4>
                            <p>شما هنوز کلاسی ایجاد نکرده‌اید.</p>
                            <p>برای ادامه، یک کلاس جدید ایجاد کنید.</p>
                        </div>
                    `;
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--warning);">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h4>خطا در بارگذاری کلاس‌ها</h4>
                        <p>مشکلی در دریافت اطلاعات کلاس‌ها پیش آمده است.</p>
                        <button onclick="loadExistingClassrooms()" class="btn-nav" style="padding: 10px 20px; margin-top: 15px;">
                            تلاش مجدد
                        </button>
                    </div>
                `;
                });
        }


        // SweetAlert classroom creator -> unchanged (your code)
        // ... (همان createNewClassroom شما، بدون تغییر)

        // اعتبارسنجی مرحله اول
        function validateStep1() {
            if (!formData.examType) {
                showToast('لطفاً نوع آزمون را انتخاب کنید.', 'error');
                return false;
            }
            if (formData.examType !== 'public' && !formData.classroomId) {
                showToast('لطفاً یک کلاس انتخاب کنید یا کلاس جدید ایجاد نمایید.', 'error');
                return false;
            }
            return true;
        }

        // ========== STEP 2 ==========
        function selectGrade(e, grade) {
            document.querySelectorAll('#step2 .selection-card').forEach(c => c.classList.remove('selected'));
            e.target.closest('.selection-card').classList.add('selected');

            formData.grade = grade;
            document.getElementById('grade').value = grade;

            updatePreview();
            saveToLocalStorage();
        }

        // ========== STEP 3 ==========
        function selectBranch(e, branch) {
            document.querySelectorAll('#step3 .selection-card').forEach(c => c.classList.remove('selected'));
            e.target.closest('.selection-card').classList.add('selected');

            formData.branch = branch;
            document.getElementById('branch').value = branch;

            updatePreview();
            saveToLocalStorage();
        }

        // ========== STEP 4 ==========
        function selectField(e, field) {
            document.querySelectorAll('#step4 .selection-card').forEach(c => c.classList.remove('selected'));
            e.target.closest('.selection-card').classList.add('selected');

            formData.field = field;
            document.getElementById('field').value = field;

            loadSubfields(field);

            updatePreview();
            saveToLocalStorage();
        }

        function loadSubfields(field) {
            const container = document.getElementById('subfieldGrid');
            container.innerHTML = '';

            if (subfieldsData[field]) {
                subfieldsData[field].forEach(subfield => {
                    const card = document.createElement('div');
                    card.className = 'selection-card';
                    card.innerHTML = `
                        <div class="selection-icon">${subfield.icon}</div>
                        <div class="selection-name">${subfield.name}</div>
                        <p class="selection-description">${subfield.description}</p>
                    `;
                    card.onclick = (e) => selectSubfield(e, subfield.id, subfield.name);
                    container.appendChild(card);
                });
            }
        }

        // ========== STEP 5 ==========
        function selectSubfield(e, id, name) {
            document.querySelectorAll('#subfieldGrid .selection-card').forEach(c => c.classList.remove('selected'));
            e.target.closest('.selection-card').classList.add('selected');

            formData.subfield = id;
            document.getElementById('subfield').value = id;

            updatePreview();
            saveToLocalStorage();
        }

        // ========== STEP 6 ==========
        function selectSubjectType(e, type) {
            document.querySelectorAll('#step6 .selection-card').forEach(c => c.classList.remove('selected'));
            e.target.closest('.selection-card').classList.add('selected');

            formData.subjectType = type;
            document.getElementById('subjectType').value = type;

            calculateCoefficients(type);

            updatePreview();
            saveToLocalStorage();
        }

        function calculateCoefficients(type) {
            const data = subjectTypeData[type] || {};

            formData.coefficients = {
                coefficient: data.coefficient || 0,
                weight: data.weight || 0,
                questionCount: data.question_count || 0
            };

            formData.totalQuestions = data.question_count || 0;
            formData.totalCoefficient = data.coefficient || 0;
            formData.totalWeight = data.weight || 0;

            showCoefficientSettings(type);
        }

        function showCoefficientSettings(type) {
            const container = document.getElementById('coefficientSettings');
            const data = subjectTypeData[type];

            if (!data) {
                container.innerHTML = '';
                return;
            }

            if (type === 'all') {
                container.innerHTML = `
                    <div class="coefficient-item base-competency">
                        <div class="coeff-header"><strong>شایستگی پایه</strong><span class="coeff-badge">ضریب: ۶</span></div>
                        <div class="coeff-row"><span>تعداد سوال:</span><span class="coeff-value">۳۵</span></div>
                        <div class="coeff-row"><span>درصد اهمیت:</span><span class="coeff-value">۳۳.۳۳٪</span></div>
                    </div>
                    <div class="coefficient-item non-tech-competency">
                        <div class="coeff-header"><strong>شایستگی غیرفنی</strong><span class="coeff-badge">ضریب: ۳</span></div>
                        <div class="coeff-row"><span>تعداد سوال:</span><span class="coeff-value">۲۰</span></div>
                        <div class="coeff-row"><span>درصد اهمیت:</span><span class="coeff-value">۹.۵٪</span></div>
                    </div>
                    <div class="coefficient-item tech-competency">
                        <div class="coeff-header"><strong>شایستگی فنی</strong><span class="coeff-badge">ضریب: ۱۲</span></div>
                        <div class="coeff-row"><span>تعداد سوال:</span><span class="coeff-value">۶۰</span></div>
                        <div class="coeff-row"><span>درصد اهمیت:</span><span class="coeff-value">۵۷.۱۴٪</span></div>
                    </div>
                    <div class="total-calculation">
                        <div class="total-row"><span>مجموع سوالات:</span><strong>۱۱۵</strong></div>
                        <div class="total-row"><span>مجموع ضرایب:</span><strong>۲۱</strong></div>
                        <div class="total-row"><span>مجموع وزنی:</span><strong>۱۰۰٪</strong></div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="coefficient-item ${type.replaceAll('_','-')}">
                        <div class="coeff-header">
                            <strong>${data.name}</strong>
                            <span class="coeff-badge">ضریب: ${data.coefficient}</span>
                        </div>
                        <div class="coeff-row"><span>تعداد سوال پیشنهادی:</span><span class="coeff-value">${data.question_count}</span></div>
                        <div class="coeff-row"><span>درصد اهمیت:</span><span class="coeff-value">${data.weight}٪</span></div>
                        <div class="coeff-row"><span>توضیحات:</span><span class="coeff-value">${data.description}</span></div>
                    </div>
                `;
            }
        }

        // ========== STEP 7 ==========
        function loadSubjects() {
            const container = document.getElementById('subjectsContainer');

            if (!formData.subfield || !formData.subjectType) {
                container.innerHTML =
                    '<div class="alert alert-warning">لطفاً ابتدا زیررشته و دسته درسی را انتخاب کنید.</div>';
                return;
            }

            container.innerHTML =
                '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> در حال بارگذاری دروس...</div>';

            setTimeout(() => {
                const subjects = subjectsData[formData.subfield]?.[formData.subjectType] || [];

                if (subjects.length === 0) {
                    container.innerHTML = '<div class="alert alert-info">هیچ درسی برای این دسته یافت نشد.</div>';
                    return;
                }

                displaySubjects(subjects);
            }, 400);
        }

        function displaySubjects(subjects) {
            const container = document.getElementById('subjectsContainer');
            container.innerHTML = '';

            subjects.forEach(subject => {
                const item = document.createElement('div');
                item.className = 'subject-item';
                item.innerHTML = `
                    <div class="subject-checkbox">
                        <input type="checkbox" id="subject_${subject.id}"
                               value="${subject.id}" onchange="updateSelectedSubjects()">
                    </div>
                    <div class="subject-info">
                        <div class="subject-name">${subject.name}</div>
                        <div class="subject-meta">
                            <span class="subject-code">${subject.code}</span>
                            <span>${subject.hours} ساعت</span>
                        </div>
                    </div>
                `;
                container.appendChild(item);
            });

            if (formData.subjectType === 'all') {
                setTimeout(() => {
                    document.querySelectorAll('.subject-checkbox input').forEach(cb => cb.checked = true);
                    updateSelectedSubjects();
                }, 100);
            }
        }

        function updateSelectedSubjects() {
            const checkboxes = document.querySelectorAll('.subject-checkbox input:checked');
            formData.selectedSubjects = Array.from(checkboxes).map(cb => parseInt(cb.value));

            document.getElementById('previewSubjectsCount').textContent = formData.selectedSubjects.length + ' درس';
            document.getElementById('subjectsInput').value = formData.selectedSubjects.join(',');

            saveToLocalStorage();
        }

        // ========== STEP 8 ==========
        function showFinalCoefficientSettings() {
            const container = document.getElementById('finalCoefficientSettings');
            const type = formData.subjectType;
            const data = subjectTypeData[type];
            if (!data) return;

            if (type === 'all') {
                container.innerHTML = `
                    <div class="total-calculation">
                        <h4>📊 محاسبات نهایی آزمون</h4>
                        <div class="total-row"><span>کل سوالات:</span><strong>۱۱۵ سوال</strong></div>
                        <div class="total-row"><span>توزیع سوالات:</span><strong>۳۵ + ۲۰ + ۶۰</strong></div>
                        <div class="total-row"><span>ضرایب:</span><strong>۶ + ۳ + ۱۲ = ۲۱</strong></div>
                        <div class="total-row"><span>وزن‌ها:</span><strong>۳۳.۳۳٪ + ۹.۵٪ + ۵۷.۱۴٪ = ۱۰۰٪</strong></div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="total-calculation">
                        <h4>📊 محاسبات نهایی آزمون</h4>
                        <div class="total-row"><span>دسته درسی:</span><strong>${data.name}</strong></div>
                        <div class="total-row"><span>تعداد سوالات:</span><strong>${data.question_count} سوال</strong></div>
                        <div class="total-row"><span>ضریب:</span><strong>${data.coefficient}</strong></div>
                        <div class="total-row"><span>درصد اهمیت:</span><strong>${data.weight}٪</strong></div>
                    </div>
                `;
            }

            document.getElementById('previewTotalQuestions').textContent = formData.totalQuestions + ' سوال';
        }

        function suggestExamTitle() {
            const titleInput = document.getElementById('examTitle');
            if (titleInput.value.trim() !== '') return;

            let title = '';
            if (formData.subjectType === 'all') {
                title =
                    `آزمون جامع پایه ${formData.grade} ${fieldNames[formData.field] || ''} - ${formData.subfield || ''}`;
            } else {
                const subjectTypeName = subjectTypeNames[formData.subjectType] || '';
                title = `آزمون ${subjectTypeName} پایه ${formData.grade} - ${formData.subfield || ''}`;
            }
            titleInput.value = title;
        }

        // ========== NAV ==========
        function nextStep() {
            if (!validateCurrentStep()) return;

            if (currentStep < 8) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.add('active');

                handleStepChange();
                updateProgress();
                updateNavigationButtons();
                updatePreview();
                saveToLocalStorage();

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');

                updateProgress();
                updateNavigationButtons();
                updatePreview();
                saveToLocalStorage();

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function handleStepChange() {
            if (currentStep === 7) loadSubjects();
            if (currentStep === 8) {
                showFinalCoefficientSettings();
                suggestExamTitle();
            }
        }

        function validateCurrentStep() {
            let isValid = true;
            let message = '';

            switch (currentStep) {
                case 1:
                    isValid = validateStep1();
                    break;
                case 2:
                    if (!formData.grade) {
                        message = 'لطفاً پایه تحصیلی را انتخاب کنید.';
                        isValid = false;
                    }
                    break;
                case 3:
                    if (!formData.branch) {
                        message = 'لطفاً شاخه تحصیلی را انتخاب کنید.';
                        isValid = false;
                    }
                    break;
                case 4:
                    if (!formData.field) {
                        message = 'لطفاً زمینه فنی را انتخاب کنید.';
                        isValid = false;
                    }
                    break;
                case 5:
                    if (!formData.subfield) {
                        message = 'لطفاً زیررشته را انتخاب کنید.';
                        isValid = false;
                    }
                    break;
                case 6:
                    if (!formData.subjectType) {
                        message = 'لطفاً دسته درسی را انتخاب کنید.';
                        isValid = false;
                    }
                    break;
                case 7:
                    if (formData.selectedSubjects.length === 0 && formData.subjectType !== 'all') {
                        message = 'لطفاً حداقل یک درس انتخاب کنید.';
                        isValid = false;
                    }
                    break;
            }

            if (!isValid && message) showToast(message, 'error');
            return isValid;
        }

        function validateFinalStep() {
            if (!formData.examType || !formData.grade || !formData.branch ||
                !formData.field || !formData.subfield || !formData.subjectType) {
                showToast('لطفاً تمام مراحل را تکمیل کنید.', 'error');
                return false;
            }

            if (formData.selectedSubjects.length === 0 && formData.subjectType !== 'all') {
                showToast('لطفاً حداقل یک درس انتخاب کنید.', 'error');
                return false;
            }

            localStorage.removeItem('examFormData');
            localStorage.removeItem('examCurrentStep');

            return true;
        }

        // ========== PREVIEW ==========
        function updatePreview() {
            document.getElementById('previewExamType').textContent = examTypeNames[formData.examType] || '--';
            document.getElementById('previewGrade').textContent = formData.grade ? `پایه ${formData.grade}` : '--';
            document.getElementById('previewBranch').textContent = branchNames[formData.branch] || '--';
            document.getElementById('previewField').textContent = fieldNames[formData.field] || '--';

            if (formData.field && formData.subfield) {
                const subfield = subfieldsData[formData.field]?.find(s => s.id === formData.subfield);
                document.getElementById('previewSubfield').textContent = subfield?.name || '--';
            } else {
                document.getElementById('previewSubfield').textContent = '--';
            }

            document.getElementById('previewSubjectType').textContent = subjectTypeNames[formData.subjectType] || '--';
            document.getElementById('previewSubjectsCount').textContent =
                formData.selectedSubjects.length ? (formData.selectedSubjects.length + ' درس') : '--';
        }

        // ========== PROGRESS ==========
        function updateProgress() {
            const progress = (currentStep / 8) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;

            document.querySelectorAll('.step-item').forEach((item, index) => {
                item.classList.remove('active', 'completed');
                if (index + 1 < currentStep) item.classList.add('completed');
                else if (index + 1 === currentStep) item.classList.add('active');
            });
        }

        function updateNavigationButtons() {
            const prevBtn = document.querySelector('.btn-prev');
            const nextBtn = document.querySelector('.btn-next');
            const submitBtn = document.querySelector('.btn-submit');

            if (currentStep === 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'flex';
                submitBtn.style.display = 'none';
            } else if (currentStep === 8) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
                submitBtn.style.display = 'none';
            }
        }

        // ========== LOCAL STORAGE ==========
        function saveToLocalStorage() {
            localStorage.setItem('examFormData', JSON.stringify(formData));
            localStorage.setItem('examCurrentStep', currentStep);
        }

        function loadFromLocalStorage() {
            const savedData = localStorage.getItem('examFormData');
            const savedStep = localStorage.getItem('examCurrentStep');

            if (savedData) {
                formData = JSON.parse(savedData);
                if (savedStep) currentStep = parseInt(savedStep);

                restoreSelections();
                updatePreview();
                updateProgress();
                updateNavigationButtons();
            }
        }

        function restoreSelections() {
            // step1
            if (formData.examType) {
                document.querySelector(`.type-card[data-type="${formData.examType}"]`)?.classList.add('selected');
                if (formData.examType !== 'public') {
                    document.getElementById('classroomSelectionSection').style.display = 'block';
                    loadExistingClassrooms();
                }
            }

            // step2
            if (formData.grade) {
                document.querySelectorAll('#step2 .selection-card').forEach(card => {
                    if (card.textContent.includes(`پایه ${formData.grade}`)) card.classList.add('selected');
                });
            }

            // step3
            if (formData.branch) {
                document.querySelectorAll('#step3 .selection-card').forEach(card => {
                    if (card.textContent.includes(branchNames[formData.branch])) card.classList.add('selected');
                });
            }

            // step4 & step5
            if (formData.field) {
                document.querySelectorAll('#step4 .selection-card').forEach(card => {
                    if (card.classList.contains(formData.field)) card.classList.add('selected');
                });
                loadSubfields(formData.field);
                setTimeout(() => {
                    if (formData.subfield) {
                        document.querySelectorAll('#subfieldGrid .selection-card').forEach(card => {
                            if (card.textContent.includes(formData.subfield)) card.classList.add(
                                'selected');
                        });
                    }
                }, 100);
            }

            // step6
            if (formData.subjectType) {
                setTimeout(() => {
                    document.querySelectorAll('#step6 .selection-card').forEach(card => {
                        if (card.textContent.includes(subjectTypeNames[formData.subjectType])) card
                            .classList.add('selected');
                    });
                    calculateCoefficients(formData.subjectType);
                }, 150);
            }
        }

        // پاکسازی LS بعد از submit
        document.getElementById('examForm').addEventListener('submit', function() {
            localStorage.removeItem('examFormData');
            localStorage.removeItem('examCurrentStep');
        });
    </script>
@endpush
