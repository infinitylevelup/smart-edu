<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'داشبورد دانش‌آموز')</title>

    {{-- Fonts / Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@100..900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap RTL (اول) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">

    {{-- Landing / Mobile-base --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/landing.tokens.patch.css') }}"> {{-- optional --}}

    {{-- Student Dashboard --}}
    <link rel="stylesheet" href="{{ asset('assets/css/student-dashboard.utilities.clean.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/student-dashboard.clean.css') }}">

    {{-- Auth modal (اگر داخل داشبورد هم استفاده می‌شود) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing-auth-modal.clean.css') }}">

    {{-- Page-specific inline styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/student-user-menu.css') }}">

    @stack('styles')
</head>

<body class="landing-body">
    @yield('content')

    {{-- اگر این فایل/ویو وجود ندارد، خطا ندهد --}}
    @includeIf('shared.auth-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-specific inline scripts --}}
    @stack('scripts')
</body>
</html>
