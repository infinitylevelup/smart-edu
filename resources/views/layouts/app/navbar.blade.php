<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
    <div class="container-fluid px-3">

        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('landing') }}">
            <i class="fas fa-graduation-cap"></i>
            Smart Edu
        </a>

        {{-- Mobile sidebar toggle (فعلاً فقط UI) --}}
        <button class="btn btn-outline-light d-lg-none ms-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarOffcanvas">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Right side --}}
        <div class="ms-auto d-flex align-items-center gap-3">

            {{-- notifications placeholder --}}
            <button class="btn btn-light btn-sm position-relative">
                <i class="fas fa-bell text-primary"></i>
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            </button>

            {{-- User dropdown --}}
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle fs-5"></i>
                    <span class="d-none d-md-inline">
                        {{ auth()->user()->name ?? 'کاربر' }}
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end text-end shadow">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-edit ms-2"></i>
                            پروفایل
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-repeat ms-2"></i>
                            تغییر نقش
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt ms-2"></i>
                                خروج
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>
