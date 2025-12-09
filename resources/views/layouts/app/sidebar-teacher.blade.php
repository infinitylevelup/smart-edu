<aside class="sidebar teacher-sidebar bg-white border-start p-3 h-100">

    <div class="fw-bold mb-3 text-success d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard-teacher fs-5"></i>
        <span>پنل معلم</span>
    </div>

    <ul class="nav flex-column gap-1">

        {{-- داشبورد --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.index') ? 'active' : '' }}"
               href="{{ route('teacher.index') }}">
                <i class="fas fa-home ms-2"></i>
                <span>داشبورد</span>
            </a>
        </li>

        {{-- ساخت / مدیریت آزمون --}}
        <li class="nav-item">
            <a href="{{ route('teacher.exams.index') }}"
               class="nav-link {{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
                <i class="fas fa-file-circle-plus ms-2"></i>
                <span>ساخت / مدیریت آزمون</span>
            </a>
        </li>

        {{-- کلاس‌های من --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.classes.*') ? 'active' : '' }}"
               href="{{ route('teacher.classes.index') }}">
                <i class="fas fa-users ms-2"></i>
                <span>کلاس‌های من</span>
            </a>
        </li>

        {{-- دانش‌آموزان --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}"
               href="{{ route('teacher.students.index') }}">
                <i class="fas fa-user-graduate ms-2"></i>
                <span>دانش‌آموزان</span>
            </a>
        </li>

        {{-- گزارش‌ها --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.reports.*') ? 'active' : '' }}"
               href="{{ route('teacher.reports.index') }}">
                <i class="fas fa-chart-bar ms-2"></i>
                <span>گزارش‌ها</span>
            </a>
        </li>

        {{-- پروفایل --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.profile') ? 'active' : '' }}"
               href="{{ route('teacher.profile') }}">
                <i class="fas fa-user ms-2"></i>
                <span>پروفایل</span>
            </a>
        </li>

        <li class="nav-item mt-2">
            <hr class="my-2">
        </li>

        {{-- خروج --}}
        <li class="nav-item">
            <a class="nav-link text-danger" href="#"
               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt ms-2"></i>
                <span>خروج</span>
            </a>
        </li>

    </ul>
</aside>
