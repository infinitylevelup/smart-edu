<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'سامانه هوشمند آموزش')</title>

    {{-- Fonts / Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Bootstrap RTL (Guest via CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">

    {{-- Landing CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">

    {{-- Modal CSS (clean) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/landing-auth-modal.clean.css') }}">

    @stack('styles')
</head>

<body class="landing-body" data-page="landing">

    @yield('content')

@include('shared.auth-modal')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/landing.js') }}"></script>

    @stack('scripts')
</body>
</html>
