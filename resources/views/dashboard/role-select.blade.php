@extends('layouts.app')
@section('title', 'انتخاب نقش')

@section('content')
    <div class="container py-5">

        <div class="text-center mb-4">
            <h4 class="fw-bold">انتخاب نقش</h4>
            <p class="text-muted small">برای اولین ورود، نقش خود را مشخص کنید تا وارد داشبورد شوید.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-warning small">
                لطفاً خطاهای زیر را بررسی کنید:
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 justify-content-center">

            <div class="col-md-4">
                <form method="POST" action="{{ route('auth.setRole') }}">
                    @csrf
                    <input type="hidden" name="role" value="student">
                    <button class="btn btn-outline-primary w-100 py-4 h-100">
                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                        <div class="fw-bold">دانش‌آموز</div>
                        <div class="text-muted small">ورود به پنل دانش‌آموز</div>
                    </button>
                </form>
            </div>

            <div class="col-md-4">
                <form method="POST" action="{{ route('auth.setRole') }}">
                    @csrf
                    <input type="hidden" name="role" value="teacher">
                    <button class="btn btn-outline-success w-100 py-4 h-100">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                        <div class="fw-bold">معلم</div>
                        <div class="text-muted small">ورود به پنل معلم</div>
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
