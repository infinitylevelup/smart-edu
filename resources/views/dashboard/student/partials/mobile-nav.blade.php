<nav class="student-side-nav">
    <a href="{{ route('student.index') }}"
       class="nav-item {{ request()->routeIs('student.index') ? 'active' : '' }}">
        <span class="icon">🏠</span>
        <span class="label">داشبورد</span>
    </a>

    <a href="{{ route('student.exams.public') }}"
       class="nav-item {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
        <span class="icon">⭐</span>
        <span class="label">شروع آزمون</span>
    </a>

    <a href="{{ route('student.reports.index') }}"
       class="nav-item {{ request()->routeIs('student.reports.*') ? 'active' : '' }}">
        <span class="icon">📊</span>
        <span class="label">کارنامه و تحلیل</span>
    </a>

    <a href="{{ route('student.learning-path') }}"
       class="nav-item {{ request()->routeIs('student.learning-path') ? 'active' : '' }}">
        <span class="icon">🧭</span>
        <span class="label">مسیر یادگیری من</span>
    </a>

    <a href="{{ route('student.classrooms.index') }}"
       class="nav-item {{ request()->routeIs('student.classrooms.*') ? 'active' : '' }}">
        <span class="icon">🏆</span>
        <span class="label">کلاس‌های من</span>
    </a>

    <a href="{{ route('student.profile') }}"
       class="nav-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
        <span class="icon">👤</span>
        <span class="label">پروفایل و تنظیمات</span>
    </a>
</nav>
