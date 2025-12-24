@extends('layouts.guest')

@section('title', 'سامانه هوشمند آموزش | آزمون‌های آنلاین تقویتی، کنکور و المپیاد')

@section('content')
    @include('shared.navigation')

    @include('partials.landing.hero')
    @include('partials.landing.features')
    @include('partials.landing.levels')
    @include('partials.landing.pricing')
    @include('partials.landing.screenshots')
    @include('partials.landing.samples')
    @include('partials.landing.demo')
    @include('partials.landing.testimonials')
    @include('partials.landing.cta')
    @include('partials.landing.faq')
    @include('partials.landing.footer')

    @include('shared.auth-modal')

    {{-- فقط اگر سرور گفت نیاز به نقش هست، مودال را باز کن --}}
    @if (session('need_role'))
        @push('scripts')
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    const authModalEl = document.getElementById('authModal');
                    if (!authModalEl || !window.bootstrap) return;

                    const authModal = new bootstrap.Modal(authModalEl);
                    authModal.show();

                    if (window.showStep) showStep(3);
                });
            </script>
        @endpush
    @endif
@endsection
