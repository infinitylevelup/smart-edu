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
        // داده‌های دروس برای هر پایه و رشته
        const subjectsData = {
            elementary: {
                1: ['ریاضی اول', 'فارسی اول', 'علوم اول', 'قرآن اول'],
                2: ['ریاضی دوم', 'فارسی دوم', 'علوم دوم', 'قرآن دوم'],
                3: ['ریاضی سوم', 'فارسی سوم', 'علوم سوم', 'قرآن سوم', 'هدیه‌های آسمانی'],
                4: ['ریاضی چهارم', 'فارسی چهارم', 'علوم چهارم', 'قرآن چهارم', 'هدیه‌های آسمانی', 'اجتماعی چهارم'],
                5: ['ریاضی پنجم', 'فارسی پنجم', 'علوم پنجم', 'قرآن پنجم', 'هدیه‌های آسمانی', 'اجتماعی پنجم'],
                6: ['ریاضی ششم', 'فارسی ششم', 'علوم ششم', 'قرآن ششم', 'هدیه‌های آسمانی', 'اجتماعی ششم', 'تفکر و پژوهش']
            },
            middle_school: {
                7: ['ریاضی هفتم', 'فارسی هفتم', 'علوم هفتم', 'قرآن هفتم', 'عربی هفتم', 'انگلیسی هفتم',
                    'مطالعات اجتماعی', 'تفکر و سبک زندگی'
                ],
                8: ['ریاضی هشتم', 'فارسی هشتم', 'علوم هشتم', 'قرآن هشتم', 'عربی هشتم', 'انگلیسی هشتم',
                    'مطالعات اجتماعی', 'تفکر و سبک زندگی'
                ],
                9: ['ریاضی نهم', 'فارسی نهم', 'علوم نهم', 'قرآن نهم', 'عربی نهم', 'انگلیسی نهم', 'مطالعات اجتماعی',
                    'آمادگی دفاعی', 'کار و فناوری'
                ]
            },
            high_school: {
                math: {
                    10: ['ریاضی ۱', 'فیزیک ۱', 'شیمی ۱', 'ادبیات فارسی ۱', 'زبان انگلیسی ۱', 'عربی ۱', 'دین و زندگی ۱',
                        'سلامت و بهداشت'
                    ],
                    11: ['ریاضی ۲', 'فیزیک ۲', 'شیمی ۲', 'ادبیات فارسی ۲', 'زبان انگلیسی ۲', 'عربی ۲', 'دین و زندگی ۲',
                        'هندسه ۱'
                    ],
                    12: ['ریاضی ۳', 'فیزیک ۳', 'شیمی ۳', 'ادبیات فارسی ۳', 'زبان انگلیسی ۳', 'عربی ۳', 'دین و زندگی ۳',
                        'هندسه ۲'
                    ]
                },
                science: {
                    10: ['ریاضی ۱', 'فیزیک ۱', 'شیمی ۱', 'زیست‌شناسی ۱', 'ادبیات فارسی ۱', 'زبان انگلیسی ۱', 'عربی ۱',
                        'دین و زندگی ۱'
                    ],
                    11: ['ریاضی ۲', 'فیزیک ۲', 'شیمی ۲', 'زیست‌شناسی ۲', 'ادبیات فارسی ۲', 'زبان انگلیسی ۲', 'عربی ۲',
                        'دین و زندگی ۲'
                    ],
                    12: ['ریاضی ۳', 'فیزیک ۳', 'شیمی ۳', 'زیست‌شناسی ۳', 'ادبیات فارسی ۳', 'زبان انگلیسی ۳', 'عربی ۳',
                        'دین و زندگی ۳'
                    ]
                },
                humanities: {
                    10: ['ریاضی و آمار ۱', 'علوم و فنون ادبی ۱', 'تاریخ ۱', 'جغرافیا ۱', 'جامعه‌شناسی ۱',
                        'ادبیات فارسی ۱', 'زبان انگلیسی ۱', 'عربی ۱'
                    ],
                    11: ['ریاضی و آمار ۲', 'علوم و فنون ادبی ۲', 'تاریخ ۲', 'جغرافیا ۲', 'جامعه‌شناسی ۲',
                        'ادبیات فارسی ۲', 'زبان انگلیسی ۲', 'عربی ۲'
                    ],
                    12: ['ریاضی و آمار ۳', 'علوم و فنون ادبی ۳', 'تاریخ ۳', 'جغرافیا ۳', 'جامعه‌شناسی ۳',
                        'ادبیات فارسی ۳', 'زبان انگلیسی ۳', 'عربی ۳'
                    ]
                },
                technical: {
                    10: ['فیزیک ۱', 'شیمی ۱', 'ریاضی ۱', 'ادبیات فارسی ۱', 'زبان انگلیسی ۱', 'دین و زندگی ۱',
                        'درس فنی ۱'
                    ],
                    11: ['فیزیک ۲', 'شیمی ۲', 'ریاضی ۲', 'ادبیات فارسی ۲', 'زبان انگلیسی ۲', 'دین و زندگی ۲',
                        'درس فنی ۲'
                    ],
                    12: ['فیزیک ۳', 'شیمی ۳', 'ریاضی ۳', 'ادبیات فارسی ۳', 'زبان انگلیسی ۳', 'دین و زندگی ۳',
                        'درس فنی ۳'
                    ]
                },
                vocational: {
                    10: ['ریاضی ۱', 'فیزیک ۱', 'ادبیات فارسی ۱', 'زبان انگلیسی ۱', 'دین و زندگی ۱', 'درس مهارتی ۱'],
                    11: ['ریاضی ۲', 'فیزیک ۲', 'ادبیات فارسی ۲', 'زبان انگلیسی ۲', 'دین و زندگی ２', 'درس مهارتی ۲'],
                    12: ['ریاضی ３', 'فیزیک ３', 'ادبیات فارسی ３', 'زبان انگلیسی ３', 'دین و زندگی ３', 'درس مهارتی ３']
                }
            }
        };

        // داده‌های پیش‌فرض
        let currentStep = 1;
        let formData = {
            examType: 'public',
            educationLevel: '',
            grade: '',
            field: '',
            subjectType: 'single',
            subjectId: '',
            selectedSubjects: []
        };

        document.addEventListener('DOMContentLoaded', function() {
            // مقداردهی اولیه
            selectExamType('public');
            updateProgress();
            updateNavigationButtons();

            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.type-card, .level-tab, .grade-card, .field-card, .subject-card, .btn-nav');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // انیمیشن ورود
            setTimeout(() => {
                const steps = document.querySelectorAll('.step-item');
                steps.forEach((step, i) => {
                    step.style.animationDelay = `${i * 0.1}s`;
                    step.style.animation = 'fadeIn 0.5s ease-out forwards';
                    step.style.opacity = '0';
                });
            }, 300);
        });

        // ========== FUNCTIONS FOR EXAM TYPE ==========
        function selectExamType(type) {
            // حذف انتخاب قبلی
            document.querySelectorAll('.type-card').forEach(card => {
                card.classList.remove('selected');
            });

            // انتخاب جدید
            document.querySelector(`.type-card[data-type="${type}"]`).classList.add('selected');

            // ذخیره داده
            formData.examType = type;
            document.getElementById('examType').value = type;

            // نمایش تغییرات در پیش‌نمایش
            updatePreview();

            // بررسی نوع آزمون برای نمایش/مخفی کردن مراحل
            handleExamTypeChange();
        }

        function handleExamTypeChange() {
            const examType = formData.examType;

            if (examType === 'public') {
                // آزمون عمومی: مخفی کردن مراحل کلاس و رشته
                document.getElementById('step4').style.display = 'none';
                document.getElementById('step5').style.display = 'block';
            } else {
                // آزمون کلاسی: نمایش همه مراحل
                document.getElementById('step4').style.display = 'block';
                document.getElementById('step5').style.display = 'block';
            }
        }

        // ========== FUNCTIONS FOR EDUCATION LEVEL ==========
        function selectEducationLevel(level) {
            // حذف انتخاب قبلی
            document.querySelectorAll('.level-tab').forEach(tab => {
                tab.classList.remove('selected');
            });

            // انتخاب جدید
            document.querySelector(`.level-tab.${level.replace('_', '-')}`).classList.add('selected');

            // ذخیره داده
            formData.educationLevel = level;
            document.getElementById('educationLevel').value = level;

            // نمایش گرید پایه مناسب
            showGradeGrid(level);

            // ریست کردن پایه انتخابی
            formData.grade = '';
            document.getElementById('grade').value = '';
            document.querySelectorAll('.grade-card').forEach(card => {
                card.classList.remove('selected');
            });

            // نمایش تغییرات در پیش‌نمایش
            updatePreview();

            // بررسی نمایش مرحله رشته
            if (level === 'high_school') {
                document.getElementById('step4').style.display = 'block';
            } else {
                formData.field = '';
                document.getElementById('field').value = '';
                document.querySelectorAll('.field-card').forEach(card => {
                    card.classList.remove('selected');
                });
            }
        }

        function showGradeGrid(level) {
            // مخفی کردن همه گریدها
            document.getElementById('elementaryGrades').style.display = 'none';
            document.getElementById('middleSchoolGrades').style.display = 'none';
            document.getElementById('highSchoolGrades').style.display = 'none';

            // نمایش گرید مناسب
            switch (level) {
                case 'elementary':
                    document.getElementById('elementaryGrades').style.display = 'grid';
                    document.getElementById('gradeDescription').textContent = 'پایه ابتدایی مورد نظر را انتخاب کنید.';
                    break;
                case 'middle_school':
                    document.getElementById('middleSchoolGrades').style.display = 'grid';
                    document.getElementById('gradeDescription').textContent = 'پایه متوسطه اول مورد نظر را انتخاب کنید.';
                    break;
                case 'high_school':
                    document.getElementById('highSchoolGrades').style.display = 'grid';
                    document.getElementById('gradeDescription').textContent = 'پایه متوسطه دوم مورد نظر را انتخاب کنید.';
                    break;
            }
        }

        // ========== FUNCTIONS FOR GRADE SELECTION ==========
        function selectGrade(grade) {
            // حذف انتخاب قبلی
            document.querySelectorAll('.grade-card').forEach(card => {
                card.classList.remove('selected');
            });

            // انتخاب جدید
            document.querySelectorAll('.grade-card').forEach(card => {
                if (card.textContent.trim() === `پایه ${grade}`) {
                    card.classList.add('selected');
                }
            });

            // ذخیره داده
            formData.grade = grade;
            document.getElementById('grade').value = grade;

            // نمایش تغییرات در پیش‌نمایش
            updatePreview();

            // بارگذاری دروس برای این پایه
            loadSubjectsForGrade();
        }

        // ========== FUNCTIONS FOR FIELD SELECTION ==========
        function selectField(field) {
            // حذف انتخاب قبلی
            document.querySelectorAll('.field-card').forEach(card => {
                card.classList.remove('selected');
            });

            // انتخاب جدید
            document.querySelector(`.field-card[data-field="${field}"]`).classList.add('selected');

            // ذخیره داده
            formData.field = field;
            document.getElementById('field').value = field;

            // نمایش تغییرات در پیش‌نمایش
            updatePreview();

            // بارگذاری دروس برای این رشته
            loadSubjectsForGrade();
        }

        // ========== FUNCTIONS FOR SUBJECTS ==========
        function loadSubjectsForGrade() {
            if (!formData.educationLevel || !formData.grade) return;

            let subjects = [];

            if (formData.educationLevel === 'high_school' && formData.field) {
                // متوسطه دوم با رشته
                subjects = subjectsData.high_school[formData.field][formData.grade] || [];
            } else if (formData.educationLevel === 'middle_school') {
                // متوسطه اول
                subjects = subjectsData.middle_school[formData.grade] || [];
            } else if (formData.educationLevel === 'elementary') {
                // ابتدایی
                subjects = subjectsData.elementary[formData.grade] || [];
            }

            // نمایش دروس
            renderSubjects(subjects);
        }

        function renderSubjects(subjects) {
            const singleSubjectGrid = document.getElementById('singleSubjectGrid');
            const comprehensiveSubjectList = document.getElementById('comprehensiveSubjectList');

            // پاک کردن محتوای قبلی
            singleSubjectGrid.innerHTML = '';
            comprehensiveSubjectList.innerHTML = '';

            // ایجاد کارت برای هر درس
            subjects.forEach((subject, index) => {
                // کارت برای انتخاب تک درس
                const subjectCard = document.createElement('div');
                subjectCard.className = 'subject-card';
                subjectCard.setAttribute('data-subject-id', index);
                subjectCard.setAttribute('onclick', `selectSingleSubject(${index}, '${subject}')`);
                subjectCard.innerHTML = `
            <div class="subject-name">${subject}</div>
            <div class="subject-code">${generateSubjectCode(subject)}</div>
            <div class="subject-hours">۲ ساعت</div>
        `;
                singleSubjectGrid.appendChild(subjectCard);

                // تگ برای لیست جامع
                const subjectTag = document.createElement('div');
                subjectTag.className = 'subject-tag';
                subjectTag.innerHTML = `
            <i class="fas fa-book"></i>
            ${subject}
        `;
                comprehensiveSubjectList.appendChild(subjectTag);
            });
        }

        function generateSubjectCode(subjectName) {
            // تولید کد ساده برای درس
            const words = subjectName.split(' ');
            if (words.length > 1) {
                return words.map(word => word[0]).join('').toUpperCase();
            }
            return subjectName.substring(0, 3).toUpperCase();
        }

        function selectSubjectType(type) {
            formData.subjectType = type;
            document.getElementById('subjectType').value = type;

            // نمایش المان مناسب
            if (type === 'single') {
                document.getElementById('singleSubjectGrid').style.display = 'grid';
                document.getElementById('comprehensiveSubjects').style.display = 'none';
            } else {
                document.getElementById('singleSubjectGrid').style.display = 'none';
                document.getElementById('comprehensiveSubjects').style.display = 'block';
            }

            // به‌روزرسانی پیش‌نمایش
            updatePreview();
        }

        function selectSingleSubject(id, subjectName) {
            // حذف انتخاب قبلی
            document.querySelectorAll('.subject-card').forEach(card => {
                card.classList.remove('selected');
            });

            // انتخاب جدید
            document.querySelector(`.subject-card[data-subject-id="${id}"]`).classList.add('selected');

            // ذخیره داده
            formData.subjectId = id;
            formData.selectedSubjects = [subjectName];
            document.getElementById('subjectId').value = id;

            // به‌روزرسانی پیش‌نمایش
            updatePreview();
        }

        // ========== STEP NAVIGATION ==========
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < 6) {
                    // رفتن به مرحله بعد
                    document.getElementById(`step${currentStep}`).classList.remove('active');
                    currentStep++;
                    document.getElementById(`step${currentStep}`).classList.add('active');

                    // به‌روزرسانی پیشرفت
                    updateProgress();
                    updateNavigationButtons();

                    // اسکرول به بالا
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // ویبره
                    if (navigator.vibrate) navigator.vibrate(30);
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                // رفتن به مرحله قبل
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');

                // به‌روزرسانی پیشرفت
                updateProgress();
                updateNavigationButtons();

                // اسکرول به بالا
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // ویبره
                if (navigator.vibrate) navigator.vibrate(20);
            }
        }

        function updateProgress() {
            // به‌روزرسانی progress bar
            const progress = (currentStep / 6) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;

            // به‌روزرسانی مراحل
            document.querySelectorAll('.step-item').forEach((item, index) => {
                item.classList.remove('active', 'completed');
                if (index + 1 < currentStep) {
                    item.classList.add('completed');
                } else if (index + 1 === currentStep) {
                    item.classList.add('active');
                }
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
            } else if (currentStep === 6) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'flex';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
                submitBtn.style.display = 'none';
            }
        }

        function validateCurrentStep() {
            switch (currentStep) {
                case 1:
                    if (!formData.examType) {
                        showToast('لطفاً نوع آزمون را انتخاب کنید.', 'error');
                        return false;
                    }
                    break;
                case 2:
                    if (!formData.educationLevel) {
                        showToast('لطفاً سطح آموزشی را انتخاب کنید.', 'error');
                        return false;
                    }
                    break;
                case 3:
                    if (!formData.grade) {
                        showToast('لطفاً پایه تحصیلی را انتخاب کنید.', 'error');
                        return false;
                    }

                    // اگر متوسطه دوم است، بررسی می‌کنیم که مرحله رشته را باید نشان دهیم
                    if (formData.educationLevel === 'high_school') {
                        document.getElementById('step4').style.display = 'block';
                    }
                    break;
                case 4:
                    if (formData.educationLevel === 'high_school' && !formData.field) {
                        showToast('لطفاً رشته تحصیلی را انتخاب کنید.', 'error');
                        return false;
                    }
                    break;
                case 5:
                    if (formData.examType === 'class_single' && !formData.subjectId) {
                        showToast('لطفاً درس مورد نظر را انتخاب کنید.', 'error');
                        return false;
                    }
                    break;
            }
            return true;
        }

        // ========== PREVIEW UPDATES ==========
        function updatePreview() {
            // نوع آزمون
            const examTypeMap = {
                'public': 'آزمون عمومی',
                'class_single': 'کلاسی تک درس',
                'class_comprehensive': 'کلاسی جامع'
            };
            document.getElementById('previewExamType').textContent = examTypeMap[formData.examType] || '--';

            // سطح آموزشی
            const levelMap = {
                'elementary': 'ابتدایی',
                'middle_school': 'متوسطه اول',
                'high_school': 'متوسطه دوم'
            };
            document.getElementById('previewEducationLevel').textContent = levelMap[formData.educationLevel] || '--';

            // پایه
            document.getElementById('previewGrade').textContent = formData.grade ? `پایه ${formData.grade}` : '--';

            // رشته
            const fieldMap = {
                'math': 'ریاضی و فیزیک',
                'science': 'علوم تجربی',
                'humanities': 'علوم انسانی',
                'technical': 'فنی و حرفه‌ای',
                'vocational': 'کاردانش'
            };
            document.getElementById('previewField').textContent = fieldMap[formData.field] || '--';

            // دروس
            if (formData.examType === 'class_comprehensive') {
                document.getElementById('previewSubjects').textContent = 'تمام دروس پایه';
            } else if (formData.selectedSubjects.length > 0) {
                document.getElementById('previewSubjects').textContent = formData.selectedSubjects[0];
            } else {
                document.getElementById('previewSubjects').textContent = '--';
            }
        }

        // ========== TOAST NOTIFICATION ==========
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: ${type === 'error' ? 'var(--warning)' : 'var(--success)'};
        color: white;
        padding: 15px 20px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        animation: slideInLeft 0.3s ease;
        max-width: 350px;
    `;

            toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}"></i>
        <span>${message}</span>
    `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);

            // ویبره برای خطا
            if (type === 'error' && navigator.vibrate) {
                navigator.vibrate([200, 100, 200]);
            }
        }

        // اضافه کردن استایل‌های انیمیشن
        const style = document.createElement('style');
        style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-30px);
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
