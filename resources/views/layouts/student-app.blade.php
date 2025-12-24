<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'داشبورد دانش‌آموز')</title>

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;900&display=swap" rel="stylesheet">

    {{-- Extracted CSS from Prototype --}}
    <link rel="stylesheet" href="{{ asset('assets/css/student-area.css') }}">

    @stack('styles')
</head>
<body>

    {{-- ✅ Mobile bottom nav (مثل Prototype) --}}
    {{-- ✅ Mobile bottom nav --}}
    <nav class="mobile-nav d-lg-none">
        <div class="container">
            <div class="row text-center">

                <a class="col nav-item {{ request()->routeIs('student.index') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.index') }}">
                    <i class="bi bi-house-door-fill nav-icon"></i>
                    <span class="nav-text">داشبورد</span>
                </a>

                <a class="col nav-item {{ request()->routeIs('student.exams.*') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.exams.public') }}">
                    <i class="bi bi-compass nav-icon"></i>
                    <span class="nav-text">شروع آزمون</span>
                </a>

                <a class="col nav-item {{ request()->routeIs('student.reports.*') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.reports.index') }}">
                    <i class="bi bi-clipboard-data nav-icon"></i>
                    <span class="nav-text">کارنامه</span>
                    <span class="notification-badge">۳</span>
                </a>

                <a class="col nav-item {{ request()->routeIs('student.learning-path') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.learning-path') }}">
                    <i class="bi bi-people-fill nav-icon"></i>
                    <span class="nav-text">مسیر یادگیری</span>
                </a>

                <a class="col nav-item {{ request()->routeIs('student.classrooms.*') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.classrooms.index') }}">
                    <i class="bi bi-trophy nav-icon"></i>
                    <span class="nav-text">کلاس‌های من</span>
                </a>

                <a class="col nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }} text-decoration-none"
                href="{{ route('student.profile') }}">
                    <i class="bi bi-person-circle nav-icon"></i>
                    <span class="nav-text">پروفایل</span>
                </a>

            </div>
        </div>
    </nav>

    {{-- ✅ Desktop sidebar (مثل Prototype) --}}
    <div class="desktop-sidebar d-none">
        <div class="text-center mb-4">
            <div class="student-avatar mx-auto mb-2">
                <span>{{ mb_substr(auth()->user()->name ?? 'آ', 0, 1, 'UTF-8') }}</span>
            </div>
            <h5 class="text-white mb-1">{{ auth()->user()->name ?? 'دانش‌آموز' }}</h5>
            <div class="user-plan">پلن ۲ (کنکوری)</div>
        </div>

        <div class="sidebar-nav mt-4">
            <a href="{{ route('student.index') }}" class="nav-link {{ request()->routeIs('student.index') ? 'active' : '' }}">
    <i class="bi bi-house-door"></i><span>داشبورد</span>
</a>

<a href="{{ route('student.exams.public') }}" class="nav-link {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
    <i class="bi bi-compass"></i><span>شروع آزمون ⭐</span>
</a>

<a href="{{ route('student.reports.index') }}" class="nav-link {{ request()->routeIs('student.reports.*') ? 'active' : '' }}">
    <i class="bi bi-clipboard-data"></i><span>کارنامه و تحلیل</span>
    <span class="badge bg-warning ms-2">۳</span>
</a>

<a href="{{ route('student.learning-path') }}" class="nav-link {{ request()->routeIs('student.learning-path') ? 'active' : '' }}">
    <i class="bi bi-people-fill"></i><span>مسیر یادگیری من</span>
</a>

<a href="{{ route('student.classrooms.index') }}" class="nav-link {{ request()->routeIs('student.classrooms.*') ? 'active' : '' }}">
    <i class="bi bi-trophy"></i><span>کلاس‌های من</span>
    <span class="badge bg-success ms-2">۱۲</span>
</a>

<a href="{{ route('student.profile') }}" class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
    <i class="bi bi-person-circle"></i><span>پروفایل و تنظیمات</span>
</a>

<a href="{{ route('student.support.index') }}" class="nav-link {{ request()->routeIs('student.support.*') ? 'active' : '' }}">
    <i class="bi bi-life-preserver"></i><span>پشتیبانی</span>
</a>

        </div>
    </div>

    {{-- ✅ Main content wrapper (مثل Prototype) --}}
    <div class="main-content">
        @include('layouts.app.navbar-student')

        <div class="container py-4">
            @yield('content')
        </div>
    </div>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Extracted JS from Prototype --}}
    <script src="{{ asset('assets/js/student-area.js') }}"></script>

    @stack('scripts')
</body>
</html>
