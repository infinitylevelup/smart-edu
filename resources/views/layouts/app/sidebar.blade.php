{{-- ========== Desktop Sidebar ========== --}}
<div class="bg-white border-end vh-100 position-fixed top-0 end-0 pt-5 d-none d-lg-block"
    style="width: 240px; margin-top:56px;">
    <div class="p-3">

        <div class="text-center mb-4">
            <div class="fw-bold">پنل کاربری</div>
            <small class="text-muted">
                {{ auth()->user()->phone ?? 'شماره ثبت نشده' }}
            </small>
        </div>

        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a class="nav-link active rounded-3" href="#">
                    <i class="fas fa-home ms-2"></i>
                    داشبورد
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-file-pen ms-2"></i>
                    آزمون‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-chart-line ms-2"></i>
                    گزارش‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-medal ms-2"></i>
                    امتیاز و نشان‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-gear ms-2"></i>
                    تنظیمات
                </a>
            </li>

        </ul>

        <hr>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger w-100 rounded-3">
                <i class="fas fa-sign-out-alt ms-2"></i>
                خروج
            </button>
        </form>

    </div>
</div>


{{-- ========== Mobile Offcanvas Sidebar ========== --}}
<div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold">
            <i class="fas fa-graduation-cap ms-2"></i>
            Smart Edu
        </h5>
        <button type="button" class="btn-close ms-0" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <div class="text-center mb-4">
            <div class="fw-bold">پنل کاربری</div>
            <small class="text-muted">
                {{ auth()->user()->phone ?? 'شماره ثبت نشده' }}
            </small>
        </div>

        <ul class="nav flex-column gap-1">

            <li class="nav-item">
                <a class="nav-link active rounded-3" href="#">
                    <i class="fas fa-home ms-2"></i>
                    داشبورد
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-file-pen ms-2"></i>
                    آزمون‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-chart-line ms-2"></i>
                    گزارش‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-medal ms-2"></i>
                    امتیاز و نشان‌ها
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link rounded-3" href="#">
                    <i class="fas fa-gear ms-2"></i>
                    تنظیمات
                </a>
            </li>

        </ul>

        <hr>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger w-100 rounded-3">
                <i class="fas fa-sign-out-alt ms-2"></i>
                خروج
            </button>
        </form>

    </div>
</div>
