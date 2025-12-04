@extends('layouts.app')

@section('title', 'ورود به کلاس')

@push('styles')
    <style>
        .page-wrap {
            padding: 1.5rem 0;
        }

        .soft-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 8px 24px rgba(18, 38, 63, .06);
            background: #fff;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .page-title {
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .hint {
            color: #64748b;
            font-size: .9rem;
        }

        .join-input {
            font-weight: 700;
            letter-spacing: .08em;
        }
    </style>
@endpush

@section('content')
    <div class="container page-wrap">

        {{-- ============================================================
     | Header
     |============================================================= --}}
        <div class="page-header">
            <div>
                <h4 class="page-title mb-1">
                    <i class="bi bi-box-arrow-in-right text-primary me-1"></i>
                    ورود به کلاس با کد
                </h4>
                <div class="hint">
                    کد ورود (join_code) را از معلمت بگیر و اینجا وارد کن تا عضو کلاس شوی.
                </div>
            </div>

            <a href="{{ route('student.classrooms.index') }}"
                class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-arrow-right"></i>
                بازگشت به کلاس‌ها
            </a>
        </div>

        {{-- ============================================================
     | Card
     |============================================================= --}}
        <div class="soft-card p-3 p-md-4">

            {{-- پیام موفقیت --}}
            @if (session('success'))
                <div class="alert alert-success soft-card p-3">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- پیام هشدار --}}
            @if (session('warning'))
                <div class="alert alert-warning soft-card p-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ session('warning') }}
                </div>
            @endif

            {{-- پیام خطا کلی --}}
            @if ($errors->any())
                <div class="alert alert-danger soft-card p-3 small">
                    لطفاً خطاهای زیر را بررسی کن:
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('student.classrooms.join') }}" class="mt-2">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">کد ورود کلاس</label>

                    {{-- dir="ltr" برای نمایش درست کد --}}
                    <input type="text" dir="ltr" name="join_code"
                        class="form-control join-input @error('join_code') is-invalid @enderror"
                        value="{{ old('join_code') }}" placeholder="مثلاً: ABC123" autocomplete="off">

                    @error('join_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="form-text">
                        بعد از ثبت کد، کلاس به لیست «کلاس‌های من» اضافه می‌شود و آزمون‌های کلاس برایت فعال خواهد شد.
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i>
                        عضویت در کلاس
                    </button>

                    <a href="{{ route('student.classrooms.index') }}"
                        class="btn btn-link d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-right"></i>
                        بازگشت
                    </a>
                </div>

            </form>
        </div>
    </div>
@endsection
