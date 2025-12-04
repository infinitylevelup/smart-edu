@extends('layouts.app')
@section('title', 'پروفایل من - SmartEdu')

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
            --gradient-gold: linear-gradient(135deg, #FFD166, #FFB347);
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
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

        .profile-page {
            animation: fadeIn 0.6s ease both;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px 100px;
        }

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
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* آلرت‌ها */
        .alert-custom {
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            border: none;
            box-shadow: var(--shadow-sm);
            margin-bottom: 25px;
            animation: slideInRight 0.6s ease-out;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
            border-right: 4px solid var(--accent);
            color: var(--dark);
        }

        .alert-warning-custom {
            background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 209, 102, 0.05));
            border-right: 4px solid var(--gold);
            color: var(--dark);
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(255, 107, 157, 0.05));
            border-right: 4px solid var(--secondary);
            color: var(--dark);
        }

        /* کارت‌های پروفایل */
        .profile-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px;
            box-shadow: var(--shadow-md);
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            animation: fadeIn 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        /* کارت آواتار */
        .avatar-section {
            text-align: center;
            position: relative;
            padding-bottom: 25px;
            margin-bottom: 25px;
            border-bottom: 2px dashed var(--primary-light);
        }

        .avatar-wrap {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin: 0 auto 20px;
            background: var(--light-gray);
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .avatar-wrap:hover img {
            transform: scale(1.1);
        }

        .avatar-level {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gradient-gold);
            color: #5C4033;
            padding: 6px 15px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(255, 209, 102, 0.3);
            border: 3px solid white;
            animation: pulse 2s infinite;
        }

        .user-name {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark);
            margin: 0 0 5px 0;
        }

        .user-role {
            color: var(--primary);
            font-weight: 700;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: var(--primary-light);
            border-radius: 50px;
        }

        /* فرم آپلود آواتار */
        .upload-form {
            position: relative;
            margin-top: 20px;
        }

        .upload-btn {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding: 14px;
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.3);
        }

        .upload-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.4);
        }

        .upload-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .upload-btn:hover::before {
            right: 100%;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            right: 0;
            opacity: 0;
            cursor: pointer;
        }

        /* بخش گیمفیکیشن */
        .gm-stat {
            background: var(--light-gray);
            border: 2px solid rgba(0, 0, 0, 0.05);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .gm-stat:hover {
            transform: translateY(-3px);
            background: var(--primary-light);
            border-color: var(--primary-light);
            box-shadow: var(--shadow-sm);
        }

        .gm-stat-title {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gm-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
        }

        .gm-stat-subtitle {
            font-size: 0.85rem;
            color: var(--gray);
            margin-top: 5px;
        }

        /* نوار پیشرفت */
        .progress-container {
            margin: 25px 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-weight: 700;
            color: var(--dark);
        }

        .progress-bar-custom {
            height: 16px;
            background: var(--light-gray);
            border-radius: 50px;
            overflow: hidden;
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-1);
            border-radius: 50px;
            transition: width 1s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            inset: 0;
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

        /* بخش streak */
        .streak-section {
            background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 107, 157, 0.1));
            border-radius: var(--radius-lg);
            padding: 20px;
            margin: 25px 0;
            border: 2px dashed var(--gold);
            text-align: center;
        }

        .streak-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            animation: pulse 1.5s infinite;
        }

        .streak-count {
            font-size: 2.5rem;
            font-weight: 800;
            color: #D4A017;
            line-height: 1;
        }

        .streak-label {
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* بخش تغییر نقش */
        .role-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px dashed var(--primary-light);
        }

        .role-form {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 2px solid rgba(0, 0, 0, 0.05);
        }

        .role-select {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            border: 2px solid var(--primary);
            background: white;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 16px center;
            padding-left: 45px;
            cursor: pointer;
        }

        .btn-outline-custom {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--primary);
            padding: 12px 24px;
            border-radius: var(--radius-lg);
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
            box-shadow: var(--shadow-md);
        }

        /* فرم ویرایش اطلاعات */
        .edit-form-section {
            padding: 25px;
            background: var(--light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            animation: slideInRight 0.6s ease-out;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-light);
        }

        .form-header i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            animation: bounce 2s infinite;
        }

        .form-header h3 {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--dark);
            margin: 0;
        }

        .form-label {
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            border: 2px solid rgba(0, 0, 0, 0.1);
            background: var(--light-gray);
            font-size: 1rem;
            color: var(--dark);
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.1);
        }

        .form-control-custom:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .textarea-custom {
            min-height: 120px;
            resize: vertical;
        }

        .btn-save {
            background: var(--gradient-1);
            color: white;
            border: none;
            padding: 16px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-save::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-save:hover::before {
            right: 100%;
        }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .profile-page {
                padding: 15px 10px 90px;
            }

            .profile-card {
                padding: 20px;
            }

            .avatar-wrap {
                width: 120px;
                height: 120px;
            }

            .gm-stat-value {
                font-size: 1.8rem;
            }

            .streak-count {
                font-size: 2rem;
            }

            .edit-form-section {
                padding: 20px;
            }

            .form-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .form-header i {
                width: 45px;
                height: 45px;
            }

            .form-header h3 {
                font-size: 1.2rem;
            }

            .form-control-custom {
                padding: 12px;
                font-size: 0.95rem;
            }

            .btn-save,
            .upload-btn,
            .btn-outline-custom {
                padding: 14px;
                font-size: 0.95rem;
            }
        }

        .btn-save,
        .upload-btn,
        .btn-outline-custom {
            min-height: 44px;
        }

        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    @php
        // ----------------------------
        // Safe defaults
        // ----------------------------
        $user = $user ?? auth()->user();

        if (!$user) {
            $user = (object) [
                'name' => 'کاربر',
                'email' => null,
                'phone' => null,
                'national_code' => null,
                'bio' => null,
                'role' => 'student',
                'avatar' => null,
            ];
        }

        $role = $user->role ?? 'student';

        $avatarPath = !empty($user->avatar) ? 'storage/' . $user->avatar : null;
        $avatarUrl =
            $avatarPath && file_exists(public_path($avatarPath))
                ? asset($avatarPath)
                : asset('assets/images/samples/user.png');

        $gm =
            $gamification ??
            (object) [
                'total_xp' => 0,
                'level' => 1,
                'current_streak' => 0,
                'longest_streak' => 0,
            ];

        $gm->total_xp = (int) ($gm->total_xp ?? 0);
        $gm->level = max(1, (int) ($gm->level ?? 1));
        $gm->current_streak = (int) ($gm->current_streak ?? 0);
        $gm->longest_streak = (int) ($gm->longest_streak ?? 0);

        $xpPerLevel = 200;
        $levelBaseXp = max(0, ($gm->level - 1) * $xpPerLevel);
        $nextLevelXp = $gm->level * $xpPerLevel;

        $progressXp = max(0, $gm->total_xp - $levelBaseXp);
        $needXp = max(1, $nextLevelXp - $levelBaseXp);
        $percent = min(100, round(($progressXp / $needXp) * 100));

        $hasAvatarRoute = \Route::has('student.profile.avatar');
        $hasChangeRoleRoute = \Route::has('profile.change-role');
        $hasUpdateRoute = \Route::has('student.profile.update');
    @endphp

    <div class="profile-page">
        {{-- ================= ALERTS ================= --}}
        @if (session('success'))
            <div class="alert-custom alert-success-custom d-flex align-items-center gap-3">
                <div style="font-size: 1.5rem; color: var(--accent);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="flex-grow-1">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-custom alert-warning-custom d-flex align-items-center gap-3">
                <div style="font-size: 1.5rem; color: var(--gold);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    {{ session('warning') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-custom alert-danger-custom">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div style="font-size: 1.5rem; color: var(--secondary);">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="fw-bold">خطا در ذخیره اطلاعات</div>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            {{-- ===== ستون سمت چپ: آواتار و گیمفیکیشن ===== --}}
            <div class="col-12 col-lg-4">
                <div class="profile-card">
                    <div class="avatar-section">
                        <div class="avatar-wrap">
                            <img id="avatarPreview" src="{{ $avatarUrl }}" alt="آواتار">
                            <div class="avatar-level">سطح {{ $gm->level }}</div>
                        </div>

                        <h2 class="user-name">{{ $user->name ?? 'کاربر' }}</h2>
                        <div class="user-role">
                            <i class="fas fa-user-tag"></i>
                            {{ $role === 'student' ? 'دانش‌آموز' : 'معلم' }}
                        </div>
                    </div>

                    {{-- آپلود آواتار --}}
                    @if ($hasAvatarRoute)
                        <form action="{{ route('student.profile.avatar') }}" method="POST" enctype="multipart/form-data"
                            class="upload-form">
                            @csrf
                            <div class="upload-btn">
                                <i class="fas fa-camera"></i>
                                تغییر عکس پروفایل
                                <input type="file" name="avatar" id="avatarInput" class="file-input" accept="image/*">
                            </div>
                        </form>
                    @else
                        <div class="upload-form">
                            <button class="upload-btn" disabled>
                                <i class="fas fa-camera"></i>
                                تغییر عکس (به زودی)
                            </button>
                        </div>
                    @endif

                    {{-- آمار گیمفیکیشن --}}
                    <div class="mt-4">
                        <div class="gm-stat">
                            <div class="gm-stat-title">
                                <i class="fas fa-trophy" style="color: var(--gold);"></i>
                                امتیاز کلی (XP)
                            </div>
                            <div class="gm-stat-value">{{ $gm->total_xp }}</div>
                            <div class="gm-stat-subtitle">از {{ $nextLevelXp }} امتیاز تا سطح بعدی</div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label">
                                <span>پیشرفت سطح {{ $gm->level }}</span>
                                <span>{{ $percent }}%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: {{ $percent }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between text-sm mt-2"
                                style="color: var(--gray); font-size: 0.9rem;">
                                <span>{{ $progressXp }} XP</span>
                                <span>{{ $needXp }} XP</span>
                            </div>
                        </div>

                        {{-- بخش Streak --}}
                        <div class="streak-section">
                            <div class="streak-icon">🔥</div>
                            <div class="streak-count">{{ $gm->current_streak }}</div>
                            <div class="streak-label">روز متوالی فعالیت</div>
                            <div class="mt-2" style="color: var(--gray); font-size: 0.9rem;">
                                بهترین رکورد: {{ $gm->longest_streak }} روز
                            </div>
                        </div>

                        {{-- تغییر نقش --}}
                        <div class="role-section">
                            <h4 class="fw-bold mb-3" style="color: var(--dark);">
                                <i class="fas fa-random me-2" style="color: var(--primary);"></i>
                                تغییر نقش
                            </h4>

                            @if ($hasChangeRoleRoute)
                                <form action="{{ route('profile.change-role') }}" method="POST" class="role-form">
                                    @csrf
                                    <select name="role" class="role-select">
                                        <option value="student" {{ $role === 'student' ? 'selected' : '' }}>دانش‌آموز
                                        </option>
                                        <option value="teacher" {{ $role === 'teacher' ? 'selected' : '' }}>معلم</option>
                                    </select>
                                    <button type="submit" class="btn-outline-custom">
                                        <i class="fas fa-save"></i>
                                        ذخیره نقش جدید
                                    </button>
                                </form>
                            @else
                                <div class="role-form">
                                    <select class="role-select" disabled>
                                        <option>دانش‌آموز</option>
                                        <option>معلم</option>
                                    </select>
                                    <button class="btn-outline-custom" disabled>
                                        <i class="fas fa-save"></i>
                                        ذخیره نقش جدید (به زودی)
                                    </button>
                                </div>
                            @endif

                            <div class="mt-3" style="color: var(--gray); font-size: 0.9rem; line-height: 1.6;">
                                اگر نقش رو عوض کنی، بعد از رفرش به پنل همون نقش منتقل می‌شی.
                                این تغییر فقط نمایش‌دهنده نوع فعالیت تو در سیستم خواهد بود.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== ستون سمت راست: ویرایش اطلاعات ===== --}}
            <div class="col-12 col-lg-8">
                <div class="edit-form-section">
                    <div class="form-header">
                        <i class="fas fa-user-edit"></i>
                        <h3>ویرایش اطلاعات شخصی</h3>
                    </div>

                    @if ($hasUpdateRoute)
                        <form action="{{ route('student.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">نام و نام خانوادگی</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                                        class="form-control-custom" required placeholder="نام کامل خود را وارد کنید">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">ایمیل</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                                        class="form-control-custom" placeholder="example@domain.com">
                                </div>

                                <div class="col-12 col-md-6 mt-3">
                                    <label class="form-label">شماره موبایل</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                        class="form-control-custom" placeholder="۰۹۱۲۳۴۵۶۷۸۹">
                                </div>

                                <div class="col-12 col-md-6 mt-3">
                                    <label class="form-label">کد ملی</label>
                                    <input type="text" name="national_code"
                                        value="{{ old('national_code', $user->national_code ?? '') }}"
                                        class="form-control-custom" placeholder="اختیاری">
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label">درباره من</label>
                                    <textarea name="bio" rows="4" class="form-control-custom textarea-custom"
                                        placeholder="چند خط درباره خودت، علایق یا اهدافت بنویس...">{{ old('bio', $user->bio ?? '') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-check-circle"></i>
                                        ذخیره تغییرات
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <form>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">نام و نام خانوادگی</label>
                                    <input type="text" value="{{ $user->name ?? '' }}" class="form-control-custom"
                                        disabled>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">ایمیل</label>
                                    <input type="email" value="{{ $user->email ?? '' }}" class="form-control-custom"
                                        disabled>
                                </div>

                                <div class="col-12 col-md-6 mt-3">
                                    <label class="form-label">شماره موبایل</label>
                                    <input type="text" value="{{ $user->phone ?? '' }}" class="form-control-custom"
                                        disabled>
                                </div>

                                <div class="col-12 col-md-6 mt-3">
                                    <label class="form-label">کد ملی</label>
                                    <input type="text" value="{{ $user->national_code ?? '' }}"
                                        class="form-control-custom" disabled>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label">درباره من</label>
                                    <textarea rows="4" class="form-control-custom textarea-custom" disabled>{{ $user->bio ?? '' }}</textarea>
                                </div>

                                <div class="col-12">
                                    <button class="btn-save" disabled>
                                        <i class="fas fa-clock"></i>
                                        ذخیره تغییرات (به زودی)
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <div class="mt-4 p-3"
                        style="background: var(--accent-light); border-radius: var(--radius-md); border-right: 4px solid var(--accent);">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-lightbulb" style="color: var(--accent); font-size: 1.3rem;"></i>
                            <div>
                                <div class="fw-bold mb-1" style="color: var(--dark);">نکات مهم:</div>
                                <ul class="mb-0" style="color: var(--gray); font-size: 0.9rem; line-height: 1.6;">
                                    <li>اطلاعاتت رو دقیق وارد کن تا در صورت نیاز بتونیم باهات در تماس باشیم</li>
                                    <li>بیوگرافی کوتاه می‌تونه به معلم‌ها کمک کنه بهتر باهات آشنا بشن</li>
                                    <li>تغییراتت بعد از ذخیره شدن بلافاصله اعمال می‌شن</li>
                                </ul>
                            </div>
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

            if (navigator.vibrate) {
                navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate ||
                    navigator.msVibrate;
            }

            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');

            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files?.[0];
                    if (!file) return;

                    if (file.size > 5 * 1024 * 1024) {
                        showErrorModal('حجم فایل باید کمتر از ۵ مگابایت باشد');
                        this.value = '';
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        showErrorModal('فقط فایل‌های تصویری (JPG, PNG, GIF, WebP) مجاز هستند');
                        this.value = '';
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    avatarPreview.src = url;

                    if (navigator.vibrate) navigator.vibrate(50);

                    showSuccessModal('آواتار انتخاب شد! برای ذخیره دکمه آپلود رو بزن');

                    setTimeout(() => {
                        const form = avatarInput.closest('form');
                        if (form) form.submit();
                    }, 2000);
                });
            }

            const buttons = document.querySelectorAll(
            '.btn-save, .upload-btn, .btn-outline-custom:not([disabled])');
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.disabled) return;

                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);

                    if (navigator.vibrate) navigator.vibrate(30);
                });
            });

            const profileCards = document.querySelectorAll('.profile-card');
            profileCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) navigator.vibrate(20);
                });
            });

            const gmStats = document.querySelectorAll('.gm-stat');
            gmStats.forEach(stat => {
                stat.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                    if (navigator.vibrate) navigator.vibrate(20);
                });
            });
        });

        function showErrorModal(message) {
            const modal = document.createElement('div');
            modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 350px;
        width: 85%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--secondary);
    `;

            modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 15px; color: var(--secondary);">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <h3 style="margin-bottom: 12px; color: var(--dark); font-size: 1.2rem; font-weight: 700;">خطا!</h3>
        <p style="color: var(--gray); margin-bottom: 20px; font-size: 1rem; line-height: 1.5;">${message}</p>
        <button class="close-modal"
                style="width:100%; padding: 12px; border: none; background: var(--secondary); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
            متوجه شدم
        </button>
    `;

            const overlay = document.createElement('div');
            overlay.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease;
    `;

            document.body.appendChild(overlay);
            document.body.appendChild(modal);

            modal.querySelector('.close-modal')?.addEventListener('click', () => {
                modal.remove();
                overlay.remove();
            });

            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
        }

        function showSuccessModal(message) {
            const modal = document.createElement('div');
            modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 350px;
        width: 85%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--accent);
    `;

            modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 15px; color: var(--accent);">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 style="margin-bottom: 12px; color: var(--dark); font-size: 1.2rem; font-weight: 700;">موفقیت!</h3>
        <p style="color: var(--gray); margin-bottom: 20px; font-size: 1rem; line-height: 1.5;">${message}</p>
        <button class="close-modal"
                style="width:100%; padding: 12px; border: none; background: var(--accent); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
            عالیه!
        </button>
    `;

            const overlay = document.createElement('div');
            overlay.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease;
    `;

            document.body.appendChild(overlay);
            document.body.appendChild(modal);

            modal.querySelector('.close-modal')?.addEventListener('click', () => {
                modal.remove();
                overlay.remove();
            });

            if (navigator.vibrate) navigator.vibrate([50, 50, 50]);

            setTimeout(() => {
                if (document.body.contains(modal)) {
                    modal.remove();
                    overlay.remove();
                }
            }, 3000);
        }

        const style = document.createElement('style');
        style.textContent = `
@keyframes scaleIn {
    from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
    to   { transform: translate(-50%, -50%) scale(1); opacity: 1; }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
`;
        document.head.appendChild(style);
    </script>
@endpush
