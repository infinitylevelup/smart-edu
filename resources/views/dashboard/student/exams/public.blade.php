@extends('layouts.app')
@section('title', 'آزمون‌های عمومی - SmartEdu')

@push('styles')
    <style>
        /* تم رنگ هماهنگ با SmartEdu */
        :root {
            --primary: #7B68EE;
            --primary-light: rgba(123, 104, 238, 0.1);
            --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --secondary: #FF6B9D;
            --secondary-light: rgba(255, 107, 157, 0.1);
            --accent: #00D4AA;
            --accent-light: rgba(0, 212, 170, 0.1);
            --gold: #FFD166;
            --light: #ffffff;
            --dark: #2D3047;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .public-exams-page {
            animation: fadeIn 0.6s ease both;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px 100px;
        }

        /* انیمیشن‌ها */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
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

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* المان‌های شناور تزئینی */
        .floating-element {
            position: fixed;
            pointer-events: none;
            z-index: -1;
            opacity: 0.3;
        }

        /* هدر */
        .page-header {
            background: var(--light);
            padding: 20px;
            box-shadow: var(--shadow-md);
            border-radius: var(--radius-xl);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            animation: bounce 2.5s infinite;
        }

        .logo-text h1 {
            font-size: 1.4rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .logo-text span {
            font-size: 0.85rem;
            color: var(--gray);
            display: block;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            position: relative;
            transition: all 0.3s;
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .notification-btn:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            left: -2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--secondary);
            color: white;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            animation: pulse 2s infinite;
            border: 2px solid white;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--gradient-2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: var(--shadow-sm);
            border: 3px solid white;
        }

        /* خوشامدگویی */
        .welcome-section {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.6s ease-out;
            border: 2px solid transparent;
        }

        .welcome-section:hover {
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            transition: all 0.3s;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .welcome-subtitle {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .progress-summary {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), rgba(255, 107, 157, 0.05));
            padding: 15px;
            border-radius: var(--radius-lg);
            border: 2px solid rgba(123, 104, 238, 0.1);
        }

        .progress-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .progress-text {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .welcome-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 4rem;
            opacity: 0.1;
            color: var(--primary);
            animation: float 4s ease-in-out infinite;
        }

        /* انتخاب نوع آزمون */
        .exam-type-selector {
            margin-bottom: 30px;
            animation: fadeIn 0.7s ease-out;
        }

        .selector-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .type-cards {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        @media (min-width: 768px) {
            .type-cards {
                flex-direction: row;
            }
        }

        .type-card {
            flex: 1;
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            animation: scaleIn 0.5s ease-out;
        }

        .type-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .type-card.selected {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .type-card.public.selected {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), rgba(255, 255, 255, 0.95));
        }

        .type-card.class.selected {
            border-color: var(--secondary);
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.05), rgba(255, 255, 255, 0.95));
        }

        .type-card.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .floating-dots {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 5px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: bounce 2s infinite;
        }

        .type-card.public .dot {
            background: var(--primary);
        }

        .type-card.class .dot {
            background: var(--secondary);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 2rem;
            transition: all 0.3s;
        }

        .type-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .type-card.public .card-icon {
            background: var(--gradient-1);
            box-shadow: 0 6px 20px rgba(123, 104, 238, 0.3);
        }

        .type-card.class .card-icon {
            background: var(--gradient-2);
            box-shadow: 0 6px 20px rgba(0, 212, 170, 0.3);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 10px;
        }

        .type-card.public .card-title {
            color: var(--primary);
        }

        .type-card.class .card-title {
            color: var(--accent);
        }

        .card-description {
            color: var(--gray);
            text-align: center;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .card-status {
            text-align: center;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 8px 16px;
            border-radius: 50px;
            display: inline-block;
            margin: 0 auto;
            transition: all 0.3s;
        }

        .type-card.public .card-status {
            background: var(--primary-light);
            color: var(--primary);
            border: 2px solid rgba(123, 104, 238, 0.2);
        }

        .type-card.class .card-status {
            background: var(--accent-light);
            color: var(--accent);
            border: 2px solid rgba(0, 212, 170, 0.2);
        }

        /* لیست آزمون‌ها */
        .exams-section {
            animation: fadeIn 0.8s ease-out;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .exams-count {
            background: var(--light-gray);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: 800;
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .exams-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* کارت آزمون */
        .exam-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 22px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.6s ease-out;
            animation-fill-mode: both;
            border: 2px solid transparent;
        }

        .exam-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .exam-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .exam-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        /* نوار رنگی بالای کارت */
        .exam-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-1);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        .exam-card.completed::before {
            background: var(--gradient-2);
        }

        .exam-card.active {
            border-right: 4px solid var(--secondary);
        }

        .exam-card.upcoming {
            border-right: 4px solid var(--accent);
        }

        /* نوار پیشرفت */
        .progress-bar-container {
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
        }

        .progress-percent {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--primary);
        }

        .progress-bar {
            height: 10px;
            background: var(--light-gray);
            border-radius: 50px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .progress-fill {
            height: 100%;
            border-radius: 50px;
            background: var(--gradient-1);
            width: 0;
            transition: width 1.5s ease-out;
            position: relative;
        }

        .progress-fill::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 30px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 2s infinite;
        }

        /* هدر کارت */
        .exam-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
        }

        .exam-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--dark);
            flex: 1;
            line-height: 1.5;
        }

        .exam-type-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 800;
            background: var(--primary-light);
            color: var(--primary);
            border: 2px solid rgba(123, 104, 238, 0.2);
            white-space: nowrap;
        }

        .badge-class {
            background: var(--accent-light);
            color: var(--accent);
            border-color: rgba(0, 212, 170, 0.2);
        }

        /* رتبه‌بندی */
        .rating {
            display: flex;
            gap: 3px;
            margin: 8px 0 12px;
            position: relative;
            z-index: 2;
        }

        .star {
            color: var(--gold);
            font-size: 1rem;
        }

        /* متاداده‌ها */
        .exam-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--gray);
            font-size: 0.85rem;
            font-weight: 700;
            padding: 6px 12px;
            background: var(--light-gray);
            border-radius: 50px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* فوتر کارت */
        .exam-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px dashed rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 2;
        }

        .exam-status {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 800;
        }

        .status-active {
            color: var(--secondary);
        }

        .status-upcoming {
            color: var(--accent);
        }

        .status-completed {
            color: var(--gray);
        }

        .status-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            background: rgba(123, 104, 238, 0.1);
            animation: pulse 2s infinite;
        }

        .status-active .status-icon {
            background: rgba(255, 107, 157, 0.1);
        }

        .status-upcoming .status-icon {
            background: rgba(0, 212, 170, 0.1);
        }

        /* دکمه‌های عمل */
        .action-btn {
            padding: 12px 20px;
            border-radius: var(--radius-md);
            border: none;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            min-height: 44px;
            position: relative;
            overflow: hidden;
        }

        .action-btn:active {
            transform: scale(0.96);
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .action-btn:hover::before {
            right: 100%;
        }

        .btn-primary {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 20px rgba(123, 104, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .btn-secondary:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.15);
        }

        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        /* راهنمای هوشمند */
        .smart-guide {
            margin-top: 30px;
            animation: fadeIn 0.6s ease-out;
        }

        .guide-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-md);
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px dashed var(--primary-light);
        }

        .guide-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
            animation: float 4s ease-in-out infinite;
        }

        .guide-title {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .guide-text {
            color: var(--gray);
            line-height: 1.7;
            margin-bottom: 25px;
            max-width: 500px;
            margin-inline: auto;
            font-size: 1rem;
        }

        .guide-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* وضعیت خالی */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: var(--light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            animation: slideInRight 0.6s ease-out;
            border: 2px dashed var(--primary-light);
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: float 5s ease-in-out infinite;
        }

        .empty-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .empty-text {
            color: var(--gray);
            line-height: 1.7;
            max-width: 450px;
            margin: 0 auto 30px;
            font-size: 1rem;
        }

        /* بهینه‌سازی موبایل */
        @media (max-width: 768px) {
            .public-exams-page {
                padding: 15px 10px 90px;
            }

            .page-header {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .logo {
                justify-content: center;
            }

            .user-menu {
                justify-content: center;
            }

            .welcome-section {
                padding: 20px;
                text-align: center;
            }

            .welcome-icon {
                display: none;
            }

            .progress-summary {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .exam-header {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .exam-type-badge {
                align-self: flex-start;
            }

            .exam-footer {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .action-btn {
                width: 100%;
            }

            .guide-actions {
                flex-direction: column;
            }

            .empty-state {
                padding: 40px 20px;
            }
        }

        /* جلوگیری از اسکرول افقی */
        .public-exams-page {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    @php
        use Illuminate\Support\Facades\Route;

        // route name safety
        $startExamRouteName = Route::has('student.exams.start')
            ? 'student.exams.start'
            : (Route::has('student.exams.begin')
                ? 'student.exams.begin'
                : null);

        $attemptResultRouteName = Route::has('student.attempts.result')
            ? 'student.attempts.result'
            : (Route::has('student.exams.result')
                ? 'student.exams.result'
                : null);

        $joinClassRoute = Route::has('student.join-class')
            ? route('student.join-class')
            : (Route::has('student.join-class.form')
                ? route('student.join-class.form')
                : (Route::has('student.join')
                    ? route('student.join')
                    : '#'));

        // ===== لیست عمومی
        $examsCol =
            $exams instanceof \Illuminate\Pagination\LengthAwarePaginator ? collect($exams->items()) : collect($exams);

        $studentId = auth()->id();

        $doneMap = $examsCol->map(function ($exam) use ($studentId) {
            if (!is_object($exam)) {
                return ['exam' => $exam, 'done' => false, 'lastAttemptId' => null, 'lastAttempt' => null];
            }

            $lastAttempt = $exam->attempts()->where('student_id', $studentId)->latest()->first();

            $isFinal = $lastAttempt && method_exists($lastAttempt, 'isFinal') && $lastAttempt->isFinal();

            return [
                'exam' => $exam,
                'done' => $isFinal,
                'lastAttemptId' => $lastAttempt?->id,
                'lastAttempt' => $lastAttempt,
            ];
        });

        $doneCount = $doneMap->where('done', true)->count();
        $totalCount = $examsCol->count();
        $remainingCount = max(0, $totalCount - $doneCount);
        $donePercent = $totalCount ? round(($doneCount / $totalCount) * 100) : 0;

        // وضعیت کلاس
        $userHasClass = $userHasClass ?? false;
        $classHasExams = $classHasExams ?? true;

        $classExamsCol = isset($classExams)
            ? ($classExams instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? collect($classExams->items())
                : collect($classExams))
            : collect();

        $classTotalCount = $classExamsCol->count();

        $student = auth()->user();
        $studentName = $student->name ?? 'دانش‌آموز عزیز';
        $studentInitial = mb_substr(trim($studentName), 0, 1) ?: 'د';
    @endphp

    <div class="public-exams-page">
        <!-- المان‌های شناور تزئینی -->
        <div class="floating-element"
            style="top:10%;right:5%;width:60px;height:60px;background:var(--primary-light);border-radius:50%;animation:float 8s infinite ease-in-out;">
        </div>
        <div class="floating-element"
            style="bottom:20%;left:10%;width:80px;height:80px;background:var(--secondary-light);border-radius:30% 70% 70% 30% / 30% 30% 70% 70%;animation:float 10s infinite ease-in-out reverse;">
        </div>

        <main class="container">
            <!-- خوشامدگویی -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <h2 class="welcome-title">سلام {{ $studentName }}! آماده چالش جدیدی؟</h2>
                    <p class="welcome-subtitle">
                        اینجا می‌تونی در آزمون‌های عمومی شرکت کنی و مهارت‌هات رو محک بزنی.
                        یادگیری با چالش‌های جذاب، همیشه موندگارتره! 🚀
                    </p>

                    <div class="progress-summary">
                        <div class="progress-number">{{ $donePercent }}%</div>
                        <div class="progress-text">
                            تا الان <strong>{{ $doneCount }} آزمون</strong> رو دادی و
                            <strong>{{ $remainingCount }} آزمون</strong> دیگه مونده.
                            ادامه بده تا به ۱۰۰٪ برسی! 🔥
                        </div>
                    </div>
                </div>
                <div class="welcome-icon"><i class="fas fa-rocket"></i></div>
            </section>

            <!-- انتخاب نوع -->
            <section class="exam-type-selector">
                <h3 class="selector-title">
                    <i class="fas fa-layer-group" style="color: var(--primary);"></i>
                    چه نوع آزمونی می‌خوای بدی؟
                </h3>
                <div class="type-cards">
                    <div class="type-card public selected" id="publicCard">
                        <div class="floating-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                        <div class="card-icon"><i class="fas fa-globe-americas"></i></div>
                        <h4 class="card-title">آزمون عمومی</h4>
                        <p class="card-description">برای همه دانش‌آموزان • آزاد • رایگان • همیشه در دسترس</p>
                        <div class="card-status">{{ $totalCount }} آزمون فعال</div>
                    </div>

                    <div class="type-card class {{ !$userHasClass ? 'disabled' : '' }}" id="classCard">
                        <div class="floating-dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                        <div class="card-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        <h4 class="card-title">آزمون کلاسی</h4>
                        <p class="card-description">مخصوص کلاس شما • زمان‌بندی شده • با معلم اختصاصی</p>
                        <div class="card-status" id="classStatus">
                            @if (!$userHasClass)
                                نیاز به پیوستن
                            @elseif(!$classHasExams)
                                بدون آزمون
                            @else
                                {{ $classTotalCount }} آزمون فعال
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- آزمون‌های عمومی -->
            <section class="exams-section" id="publicExams">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-globe-americas" style="color: var(--primary);"></i>
                        آزمون‌های عمومی
                    </h3>
                    <span class="exams-count">{{ $totalCount }} آزمون</span>
                </div>

                @if ($totalCount === 0)
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                        <h4 class="empty-title">فعلاً آزمون عمومی منتشر نشده است</h4>
                        <p class="empty-text">
                            به زودی آزمون‌های جذاب و چالش‌برانگیزی برای شما آماده می‌شه.
                            می‌تونی در همین صفحه منتظر بمونی یا از معلمت بخوای آزمون کلاسی برات تنظیم کنه.
                        </p>
                        <button class="action-btn btn-primary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i>
                            بروزرسانی
                        </button>
                    </div>
                @else
                    <div class="exams-container">
                        @foreach ($doneMap as $index => $row)
                            @php
                                $exam = $row['exam'];
                                if (!is_object($exam)) {
                                    continue;
                                }

                                $isFinalAttempt = (bool) $row['done'];
                                $lastAttemptId = $row['lastAttemptId'];
                                $lastAttempt = $row['lastAttempt'];

                                $levelText = match ($exam->level) {
                                    'easy' => 'آسان',
                                    'hard' => 'سخت',
                                    'tough' => 'خیلی سخت',
                                    default => 'متوسط',
                                };

                                $rating = (float) ($exam->rating ?? 0);
                                $participantsCount = $exam->participants_count ?? null;
                                $progress = $exam->progress ?? ($isFinalAttempt ? 100 : 0);
                            @endphp

                            <div
                                class="exam-card {{ $isFinalAttempt ? 'completed' : ($index === 0 ? 'active' : 'upcoming') }}">
                                @if (!$isFinalAttempt && $progress > 0)
                                    <div class="progress-bar-container">
                                        <div class="progress-info">
                                            <span class="progress-label">پیشرفت شما</span>
                                            <span class="progress-percent">{{ $progress }}٪</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="exam-header">
                                    <h4 class="exam-title">{{ $exam->title }}</h4>
                                    <span class="exam-type-badge">عمومی</span>
                                </div>

                                @if ($rating > 0 && !$isFinalAttempt)
                                    <div class="rating">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < floor($rating))
                                                <i class="fas fa-star star"></i>
                                            @elseif($i < ceil($rating) && $rating - floor($rating) >= 0.5)
                                                <i class="fas fa-star-half-alt star"></i>
                                            @else
                                                <i class="far fa-star star"></i>
                                            @endif
                                        @endfor
                                        <span
                                            style="color: var(--gray); font-size: 0.85rem; margin-right: 5px;">({{ number_format($rating, 1) }})</span>
                                    </div>
                                @endif

                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $exam->duration }} دقیقه</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $participantsCount ?? '۰' }} شرکت‌کننده</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span>سطح: {{ $levelText }}</span>
                                    </div>
                                </div>

                                <div class="exam-footer">
                                    @if ($isFinalAttempt)
                                        <div class="exam-status status-completed">
                                            <div class="status-icon"><i class="fas fa-check"></i></div>
                                            <span>نمره:
                                                {{ $lastAttempt?->score ? $lastAttempt->score . '/۲۰' : '--' }}</span>
                                        </div>

                                        @if ($attemptResultRouteName && $lastAttemptId)
                                            <a class="action-btn btn-secondary"
                                                href="{{ route($attemptResultRouteName, $lastAttemptId) }}">
                                                <i class="fas fa-chart-line"></i>
                                                مشاهده کارنامه
                                            </a>
                                        @else
                                            <button class="action-btn btn-secondary btn-disabled" type="button">
                                                <i class="fas fa-chart-line"></i>
                                                مشاهده کارنامه
                                            </button>
                                        @endif
                                    @else
                                        <div class="exam-status {{ $index === 0 ? 'status-active' : 'status-upcoming' }}">
                                            <div class="status-icon">
                                                @if ($index === 0)
                                                    <i class="fas fa-fire"></i>
                                                @else
                                                    <i class="far fa-clock"></i>
                                                @endif
                                            </div>
                                            <span>
                                                @if ($index === 0)
                                                    در حال انجام
                                                @else
                                                    آماده شروع
                                                @endif
                                            </span>
                                        </div>

                                        @if ($startExamRouteName)
                                            <form method="POST" action="{{ route($startExamRouteName, $exam->id) }}">
                                                @csrf
                                                <button class="action-btn btn-primary" type="submit">
                                                    <i class="fas fa-play-circle"></i>
                                                    {{ $index === 0 ? 'ادامه آزمون' : 'شروع آزمون' }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="action-btn btn-primary btn-disabled" type="button">
                                                <i class="fas fa-play-circle"></i>
                                                شروع آزمون
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- آزمون‌های کلاسی -->
            <section class="exams-section" id="classExams" style="display:none;">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-chalkboard-teacher" style="color: var(--accent);"></i>
                        آزمون‌های کلاسی
                    </h3>
                    <span class="exams-count">{{ $classTotalCount }} آزمون</span>
                </div>

                @if ($classTotalCount === 0)
                    <div class="empty-state" id="emptyClassState">
                        <div class="empty-icon"><i class="fas fa-chalkboard"></i></div>
                        <h4 class="empty-title">هنوز آزمون کلاسی ندارید!</h4>
                        <p class="empty-text">
                            استاد شما هنوز آزمونی برای کلاس قرار نداده.
                            می‌تونی از آزمون‌های عمومی استفاده کنی یا از استادت بخوای آزمون جدیدی ایجاد کنه.
                        </p>
                        <button class="action-btn btn-primary" id="viewPublicExamsBtn">
                            <i class="fas fa-arrow-left"></i>
                            مشاهده آزمون‌های عمومی
                        </button>
                    </div>
                @else
                    <div class="exams-container">
                        @foreach ($classExamsCol as $index => $exam)
                            @php
                                $lastAttempt = $exam->attempts()->where('student_id', $studentId)->latest()->first();
                                $isFinalAttempt =
                                    $lastAttempt && method_exists($lastAttempt, 'isFinal') && $lastAttempt->isFinal();
                                $lastAttemptId = $lastAttempt?->id;
                            @endphp

                            <div
                                class="exam-card {{ $isFinalAttempt ? 'completed' : ($index === 0 ? 'active' : 'upcoming') }}">
                                <div class="exam-header">
                                    <h4 class="exam-title">{{ $exam->title }}</h4>
                                    <span class="exam-type-badge badge-class">کلاسی</span>
                                </div>

                                <div class="exam-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-school"></i>
                                        <span>کلاس: {{ $exam->classroom->name ?? '—' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $exam->duration }} دقیقه</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-user-graduate"></i>
                                        <span>استاد: {{ $exam->teacher->name ?? '—' }}</span>
                                    </div>
                                </div>

                                <div class="exam-footer">
                                    @if ($isFinalAttempt)
                                        <div class="exam-status status-completed">
                                            <div class="status-icon"><i class="fas fa-check"></i></div>
                                            <span>نمره:
                                                {{ $lastAttempt?->score ? $lastAttempt->score . '/۲۰' : '--' }}</span>
                                        </div>

                                        @if ($attemptResultRouteName && $lastAttemptId)
                                            <a class="action-btn btn-secondary"
                                                href="{{ route($attemptResultRouteName, $lastAttemptId) }}">
                                                <i class="fas fa-chart-line"></i>
                                                مشاهده کارنامه
                                            </a>
                                        @else
                                            <button class="action-btn btn-secondary btn-disabled" type="button">
                                                مشاهده کارنامه
                                            </button>
                                        @endif
                                    @else
                                        <div class="exam-status status-upcoming">
                                            <div class="status-icon"><i class="far fa-clock"></i></div>
                                            <span>آماده شروع</span>
                                        </div>

                                        @if ($startExamRouteName)
                                            <form method="POST" action="{{ route($startExamRouteName, $exam->id) }}">
                                                @csrf
                                                <button class="action-btn btn-primary" type="submit">
                                                    <i class="fas fa-play-circle"></i>
                                                    شروع آزمون
                                                </button>
                                            </form>
                                        @else
                                            <button class="action-btn btn-primary btn-disabled" type="button">
                                                شروع آزمون
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- راهنمای پیوستن -->
            <section class="smart-guide" id="smartGuide" style="display:none;">
                <div class="guide-card">
                    <div class="guide-icon"><i class="fas fa-user-graduate"></i></div>
                    <h4 class="guide-title">به کلاس متصل نیستید؟</h4>
                    <p class="guide-text">
                        برای شرکت در آزمون‌های کلاسی باید به کلاس درسی خود متصل شوید.
                        کد کلاس رو از معلمت بگیر و اینجا وارد کن تا به دنیای یادگیری اختصاصی وارد بشی!
                    </p>
                    <div class="guide-actions">
                        @if ($joinClassRoute && $joinClassRoute !== '#')
                            <a href="{{ $joinClassRoute }}" class="action-btn btn-primary" id="joinClassBtn">
                                <i class="fas fa-plus-circle"></i>
                                پیوستن به کلاس
                            </a>
                        @else
                            <button class="action-btn btn-primary btn-disabled">
                                <i class="fas fa-plus-circle"></i>
                                پیوستن به کلاس (به زودی)
                            </button>
                        @endif
                        <button class="action-btn btn-secondary" id="continuePublicBtn">
                            <i class="fas fa-arrow-right"></i>
                            ادامه با آزمون عمومی
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // لرزش موبایل برای تعاملات
                if (navigator.vibrate) {
                    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate ||
                        navigator.msVibrate;
                }

                // انیمیشن نوار پیشرفت
                document.querySelectorAll('.progress-fill').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 300);
                });

                // متغیرهای اصلی
                const publicCard = document.getElementById('publicCard');
                const classCard = document.getElementById('classCard');
                const publicExams = document.getElementById('publicExams');
                const classExams = document.getElementById('classExams');
                const smartGuide = document.getElementById('smartGuide');
                const emptyClassState = document.getElementById('emptyClassState');
                const viewPublicExamsBtn = document.getElementById('viewPublicExamsBtn');
                const joinClassBtn = document.getElementById('joinClassBtn');
                const continuePublicBtn = document.getElementById('continuePublicBtn');

                const userHasClass = @json($userHasClass);
                const classHasExams = @json($classHasExams);

                let currentExamType = 'public';

                // --- تابع‌های کمکی ---
                function hideAllSections() {
                    publicExams.style.display = 'none';
                    classExams.style.display = 'none';
                    smartGuide.style.display = 'none';
                    if (emptyClassState) emptyClassState.style.display = 'none';
                }

                function selectExamType(type) {
                    // آپدیت ظاهری کارت‌ها
                    publicCard.classList.remove('selected');
                    classCard.classList.remove('selected');

                    hideAllSections();

                    if (type === 'public') {
                        publicCard.classList.add('selected');
                        publicExams.style.display = 'block';
                        currentExamType = 'public';

                        // لرزش موبایل
                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    } else {
                        classCard.classList.add('selected');
                        currentExamType = 'class';

                        if (!userHasClass) {
                            smartGuide.style.display = 'block';
                            smartGuide.scrollIntoView({
                                behavior: 'smooth'
                            });
                        } else if (!classHasExams) {
                            if (emptyClassState) emptyClassState.style.display = 'block';
                        } else {
                            classExams.style.display = 'block';
                        }

                        // لرزش موبایل
                        if (navigator.vibrate) {
                            navigator.vibrate([30, 50, 30]);
                        }
                    }

                    // ذخیره در localStorage
                    try {
                        localStorage.setItem('examType', currentExamType);
                    } catch (e) {}
                }

                function updateClassCardAppearance() {
                    const statusEl = document.getElementById('classStatus');
                    if (!statusEl) return;

                    if (!userHasClass) {
                        classCard.classList.add('disabled');
                    } else {
                        classCard.classList.remove('disabled');
                    }
                }

                // --- رویدادها ---
                // کلیک روی کارت عمومی
                publicCard.addEventListener('click', function() {
                    selectExamType('public');
                });

                // کلیک روی کارت کلاسی
                classCard.addEventListener('click', function() {
                    if (!userHasClass) {
                        currentExamType = 'class';
                        selectExamType('class');
                        return;
                    }
                    selectExamType('class');
                });

                // دکمه مشاهده آزمون‌های عمومی
                if (viewPublicExamsBtn) {
                    viewPublicExamsBtn.addEventListener('click', function() {
                        selectExamType('public');
                        publicExams.scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                }

                // دکمه ادامه با آزمون عمومی
                if (continuePublicBtn) {
                    continuePublicBtn.addEventListener('click', function() {
                        selectExamType('public');
                        publicExams.scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                }

                // دکمه‌های آزمون
                document.querySelectorAll('.action-btn:not(.btn-disabled)').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                });

                // کارت‌های آزمون
                document.querySelectorAll('.exam-card').forEach(card => {
                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('.action-btn') && !e.target.closest('.exam-type-badge')) {
                            this.style.transform = 'scale(0.98)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 150);

                            if (navigator.vibrate) {
                                navigator.vibrate(20);
                            }
                        }
                    });
                });

                // کارت‌های نوع آزمون
                document.querySelectorAll('.type-card:not(.disabled)').forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });
                });

                // دکمه نوتیفیکیشن
                document.querySelector('.notification-btn').addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);

                    if (navigator.vibrate) {
                        navigator.vibrate([30, 50, 30]);
                    }

                    // در حالت واقعی به صفحه نوتیفیکیشن‌ها می‌رویم
                    // window.location.href = '/notifications';
                });

                // آواتار کاربر
                document.querySelector('.user-avatar').addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);

                    if (navigator.vibrate) {
                        navigator.vibrate(30);
                    }

                    // در حالت واقعی به صفحه پروفایل می‌رویم
                    // window.location.href = '/profile';
                });

                // مقداردهی اولیه
                updateClassCardAppearance();

                // بارگذاری نوع ذخیره شده
                let savedType = 'public';
                try {
                    savedType = localStorage.getItem('examType') || 'public';
                } catch (e) {}

                selectExamType(savedType === 'class' && userHasClass ? 'class' : 'public');
            });
        </script>
    @endpush
@endsection
