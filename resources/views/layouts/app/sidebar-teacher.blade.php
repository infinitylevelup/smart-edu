<aside class="sidebar bg-white border-end p-3 h-100">
    <div class="fw-bold mb-3 text-success">
        <i class="fas fa-chalkboard-teacher ms-1"></i>
        پنل معلم
    </div>

    <ul class="nav flex-column gap-1">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.teacher') ? 'active' : '' }}"
                href="{{ route('teacher.index') }}">
                <i class="fas fa-home ms-2"></i> داشبورد
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}"
                href="{{ route('teacher.exams.index') }}">
                <i class="fas fa-plus-circle ms-2"></i> ساخت / مدیریت آزمون
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}"
                href="{{ route('teacher.classes.index') }}">
                <i class="fas fa-users ms-2"></i> کلاس‌های من
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.students') ? 'active' : '' }}"
                href="{{ route('teacher.students.index') }}">
                <i class="fas fa-user-graduate ms-2"></i> دانش‌آموزان
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.reports.*') ? 'active' : '' }}"
                href="{{ route('teacher.reports.index') }}">
                <i class="fas fa-chart-bar ms-2"></i> گزارش‌ها
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.profile') ? 'active' : '' }}"
                href="{{ route('teacher.profile') }}">
                <i class="fas fa-user ms-2"></i> پروفایل
            </a>
        </li>

        <li class="nav-item mt-2">
            <hr>
        </li>

        <li class="nav-item">
            <a class="nav-link text-danger" href="#"
                onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt ms-2"></i> خروج
            </a>
        </li>

    </ul>
</aside>
