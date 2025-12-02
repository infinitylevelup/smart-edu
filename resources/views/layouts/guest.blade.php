<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title', 'سامانه هوشمند آموزش | Smart Edu')</title>

    {{-- Vendor CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">

    {{-- Shared Theme (برای هماهنگی با partialها) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    {{-- Landing CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <style>
        body {
            font-family: Vazirmatn, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f8fafc;
            padding-top: 70px;
            /* اگر navbar ثابت باشد */
        }
    </style>

    @stack('styles')
</head>

<body>

    @yield('content')

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Project JS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/landing.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>

    @stack('scripts')
</body>

</html>
