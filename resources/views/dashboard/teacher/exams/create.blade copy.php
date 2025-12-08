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

            /* رنگ‌های پایه‌ها */
            --elementary: #4A90E2;
            --middle-school: #32CD32;
            --high-school: #FFA500;
            --math-field: #FF6B6B;
            --science-field: #4ECDC4;
            --humanities-field: #FFD166;
            --technical-field: #A663CC;
            --vocational-field: #06D6A0;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f8fcfc;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .create-exam-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px 15px 80px;
            animation: fadeIn 0.6s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ========== HEADER ========== */
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
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
        }

        .btn-back:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== PROGRESS BAR ========== */
        .progress-container {
            margin-bottom: 40px;
            animation: slideUp 0.5s ease-out;
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
            width: 17%;
            transition: width 0.6s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
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
            transition: all 0.3s;
        }

        .step-item.active .step-name {
            color: var(--primary);
            font-weight: 900;
        }

        /* ========== FORM CONTAINER ========== */
        .form-container {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 40px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(0, 206, 209, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(0, 206, 209, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        /* ========== FORM SECTIONS ========== */
        .form-section {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-section.active {
            display: block;
        }

        .section-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .section-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--primary);
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

        /* ========== EXAM TYPE SELECTION ========== */
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
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            transition: all 0.3s;
        }

        .type-card.selected .type-icon {
            color: var(--dark);
            transform: scale(1.1);
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

        /* ========== EDUCATIONAL LEVEL SELECTION ========== */
        .level-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .level-tab {
            padding: 15px 30px;
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            background: var(--light);
            font-weight: 900;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .level-tab:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .level-tab.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .level-tab.elementary.selected {
            border-color: var(--elementary);
            background: rgba(74, 144, 226, 0.1);
            color: var(--elementary);
        }

        .level-tab.middle-school.selected {
            border-color: var(--middle-school);
            background: rgba(50, 205, 50, 0.1);
            color: var(--middle-school);
        }

        .level-tab.high-school.selected {
            border-color: var(--high-school);
            background: rgba(255, 165, 0, 0.1);
            color: var(--high-school);
        }

        /* ========== GRADE SELECTION ========== */
        .grade-grid {
            display: grid;
            gap: 15px;
            margin-bottom: 30px;
        }

        .grade-grid.elementary {
            grid-template-columns: repeat(6, 1fr);
        }

        .grade-grid.middle-school {
            grid-template-columns: repeat(3, 1fr);
        }

        .grade-grid.high-school {
            grid-template-columns: repeat(3, 1fr);
        }

        @media (max-width: 768px) {
            .grade-grid.elementary {
                grid-template-columns: repeat(3, 1fr);
            }

            .grade-grid.middle-school,
            .grade-grid.high-school {
                grid-template-columns: 1fr;
            }
        }

        .grade-card {
            padding: 20px 15px;
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--light);
            font-weight: 900;
            font-size: 1.2rem;
        }

        .grade-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .grade-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        /* ========== FIELD SELECTION ========== */
        .field-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        @media (max-width: 992px) {
            .field-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .field-grid {
                grid-template-columns: 1fr;
            }
        }

        .field-card {
            padding: 25px 15px;
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--light);
        }

        .field-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .field-card.selected {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .field-card[data-field="math"].selected {
            border-color: var(--math-field);
            background: rgba(255, 107, 107, 0.1);
        }

        .field-card[data-field="science"].selected {
            border-color: var(--science-field);
            background: rgba(78, 205, 196, 0.1);
        }

        .field-card[data-field="humanities"].selected {
            border-color: var(--humanities-field);
            background: rgba(255, 209, 102, 0.1);
        }

        .field-card[data-field="technical"].selected {
            border-color: var(--technical-field);
            background: rgba(166, 99, 204, 0.1);
        }

        .field-card[data-field="vocational"].selected {
            border-color: var(--vocational-field);
            background: rgba(6, 214, 160, 0.1);
        }

        .field-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .field-title {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .field-description {
            color: var(--gray);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* ========== SUBJECT SELECTION ========== */
        .subject-section {
            margin-bottom: 30px;
        }

        .subject-type-toggle {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .subject-type-btn {
            padding: 15px 30px;
            border: 3px solid var(--light-gray);
            border-radius: var(--radius-lg);
            background: var(--light);
            font-weight: 900;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .subject-type-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .subject-type-btn.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        @media (max-width: 768px) {
            .subject-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .subject-grid {
                grid-template-columns: 1fr;
            }
        }

        .subject-card {
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            padding: 20px 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            background: var(--light);
        }

        .subject-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .subject-card.selected {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .subject-name {
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .subject-code {
            font-size: 0.85rem;
            color: var(--gray);
            background: var(--light-gray);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }

        .subject-hours {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .comprehensive-subjects {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed var(--primary);
        }

        .comprehensive-title {
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .subject-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .subject-tag {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ========== EXAM DETAILS ========== */
        .details-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
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

        .form-label i {
            color: var(--primary);
            background: var(--primary-light);
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
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

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 206, 209, 0.2);
        }

        .checkbox-group {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin: 30px 0;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .checkbox-group:hover {
            border-color: var(--primary-light);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
        }

        .form-check-input {
            width: 22px;
            height: 22px;
            border: 2px solid var(--gray);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-weight: 900;
            color: var(--dark);
            font-size: 1.05rem;
            cursor: pointer;
        }

        /* ========== PREVIEW SECTION ========== */
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

        /* ========== NAVIGATION BUTTONS ========== */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            position: relative;
            z-index: 2;
        }

        .btn-nav {
            padding: 16px 30px;
            border-radius: var(--radius-lg);
            font-weight: 900;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            border: 2px solid transparent;
            min-width: 150px;
            justify-content: center;
        }

        .btn-nav:active {
            transform: scale(0.98);
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
            background: var(--success);
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(50, 205, 50, 0.4);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .create-exam-container {
                padding: 15px 10px 60px;
            }

            .page-header {
                padding: 20px;
            }

            .header-title h1 {
                font-size: 1.5rem;
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

            .level-tabs {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .grade-grid.elementary {
                grid-template-columns: repeat(2, 1fr);
            }

            .subject-grid {
                grid-template-columns: 1fr;
            }

            .preview-grid {
                grid-template-columns: 1fr;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-nav,
        .btn-back,
        .form-input,
        .form-textarea {
            min-height: 48px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(0, 206, 209, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="create-exam-container">
        {{-- ========== PAGE HEADER ========== --}}
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>
                        <span
                            style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            ساخت آزمون جدید
                        </span>
                        📝
                    </h1>
                    <p class="header-subtitle">
                        آزمون خود را به صورت مرحله‌ای و با دقت ایجاد کنید.
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
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-steps">
                <div class="step-item active" data-step="1">
                    <div class="step-number">۱</div>
                    <div class="step-name">نوع آزمون</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-number">۲</div>
                    <div class="step-name">سطح آموزشی</div>
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-number">۳</div>
                    <div class="step-name">پایه تحصیلی</div>
                </div>
                <div class="step-item" data-step="4">
                    <div class="step-number">۴</div>
                    <div class="step-name">رشته تحصیلی</div>
                </div>
                <div class="step-item" data-step="5">
                    <div class="step-number">۵</div>
                    <div class="step-name">انتخاب درس</div>
                </div>
                <div class="step-item" data-step="6">
                    <div class="step-number">۶</div>
                    <div class="step-name">جزئیات آزمون</div>
                </div>
            </div>
        </div>

        {{-- ========== FORM CONTAINER ========== --}}
        <div class="form-container">
            <form method="POST" action="{{ route('teacher.exams.store') }}" id="examForm">
                @csrf

                {{-- Hidden Inputs for Form Data --}}
                <input type="hidden" name="exam_type" id="examType" value="public">
                <input type="hidden" name="education_level" id="educationLevel" value="">
                <input type="hidden" name="grade" id="grade" value="">
                <input type="hidden" name="field" id="field" value="">
                <input type="hidden" name="subject_type" id="subjectType" value="single">
                <input type="hidden" name="subject_id" id="subjectId" value="">
                <input type="hidden" name="classroom_id" id="classroomId" value="{{ $selectedClassroomId ?? '' }}">

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
                            <p class="type-description">
                                برای تمام دانش‌آموزان قابل دسترسی است.
                                نیازی به عضویت در کلاس ندارد.
                            </p>
                            <div class="type-badge">عمومی</div>
                        </div>

                        <div class="type-card" data-type="class_single" onclick="selectExamType('class_single')">
                            <div class="type-icon">📚</div>
                            <div class="type-title">کلاسی تک درس</div>
                            <p class="type-description">
                                برای یک کلاس خاص و فقط یک درس مشخص.
                                تمرکز کامل بر یک موضوع درسی.
                            </p>
                            <div class="type-badge">تخصصی</div>
                        </div>

                        <div class="type-card" data-type="class_comprehensive"
                            onclick="selectExamType('class_comprehensive')">
                            <div class="type-icon">🎓</div>
                            <div class="type-title">کلاسی جامع</div>
                            <p class="type-description">
                                برای یک کلاس شامل تمام دروس پایه.
                                ارزیابی کامل دانش‌آموزان.
                            </p>
                            <div class="type-badge">جامع</div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 2: EDUCATION LEVEL ===== --}}
                <div class="form-section" id="step2">
                    <div class="section-header">
                        <div class="section-icon">🏫</div>
                        <h2 class="section-title">سطح آموزشی را انتخاب کنید</h2>
                        <p class="section-description">
                            سطح تحصیلی مورد نظر برای آزمون خود را مشخص نمایید.
                        </p>
                    </div>

                    <div class="level-tabs">
                        <div class="level-tab elementary" onclick="selectEducationLevel('elementary')">
                            <i class="fas fa-school"></i>
                            ابتدایی
                        </div>
                        <div class="level-tab middle-school" onclick="selectEducationLevel('middle_school')">
                            <i class="fas fa-book-open"></i>
                            متوسطه اول
                        </div>
                        <div class="level-tab high-school" onclick="selectEducationLevel('high_school')">
                            <i class="fas fa-graduation-cap"></i>
                            متوسطه دوم
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 3: GRADE SELECTION ===== --}}
                <div class="form-section" id="step3">
                    <div class="section-header">
                        <div class="section-icon">📊</div>
                        <h2 class="section-title">پایه تحصیلی را انتخاب کنید</h2>
                        <p class="section-description" id="gradeDescription">
                            پایه مورد نظر برای آزمون خود را انتخاب نمایید.
                        </p>
                    </div>

                    <div class="grade-grid elementary" id="elementaryGrades" style="display: none;">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="grade-card" onclick="selectGrade({{ $i }})">
                                پایه {{ $i }}
                            </div>
                        @endfor
                    </div>

                    <div class="grade-grid middle-school" id="middleSchoolGrades" style="display: none;">
                        @for ($i = 7; $i <= 9; $i++)
                            <div class="grade-card" onclick="selectGrade({{ $i }})">
                                پایه {{ $i }}
                            </div>
                        @endfor
                    </div>

                    <div class="grade-grid high-school" id="highSchoolGrades" style="display: none;">
                        @for ($i = 10; $i <= 12; $i++)
                            <div class="grade-card" onclick="selectGrade({{ $i }})">
                                پایه {{ $i }}
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ===== STEP 4: FIELD SELECTION ===== --}}
                <div class="form-section" id="step4">
                    <div class="section-header">
                        <div class="section-icon">🎓</div>
                        <h2 class="section-title">رشته تحصیلی را انتخاب کنید</h2>
                        <p class="section-description">
                            رشته مورد نظر برای آزمون خود را انتخاب نمایید.
                        </p>
                    </div>

                    <div class="field-grid">
                        <div class="field-card" data-field="math" onclick="selectField('math')">
                            <div class="field-icon">📐</div>
                            <div class="field-title">ریاضی و فیزیک</div>
                            <p class="field-description">رشته نظری ریاضی</p>
                        </div>

                        <div class="field-card" data-field="science" onclick="selectField('science')">
                            <div class="field-icon">🔬</div>
                            <div class="field-title">علوم تجربی</div>
                            <p class="field-description">رشته نظری تجربی</p>
                        </div>

                        <div class="field-card" data-field="humanities" onclick="selectField('humanities')">
                            <div class="field-icon">📖</div>
                            <div class="field-title">علوم انسانی</div>
                            <p class="field-description">رشته نظری انسانی</p>
                        </div>

                        <div class="field-card" data-field="technical" onclick="selectField('technical')">
                            <div class="field-icon">🔧</div>
                            <div class="field-title">فنی و حرفه‌ای</div>
                            <p class="field-description">رشته فنی و حرفه‌ای</p>
                        </div>

                        <div class="field-card" data-field="vocational" onclick="selectField('vocational')">
                            <div class="field-icon">🛠️</div>
                            <div class="field-title">کاردانش</div>
                            <p class="field-description">رشته کاردانش</p>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 5: SUBJECT SELECTION ===== --}}
                <div class="form-section" id="step5">
                    <div class="section-header">
                        <div class="section-icon">📚</div>
                        <h2 class="section-title">درس‌های آزمون را انتخاب کنید</h2>
                        <p class="section-description">
                            بر اساس نوع آزمون، درس‌های مورد نظر را انتخاب نمایید.
                        </p>
                    </div>

                    <div class="subject-section">
                        <div class="subject-type-toggle" id="subjectTypeToggle" style="display: none;">
                            <div class="subject-type-btn" onclick="selectSubjectType('single')">
                                <i class="fas fa-book"></i>
                                آزمون تک درس
                            </div>
                            <div class="subject-type-btn" onclick="selectSubjectType('comprehensive')">
                                <i class="fas fa-books"></i>
                                آزمون جامع
                            </div>
                        </div>

                        <div class="subject-grid" id="singleSubjectGrid" style="display: none;">
                            {{-- دروس به صورت دینامیک لود می‌شوند --}}
                        </div>

                        <div class="comprehensive-subjects" id="comprehensiveSubjects" style="display: none;">
                            <div class="comprehensive-title">
                                <i class="fas fa-check-circle"></i>
                                تمام دروس این پایه به صورت خودکار انتخاب می‌شوند
                            </div>
                            <div class="subject-list" id="comprehensiveSubjectList">
                                {{-- لیست دروس به صورت دینامیک لود می‌شود --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 6: EXAM DETAILS ===== --}}
                <div class="form-section" id="step6">
                    <div class="section-header">
                        <div class="section-icon">✏️</div>
                        <h2 class="section-title">جزئیات آزمون را تکمیل کنید</h2>
                        <p class="section-description">
                            اطلاعات تکمیلی آزمون خود را وارد نمایید.
                        </p>
                    </div>

                    {{-- Preview Section --}}
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
                                <div class="preview-label">سطح آموزشی</div>
                                <div class="preview-value" id="previewEducationLevel">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">پایه تحصیلی</div>
                                <div class="preview-value" id="previewGrade">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">رشته تحصیلی</div>
                                <div class="preview-value" id="previewField">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">درس‌های انتخابی</div>
                                <div class="preview-value" id="previewSubjects">--</div>
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
                            <input type="text" name="title" class="form-input" value="{{ old('title') }}"
                                placeholder="مثال: آزمون ریاضی فصل ۱ - آزمون جامع پایه دهم" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>
                                مدت زمان آزمون (دقیقه)
                            </label>
                            <input type="number" name="duration" class="form-input" value="{{ old('duration', 60) }}"
                                min="5" max="300" step="5" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left"></i>
                                توضیحات آزمون
                            </label>
                            <textarea name="description" class="form-textarea" rows="4"
                                placeholder="هدف آزمون، منابع مطالعاتی، نکات مهم و هر توضیح اضافی...">{{ old('description') }}</textarea>
                        </div>

                        <div class="checkbox-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck"
                                    value="1" checked>
                                <label class="form-check-label" for="activeCheck">
                                    آزمون بلافاصله فعال شود
                                </label>
                            </div>
                            <div class="form-text">
                                در صورت عدم انتخاب، آزمون به صورت پیش‌نویس ذخیره شده و باید بعداً فعال شود.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== NAVIGATION BUTTONS ========== --}}
                <div class="nav-buttons">
                    <button type="button" class="btn-nav btn-prev" onclick="prevStep()" style="display: none;">
                        <i class="fas fa-arrow-right"></i>
                        مرحله قبل
                    </button>
                    <button type="button" class="btn-nav btn-next" onclick="nextStep()">
                        مرحله بعد
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="submit" class="btn-nav btn-submit" style="display: none;">
                        <i class="fas fa-check"></i>
                        ایجاد آزمون
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function createNewClassroom() {

            const endpoints = {
                sections: "/dashboard/teacher/exams/data/sections",
                grades: "/dashboard/teacher/exams/data/grades",
                branches: "/dashboard/teacher/exams/data/branches",
                fields: "/dashboard/teacher/exams/data/fields",
                subfields: "/dashboard/teacher/exams/data/subfields",
                subjectTypes: "/dashboard/teacher/exams/data/subject-types",
                subjects: "/dashboard/teacher/exams/data/subjects",
            };

            const getJSON = async (url) => {
                const res = await fetch(url, {
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                if (!res.ok) throw new Error("network");
                return res.json();
            };

            const opt = (items, placeholder = "-- انتخاب کنید --", labelKey = "name_fa") => {
                let html = `<option value="">${placeholder}</option>`;
                items.forEach(i => {
                    html += `<option value="${i.id}"
                                data-name="${i[labelKey] || i.title_fa || ''}">
                                ${i[labelKey] || i.title_fa}
                            </option>`;
                });
                return html;
            };

            // قدم اول: لود مقطع
            let sections = [];
            try {
                const s = await getJSON(endpoints.sections);
                sections = s.sections || [];
            } catch (e) {
                Swal.fire("خطا", "دریافت مقاطع ممکن نیست", "error");
                return;
            }

            Swal.fire({
                title: 'ایجاد کلاس جدید',
                html: `
                <div style="text-align:right">

                    <label class="mb-2 fw-bold d-block">🎓 مقطع</label>
                    <select id="cc_section" class="swal2-input">${opt(sections, "مقطع را انتخاب کنید")}</select>

                    <label class="mb-2 fw-bold d-block mt-3">📊 پایه</label>
                    <select id="cc_grade" class="swal2-input" disabled>
                        <option value="">ابتدا مقطع را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">🧩 شاخه / رشته</label>
                    <select id="cc_branch" class="swal2-input" disabled>
                        <option value="">ابتدا پایه را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">🏭 زمینه آموزشی</label>
                    <select id="cc_field" class="swal2-input" disabled>
                        <option value="">ابتدا شاخه را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">🔬 زیررشته</label>
                    <select id="cc_subfield" class="swal2-input" disabled>
                        <option value="">ابتدا زمینه را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">📚 دسته درسی</label>
                    <select id="cc_subject_type" class="swal2-input" disabled>
                        <option value="">ابتدا زیررشته را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">📖 درس</label>
                    <select id="cc_subject" class="swal2-input" disabled>
                        <option value="">ابتدا دسته درسی را انتخاب کنید</option>
                    </select>

                    <label class="mb-2 fw-bold d-block mt-3">🏷️ نام کلاس</label>
                    <input type="text" id="cc_title" class="swal2-input" placeholder="مثال: کلاس یازدهم شبکه - پایگاه داده">

                </div>
                `,
                showCancelButton: true,
                confirmButtonText: "ایجاد کلاس",
                cancelButtonText: "انصراف",
                reverseButtons: true,
                width: 650,
                didOpen: () => {

                    const elSection = document.getElementById("cc_section");
                    const elGrade = document.getElementById("cc_grade");
                    const elBranch = document.getElementById("cc_branch");
                    const elField = document.getElementById("cc_field");
                    const elSubfield = document.getElementById("cc_subfield");
                    const elSubjectType = document.getElementById("cc_subject_type");
                    const elSubject = document.getElementById("cc_subject");

                    const resetBelow = (from) => {
                        const map = {
                            section: [elGrade, elBranch, elField, elSubfield, elSubjectType, elSubject],
                            grade:   [elBranch, elField, elSubfield, elSubjectType, elSubject],
                            branch:  [elField, elSubfield, elSubjectType, elSubject],
                            field:   [elSubfield, elSubjectType, elSubject],
                            subfield:[elSubjectType, elSubject],
                            subjectType:[elSubject],
                        };
                        (map[from] || []).forEach(el => {
                            el.innerHTML = `<option value="">ابتدا مرحله قبل را انتخاب کنید</option>`;
                            el.disabled = true;
                        });
                    };

                    // ====== مقطع → پایه‌ها ======
                    elSection.addEventListener("change", async () => {
                        const sectionId = elSection.value;
                        resetBelow('section');

                        if (!sectionId) return;

                        elGrade.innerHTML = `<option>در حال بارگذاری...</option>`;
                        const g = await getJSON(`${endpoints.grades}?section_id=${sectionId}`);
                        elGrade.innerHTML = opt(g.grades || [], "پایه را انتخاب کنید");
                        elGrade.disabled = false;
                    });

                    // ====== پایه → شاخه‌ها ======
                    elGrade.addEventListener("change", async () => {
                        const sectionId = elSection.value;
                        const gradeId = elGrade.value;
                        resetBelow('grade');

                        if (!sectionId || !gradeId) return;

                        elBranch.innerHTML = `<option>در حال بارگذاری...</option>`;
                        const b = await getJSON(
                            `${endpoints.branches}?section_id=${sectionId}&grade_id=${gradeId}`
                        );
                        elBranch.innerHTML = opt(b.branches || [], "شاخه/رشته را انتخاب کنید");
                        elBranch.disabled = false;
                    });

                    // ====== شاخه → زمینه‌ها ======
                    elBranch.addEventListener("change", async () => {
                        const branchId = elBranch.value;
                        resetBelow('branch');

                        if (!branchId) return;

                        elField.innerHTML = `<option>در حال بارگذاری...</option>`;
                        const f = await getJSON(`${endpoints.fields}?branch_id=${branchId}`);
                        elField.innerHTML = opt(f.fields || [], "زمینه را انتخاب کنید");
                        elField.disabled = false;
                    });

                    // ====== زمینه → زیررشته ======
                    elField.addEventListener("change", async () => {
                        const fieldId = elField.value;
                        resetBelow('field');

                        if (!fieldId) return;

                        elSubfield.innerHTML = `<option>در حال بارگذاری...</option>`;
                        const sf = await getJSON(`${endpoints.subfields}?field_id=${fieldId}`);
                        elSubfield.innerHTML = opt(sf.subfields || [], "زیررشته را انتخاب کنید");
                        elSubfield.disabled = false;
                    });

                    // ====== زیررشته → دسته‌های درسی ======
                    elSubfield.addEventListener("change", async () => {
                        const sectionId = elSection.value;
                        const gradeId   = elGrade.value;
                        const branchId  = elBranch.value;
                        const fieldId   = elField.value;
                        const subfieldId= elSubfield.value;
                        resetBelow('subfield');

                        if (!subfieldId) return;

                        elSubjectType.innerHTML = `<option>در حال بارگذاری...</option>`;

                        const params = new URLSearchParams();
                        if (sectionId) params.append("section_id", sectionId);
                        if (gradeId) params.append("grade_id", gradeId);
                        if (branchId) params.append("branch_id", branchId);
                        if (fieldId) params.append("field_id", fieldId);
                        if (subfieldId) params.append("subfield_id", subfieldId);

                        const st = await getJSON(`${endpoints.subjectTypes}?${params.toString()}`);
                        elSubjectType.innerHTML = opt(st.subjectTypes || [], "دسته درسی را انتخاب کنید");
                        elSubjectType.disabled = false;
                    });

                    // ====== دسته درسی → دروس ======
                    elSubjectType.addEventListener("change", async () => {
                        const sectionId = elSection.value;
                        const gradeId   = elGrade.value;
                        const branchId  = elBranch.value;
                        const fieldId   = elField.value;
                        const subfieldId= elSubfield.value;
                        const subjectTypeId = elSubjectType.value;
                        resetBelow('subjectType');

                        if (!subjectTypeId) return;

                        elSubject.innerHTML = `<option>در حال بارگذاری...</option>`;

                        const params = new URLSearchParams();
                        if (sectionId) params.append("section_id", sectionId);
                        if (gradeId) params.append("grade_id", gradeId);
                        if (branchId) params.append("branch_id", branchId);
                        if (fieldId) params.append("field_id", fieldId);
                        if (subfieldId) params.append("subfield_id", subfieldId);
                        if (subjectTypeId) params.append("subject_type_id", subjectTypeId);

                        const sub = await getJSON(`${endpoints.subjects}?${params.toString()}`);
                        elSubject.innerHTML = opt(sub.subjects || [], "درس را انتخاب کنید", "title_fa");
                        elSubject.disabled = false;
                    });

                },
                preConfirm: () => {
                    const sectionId = document.getElementById("cc_section").value;
                    const gradeId = document.getElementById("cc_grade").value;
                    const branchId = document.getElementById("cc_branch").value;
                    const fieldId = document.getElementById("cc_field").value;
                    const subfieldId = document.getElementById("cc_subfield").value;
                    const subjectTypeId = document.getElementById("cc_subject_type").value;
                    const subjectId = document.getElementById("cc_subject").value;
                    const title = document.getElementById("cc_title").value.trim();

                    if (!sectionId || !gradeId || !branchId || !fieldId || !subfieldId || !subjectId) {
                        Swal.showValidationMessage("لطفاً همه موارد آموزشی را انتخاب کنید.");
                        return false;
                    }
                    if (!title) {
                        Swal.showValidationMessage("نام کلاس الزامی است.");
                        return false;
                    }

                    const getName = (selectId) => {
                        const el = document.getElementById(selectId);
                        return el.options[el.selectedIndex]?.dataset?.name || el.options[el.selectedIndex]?.text || "";
                    };

                    return {
                        section_id: sectionId,
                        grade_id: gradeId,
                        branch_id: branchId,
                        field_id: fieldId,
                        subfield_id: subfieldId,
                        subject_type_id: subjectTypeId || null,
                        subject_id: subjectId,

                        section_name: getName("cc_section"),
                        grade_name: getName("cc_grade"),
                        branch_name: getName("cc_branch"),
                        field_name: getName("cc_field"),
                        subfield_name: getName("cc_subfield"),
                        subject_type_name: getName("cc_subject_type"),
                        subject_name: getName("cc_subject"),

                        title
                    };
                }
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                const data = result.value;

                Swal.fire({
                    title: "در حال ایجاد کلاس...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const fd = new FormData();
                    fd.append("title", data.title);
                    fd.append("section_id", data.section_id);
                    fd.append("grade_id", data.grade_id);
                    fd.append("branch_id", data.branch_id);
                    fd.append("field_id", data.field_id);
                    fd.append("subfield_id", data.subfield_id);
                    fd.append("subject_id", data.subject_id);
                    if (data.subject_type_id) fd.append("subject_type_id", data.subject_type_id);
                    fd.append("is_active", 1);

                    fd.append("metadata", JSON.stringify({
                        section_name: data.section_name,
                        grade_name: data.grade_name,
                        branch_name: data.branch_name,
                        field_name: data.field_name,
                        subfield_name: data.subfield_name,
                        subject_type_name: data.subject_type_name,
                        subject_name: data.subject_name
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
                        Swal.fire("✅ موفقیت", "کلاس ایجاد شد", "success").then(() => {
                            loadExistingClassrooms();

                            if (responseData.classroom) {
                                setTimeout(() => {
                                    selectClassroom(
                                        { target: document.querySelector(`[data-classroom-id="${responseData.classroom.id}"]`) },
                                        responseData.classroom.id,
                                        responseData.classroom.title
                                    );
                                }, 300);
                            }
                        });
                    } else {
                        Swal.fire("❌ خطا", responseData.message || "خطا در ایجاد کلاس", "error");
                    }

                } catch (e) {
                    console.error(e);
                    Swal.close();
                    Swal.fire("❌ خطای شبکه", "ارتباط با سرور مشکل دارد.", "error");
                }
            });
        }
    </script>

@endpush
