<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Smart Edu | پنل کاربری')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/css/teacher-classes.css') }}">
    <script src="{{ asset('assets/js/teacher-classes.js') }}" defer></script>
    <style>
        body {
            font-family: Vazirmatn, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f8fafc;
            padding-top: 70px;
            /* فاصله زیر navbar ثابت */
        }

        .app-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-body {
            flex: 1;
            display: flex;
            min-height: calc(100vh - 70px);
        }

        .sidebar-wrap {
            width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-left: 1px solid #e5e7eb;
        }

        .content-wrap {
            flex: 1;
            padding: 1.25rem;
            background: #f8fafc;
        }

        @media (max-width: 992px) {
            .sidebar-wrap {
                width: 220px;
            }
        }

        @media (max-width: 768px) {
            .app-body {
                flex-direction: column;
            }

            .sidebar-wrap {
                width: 100%;
                border-left: 0;
                border-bottom: 1px solid #e5e7eb;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-wrapper">

        {{-- NAVBAR --}}
        @include('layouts.app.navbar')

        <div class="app-body">

{{-- SIDEBAR --}}
<div class="sidebar-wrap">
    @php
        $user = auth()->user();

        // نقش فعلی: اول selected_role (نقش انتخابی)، اگر نبود status
        $role = $role
            ?? ($user->selected_role ?? null)
            ?? ($user->status ?? null);
    @endphp

                @if ($role === 'student')
                    @include('layouts.app.sidebar-student')

                @elseif ($role === 'teacher')
                    @include('layouts.app.sidebar-teacher')

                @elseif ($role === 'admin')
                    {{-- ✅ سایدبار ادمین توی partials هست --}}
                    @include('layouts.app.sidebar-admin')

                @else
                    <div class="p-3 text-muted small">
                        نقش شما مشخص نیست. لطفاً از صفحه اصلی دوباره وارد شوید.
                    </div>
                @endif
            </div>


            {{-- MAIN --}}
            <main class="content-wrap">
                @yield('content')
            </main>

        </div>
    </div>

    <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    <!-- Global Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">تأیید عملیات</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
        </div>

        <div class="modal-body">
            <p class="mb-0" id="confirmModalMessage">آیا مطمئن هستید؟</p>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">لغو</button>
            <button type="button" class="btn btn-danger" id="confirmModalOkBtn">تأیید</button>
        </div>
        </div>
    </div>
    </div>

</body>

</html>
