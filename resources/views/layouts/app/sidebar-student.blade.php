<aside class="sidebar bg-white border-end p-3 h-100">
    <div class="fw-bold mb-3 text-primary">
        <i class="fas fa-user-graduate ms-1"></i>
        پنل دانش‌آموز
    </div>

    <ul class="nav flex-column gap-1">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard.student') ? 'active' : '' }}"
                href="{{ route('student.index') }}">
                <i class="fas fa-home ms-2"></i> داشبورد
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.exams.*') ? 'active' : '' }}"
                href="{{ route('student.exams.index') }}">
                <i class="fas fa-file-pen ms-2"></i> آزمون‌ها
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.reports.*') ? 'active' : '' }}"
                href="{{ route('student.reports.index') }}">
                <i class="fas fa-chart-line ms-2"></i> گزارش‌ها
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.learning-path') ? 'active' : '' }}"
                href="{{ route('student.learning-path') }}">
                <i class="fas fa-route ms-2"></i> مسیر یادگیری
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.my-teachers.*') ? 'active' : '' }}"
                href="{{ route('student.my-teachers.index') }}">
                <i class="bi bi-people-fill ms-1"></i>

                معلمان من
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.classrooms.*') ? 'active' : '' }}"
                href="{{ route('student.classrooms.index') }}">
                <i class="fas fa-users ms-2"></i> کلاس‌های من
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.support.*') ? 'active' : '' }}"
                href="{{ route('student.support.index') }}">
                <i class="fas fa-headset ms-2"></i> پشتیبانی
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.profile') ? 'active' : '' }}"
                href="{{ route('student.profile') }}">
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
