@extends('layouts.app')
@section('title', 'پروفایل معلم')

@push('styles')
    <style>
        /* تم کامل SmartEdu */
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
            --dark-light: #3A3F6D;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --gradient-3: linear-gradient(135deg, #FFD166, #FF9A3D);
            --gradient-4: linear-gradient(135deg, #7209B7, #3A0CA3);
            --radius-xl: 24px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .profile-container {
            max-width: 1200px;
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

        @keyframes slideInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes float {

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

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-15px) translateX(-10px);
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

        /* ========== HERO SECTION ========== */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(123, 104, 238, 0.1) 0%,
                    rgba(255, 107, 157, 0.1) 50%,
                    rgba(0, 212, 170, 0.1) 100%);
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 8s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 10s ease-in-out infinite reverse;
        }

        .hero-content h1 {
            font-weight: 900;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-content h1::before {
            content: '';
            width: 8px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .hero-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 700px;
            margin: 0;
        }

        /* ========== PROFILE LAYOUT ========== */
        .profile-layout {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
            margin-bottom: 35px;
        }

        @media (max-width: 992px) {
            .profile-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ========== SIDEBAR ========== */
        .profile-sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .sidebar-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(123, 104, 238, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideInLeft 0.5s ease-out;
        }

        .sidebar-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        /* ========== AVATAR SECTION ========== */
        .avatar-section {
            text-align: center;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .avatar-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--light);
            box-shadow: var(--shadow-lg);
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5rem;
            font-weight: 900;
        }

        .avatar-status {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            border: 3px solid var(--light);
            box-shadow: var(--shadow-sm);
        }

        .avatar-upload {
            margin-top: 20px;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--primary-light);
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.9rem;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-upload:hover {
            background: var(--gradient-1);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== QUICK STATS ========== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            transition: all 0.3s;
        }

        .stat-item:hover {
            background: var(--primary-light);
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--dark);
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--gray);
            font-weight: 700;
        }

        /* ========== MAIN CONTENT ========== */
        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .main-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(123, 104, 238, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-gray);
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-edit {
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.9rem;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        /* ========== INFO GRID ========== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-item {
            margin-bottom: 20px;
        }

        .info-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: var(--dark);
            font-weight: 900;
            font-size: 1.1rem;
            padding: 12px 16px;
            background: var(--light-gray);
            border-radius: var(--radius-md);
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .info-value:hover {
            border-color: var(--primary-light);
            background: var(--light);
        }

        /* ========== FORM STYLES ========== */
        .form-group {
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .form-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .form-select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 18px center;
            background-size: 16px;
            padding-left: 45px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            min-height: 120px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        /* ========== PASSWORD SECTION ========== */
        .password-input-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 1.1rem;
        }

        /* ========== ACTION BUTTONS ========== */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .btn-action {
            padding: 16px 24px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .btn-action:active {
            transform: scale(0.97);
        }

        .btn-save {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 10px 25px rgba(123, 104, 238, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(123, 104, 238, 0.4);
        }

        .btn-save::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-save:hover::before {
            left: 100%;
        }

        .btn-cancel {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-cancel:hover {
            background: var(--light-gray);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger {
            background: transparent;
            color: #FF6B9D;
            border: 2px solid #FF6B9D;
        }

        .btn-danger:hover {
            background: rgba(255, 107, 157, 0.1);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        /* ========== SETTINGS GRID ========== */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .setting-item {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .setting-item:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
            background: var(--light);
        }

        .setting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .setting-title {
            font-weight: 900;
            font-size: 1rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .setting-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .setting-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--gray);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--primary);
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }

        .setting-description {
            color: var(--gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin: 0;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .profile-container {
                padding: 15px 10px 60px;
            }

            .hero-section {
                padding: 25px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .sidebar-card,
            .main-card {
                padding: 25px;
            }

            .avatar-image {
                width: 120px;
                height: 120px;
                font-size: 2.8rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-edit {
                align-self: stretch;
                justify-content: center;
            }

            .avatar-image {
                width: 100px;
                height: 100px;
                font-size: 2.2rem;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-action,
        .btn-edit,
        .btn-upload,
        .form-input,
        .form-select {
            min-height: 48px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        {{-- ========== HERO SECTION ========== --}}
        <div class="hero-section">
            <div class="hero-content">
                <h1>
                    <span
                        style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        پروفایل معلم
                    </span>
                    👨‍🏫
                </h1>
                <p class="hero-subtitle">
                    مدیریت اطلاعات شخصی، تنظیمات حساب کاربری و سفارشی‌سازی پنل آموزشی.
                </p>
            </div>
        </div>

        {{-- ========== PROFILE LAYOUT ========== --}}
        <div class="profile-layout">
            {{-- ========== SIDEBAR ========== --}}
            <div class="profile-sidebar">
                {{-- Avatar Card --}}
                <div class="sidebar-card avatar-section">
                    <div class="avatar-wrapper">
                        <div class="avatar-image">
                            {{ substr(auth()->user()->name ?? 'م', 0, 1) }}
                        </div>
                        <div class="avatar-status"></div>
                    </div>

                    <h3 style="font-weight: 900; color: var(--dark); margin-bottom: 5px;">
                        {{ auth()->user()->name ?? 'معلم گرامی' }}
                    </h3>
                    <p style="color: var(--gray); margin-bottom: 20px; font-size: 0.95rem;">
                        {{ auth()->user()->email ?? 'teacher@smartedu.ir' }}
                    </p>

                    <div class="avatar-upload">
                        <label for="avatar-upload" class="btn-upload">
                            <i class="fas fa-camera"></i>
                            تغییر تصویر پروفایل
                        </label>
                        <input type="file" id="avatar-upload" style="display: none;" accept="image/*">
                    </div>
                </div>

                {{-- Quick Stats Card --}}
                <div class="sidebar-card">
                    <h4
                        style="font-weight: 900; color: var(--dark); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-chart-bar" style="color: var(--primary);"></i>
                        آمار سریع
                    </h4>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">۱۲</div>
                            <div class="stat-label">کلاس فعال</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">۱۵۶</div>
                            <div class="stat-label">دانش‌آموز</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">۴۸</div>
                            <div class="stat-label">آزمون ساخته‌شده</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">۹۵%</div>
                            <div class="stat-label">رضایت</div>
                        </div>
                    </div>
                </div>

                {{-- Account Status Card --}}
                <div class="sidebar-card">
                    <h4
                        style="font-weight: 900; color: var(--dark); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-shield-check" style="color: var(--accent);"></i>
                        وضعیت حساب
                    </h4>

                    <div
                        style="background: var(--light-gray); padding: 15px; border-radius: var(--radius-md); margin-bottom: 15px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-weight: 700; color: var(--gray);">احراز هویت:</span>
                            <span style="color: var(--accent); font-weight: 900;">
                                <i class="fas fa-check-circle"></i>
                                تایید شده
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; color: var(--gray);">عضویت از:</span>
                            <span style="color: var(--dark); font-weight: 900;">۱۴۰۲/۰۵/۱۵</span>
                        </div>
                    </div>

                    <button class="btn-action btn-danger" style="width: 100%; margin-top: 10px;">
                        <i class="fas fa-sign-out-alt"></i>
                        خروج از حساب
                    </button>
                </div>
            </div>

            {{-- ========== MAIN CONTENT ========== --}}
            <div class="profile-main">
                {{-- Personal Information Card --}}
                <div class="main-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-user-circle"></i>
                            اطلاعات شخصی
                        </div>
                        <button class="btn-edit" onclick="toggleEditMode('personal')">
                            <i class="fas fa-edit"></i>
                            ویرایش اطلاعات
                        </button>
                    </div>

                    <div class="info-grid" id="personal-info">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user"></i>
                                نام و نام خانوادگی
                            </div>
                            <div class="info-value">{{ auth()->user()->name ?? 'معلم نمونه' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-envelope"></i>
                                ایمیل
                            </div>
                            <div class="info-value">{{ auth()->user()->email ?? 'teacher@smartedu.ir' }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-phone"></i>
                                تلفن همراه
                            </div>
                            <div class="info-value">۰۹۱۲۳۴۵۶۷۸۹</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-graduation-cap"></i>
                                تخصص
                            </div>
                            <div class="info-value">ریاضیات و فیزیک</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-building"></i>
                                مدرسه/آموزشگاه
                            </div>
                            <div class="info-value">دبیرستان تیزهوشان شهید بهشتی</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-alt"></i>
                                سابقه تدریس
                            </div>
                            <div class="info-value">۸ سال</div>
                        </div>
                    </div>

                    <form id="personal-form" style="display: none;" class="info-grid">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                نام و نام خانوادگی
                            </label>
                            <input type="text" class="form-input" value="{{ auth()->user()->name ?? 'معلم نمونه' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                ایمیل
                            </label>
                            <input type="email" class="form-input"
                                value="{{ auth()->user()->email ?? 'teacher@smartedu.ir' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                تلفن همراه
                            </label>
                            <input type="tel" class="form-input" value="۰۹۱۲۳۴۵۶۷۸۹">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-graduation-cap"></i>
                                تخصص
                            </label>
                            <select class="form-select">
                                <option>ریاضیات</option>
                                <option>فیزیک</option>
                                <option>شیمی</option>
                                <option>زیست‌شناسی</option>
                                <option>ادبیات فارسی</option>
                                <option>زبان انگلیسی</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building"></i>
                                مدرسه/آموزشگاه
                            </label>
                            <input type="text" class="form-input" value="دبیرستان تیزهوشان شهید بهشتی">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i>
                                سابقه تدریس
                            </label>
                            <input type="text" class="form-input" value="۸ سال">
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-save" onclick="saveChanges('personal')">
                                <i class="fas fa-save"></i>
                                ذخیره تغییرات
                            </button>
                            <button type="button" class="btn-action btn-cancel" onclick="toggleEditMode('personal')">
                                <i class="fas fa-times"></i>
                                انصراف
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Security Settings Card --}}
                <div class="main-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-shield-alt"></i>
                            امنیت و رمز عبور
                        </div>
                        <button class="btn-edit" onclick="toggleEditMode('security')">
                            <i class="fas fa-edit"></i>
                            تغییر رمز عبور
                        </button>
                    </div>

                    <div id="security-info">
                        <div
                            style="background: var(--light-gray); padding: 20px; border-radius: var(--radius-lg); margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                                <div style="color: var(--accent); font-size: 1.5rem;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h4 style="font-weight: 900; color: var(--dark); margin: 0 0 5px;">رمز عبور شما امن است
                                    </h4>
                                    <p style="color: var(--gray); margin: 0; font-size: 0.9rem;">آخرین تغییر: ۲ هفته پیش
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form id="security-form" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-lock"></i>
                                رمز عبور فعلی
                            </label>
                            <div class="password-input-group">
                                <input type="password" class="form-input" id="current-password">
                                <button type="button" class="toggle-password"
                                    onclick="togglePassword('current-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-key"></i>
                                رمز عبور جدید
                            </label>
                            <div class="password-input-group">
                                <input type="password" class="form-input" id="new-password">
                                <button type="button" class="toggle-password" onclick="togglePassword('new-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-key"></i>
                                تکرار رمز عبور جدید
                            </label>
                            <div class="password-input-group">
                                <input type="password" class="form-input" id="confirm-password">
                                <button type="button" class="toggle-password"
                                    onclick="togglePassword('confirm-password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="button" class="btn-action btn-save" onclick="saveChanges('security')">
                                <i class="fas fa-save"></i>
                                تغییر رمز عبور
                            </button>
                            <button type="button" class="btn-action btn-cancel" onclick="toggleEditMode('security')">
                                <i class="fas fa-times"></i>
                                انصراف
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Notification Settings Card --}}
                <div class="main-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-bell"></i>
                            تنظیمات اطلاع‌رسانی
                        </div>
                    </div>

                    <div class="settings-grid">
                        <div class="setting-item">
                            <div class="setting-header">
                                <div class="setting-title">
                                    <i class="fas fa-envelope"></i>
                                    ایمیل اطلاع‌رسانی
                                </div>
                                <label class="setting-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <p class="setting-description">
                                دریافت اطلاعیه‌های مهم و گزارش‌های هفتگی از طریق ایمیل
                            </p>
                        </div>

                        <div class="setting-item">
                            <div class="setting-header">
                                <div class="setting-title">
                                    <i class="fas fa-comment-alt"></i>
                                    پیامک اطلاع‌رسانی
                                </div>
                                <label class="setting-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <p class="setting-description">
                                دریافت هشدارهای مهم و یادآوری‌ها از طریق پیامک
                            </p>
                        </div>

                        <div class="setting-item">
                            <div class="setting-header">
                                <div class="setting-title">
                                    <i class="fas fa-chart-line"></i>
                                    گزارش‌های تحلیلی
                                </div>
                                <label class="setting-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <p class="setting-description">
                                دریافت گزارش ماهانه عملکرد کلاس‌ها و دانش‌آموزان
                            </p>
                        </div>

                        <div class="setting-item">
                            <div class="setting-header">
                                <div class="setting-title">
                                    <i class="fas fa-exclamation-circle"></i>
                                    هشدارهای فوری
                                </div>
                                <label class="setting-switch">
                                    <input type="checkbox" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <p class="setting-description">
                                دریافت هشدار برای مشکلات فنی یا موارد اضطراری
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-action, .btn-edit, .btn-upload, .setting-item');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // آپلود تصویر پروفایل
            const avatarUpload = document.getElementById('avatar-upload');
            if (avatarUpload) {
                avatarUpload.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatarImage = document.querySelector('.avatar-image');
                            avatarImage.style.backgroundImage = `url(${e.target.result})`;
                            avatarImage.style.backgroundSize = 'cover';
                            avatarImage.style.backgroundPosition = 'center';
                            avatarImage.innerHTML = '';

                            // نمایش پیام موفقیت
                            showToast('تصویر پروفایل با موفقیت آپلود شد!', 'success');
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }

            // انیمیشن ورود المان‌ها
            const animateElements = () => {
                const cards = document.querySelectorAll('.sidebar-card, .main-card');
                cards.forEach((card, i) => {
                    card.style.animationDelay = `${i * 0.15}s`;
                    card.style.animation = 'fadeIn 0.5s ease-out forwards';
                    card.style.opacity = '0';
                });
            };

            // اجرای انیمیشن بعد از لود صفحه
            setTimeout(animateElements, 300);
        });

        // تابع toggle ویرایش
        function toggleEditMode(section) {
            const infoSection = document.getElementById(`${section}-info`);
            const formSection = document.getElementById(`${section}-form`);
            const editButton = document.querySelector(`[onclick="toggleEditMode('${section}')"]`);

            if (infoSection.style.display !== 'none') {
                infoSection.style.display = 'none';
                formSection.style.display = 'block';
                editButton.innerHTML = '<i class="fas fa-times"></i> انصراف';
                editButton.style.background = 'var(--light-gray)';
                editButton.style.color = 'var(--dark)';
            } else {
                infoSection.style.display = 'block';
                formSection.style.display = 'none';
                editButton.innerHTML = '<i class="fas fa-edit"></i> ویرایش اطلاعات';
                editButton.style.background = 'transparent';
                editButton.style.color = 'var(--primary)';
            }
        }

        // تابع ذخیره تغییرات
        function saveChanges(section) {
            // شبیه‌سازی ذخیره تغییرات
            const saveButton = document.querySelector(`[onclick="saveChanges('${section}')"]`);
            const originalText = saveButton.innerHTML;
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> در حال ذخیره...';
            saveButton.disabled = true;

            setTimeout(() => {
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;

                toggleEditMode(section);

                // نمایش پیام موفقیت
                showToast('تغییرات با موفقیت ذخیره شد!', 'success');

                // ویبره برای موبایل
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }
            }, 1500);
        }

        // تابع نمایش/مخفی کردن رمز عبور
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggleButton = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        // تابع نمایش toast
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: ${type === 'success' ? 'var(--accent)' : 'var(--secondary)'};
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
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
