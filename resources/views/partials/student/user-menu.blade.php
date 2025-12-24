{{-- Student user menu (dropdown) - Compact with "More..." --}}
<div class="user-menu dropdown">
    <button class="user-avatar dropdown-toggle"
            type="button"
            id="studentUserMenu"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="منوی دانش‌آموز">
        <i class="fas fa-chevron-left icon-closed" aria-hidden="true"></i>
        <i class="fas fa-chevron-down icon-open" aria-hidden="true"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="studentUserMenu">
        {{-- Header --}}
        <li class="px-3 py-2">
            <div class="fw-bold text-dark">
                {{ auth()->user()->name ?? ('کاربر ' . (auth()->id() ?? '')) }}
            </div>
            <div class="small text-muted">
                پایه:
                <span class="fw-semibold">
                    {{ $studentGrade ?? (auth()->user()->grade ?? '—') }}
                </span>
            </div>
        </li>

        <li><hr class="dropdown-divider"></li>

        {{-- Primary (always visible) --}}
        <li class="menu-group-title">اصلی</li>

        <li>
            <a class="dropdown-item {{ request()->routeIs('student.index') ? 'active' : '' }}"
               href="{{ route('student.index') }}">
                <i class="fas fa-house"></i>
                <span>داشبورد</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item {{ request()->routeIs('student.classrooms.*') ? 'active' : '' }}"
               href="{{ route('student.classrooms.index') }}">
                <i class="fas fa-chalkboard"></i>
                <span>کلاس‌های من</span>
            </a>
        </li>

        <li>
            <a class="dropdown-item {{ request()->routeIs('student.classrooms.join.*') ? 'active' : '' }}"
               href="{{ route('student.classrooms.join.form') }}">
                <i class="fas fa-plus"></i>
                <span>پیوستن به کلاس</span>
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        {{-- More (collapsible) --}}
        <li>
            <button class="dropdown-item menu-more-toggle"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#studentMenuMore"
                    aria-expanded="false"
                    aria-controls="studentMenuMore">
                <i class="fas fa-ellipsis-h"></i>
                <span>بیشتر…</span>
            </button>
        </li>

        <li class="px-2">
            <div class="collapse" id="studentMenuMore">
                <div class="menu-more-panel">
                    <div class="menu-group-title">آزمون و پیشرفت</div>

                    <a class="dropdown-item {{ request()->routeIs('student.exams.*') ? 'active' : '' }}"
                       href="{{ route('student.exams.classroom') }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>آزمون‌ها</span>
                    </a>

                    <a class="dropdown-item {{ request()->routeIs('student.reports.*') ? 'active' : '' }}"
                       href="{{ route('student.reports.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>گزارش پیشرفت</span>
                    </a>

                    <a class="dropdown-item {{ request()->routeIs('student.learning-path') ? 'active' : '' }}"
                       href="{{ route('student.learning-path') }}">
                        <i class="fas fa-route"></i>
                        <span>مسیر یادگیری</span>
                    </a>

                    <div class="menu-group-title mt-2">ارتباط</div>

                    <a class="dropdown-item {{ request()->routeIs('student.my-teachers.*') ? 'active' : '' }}"
                       href="{{ route('student.my-teachers.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <span>معلم‌های من</span>
                    </a>

                    <a class="dropdown-item {{ request()->routeIs('student.support.*') ? 'active' : '' }}"
                       href="{{ route('student.support.index') }}">
                        <i class="fas fa-life-ring"></i>
                        <span>پشتیبانی</span>
                    </a>

                    <div class="menu-group-title mt-2">حساب کاربری</div>

                    <a class="dropdown-item {{ request()->routeIs('student.profile') ? 'active' : '' }}"
                       href="{{ route('student.profile') }}">
                        <i class="fas fa-user"></i>
                        <span>پروفایل</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>خروج</span>
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</div>
