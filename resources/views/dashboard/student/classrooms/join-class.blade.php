@extends('layouts.app')
@section('title', 'ورود به کلاس')

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm border-0">
            <div class="card-header fw-bold bg-white">
                ورود به کلاس با کد
            </div>

            <div class="card-body">

                {{-- پیام موفقیت --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- پیام هشدار (اگر بعداً استفاده شد) --}}
                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                {{-- پیام‌های خطا کلی --}}
                @if ($errors->any())
                    <div class="alert alert-warning small">
                        لطفاً خطاهای زیر را بررسی کن:
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.classrooms.join') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">کد ورود کلاس</label>

                        {{-- ✅ اصلاح: dir="ltr" برای نمایش درست کد --}}
                        <input type="text" dir="ltr" name="join_code"
                            class="form-control @error('join_code') is-invalid @enderror" value="{{ old('join_code') }}"
                            placeholder="مثلاً: ABC123" autocomplete="off">

                        @error('join_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div class="form-text">
                            این کد را از معلم دریافت می‌کنی. بعد از ثبت، کلاس به لیستت اضافه می‌شود.
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            عضویت در کلاس
                        </button>

                        <a href="{{ route('student.classrooms.index') }}" class="btn btn-link">
                            بازگشت
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection
