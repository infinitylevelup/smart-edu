@extends('layouts.student-app')

@section('title', 'ثبت‌نام کلاس جدید')

@section('content')
    <section id="join-class" class="mobile-section active">
        <div class="join-wrap">

            <div class="join-header">
                <h3 class="mb-1">ثبت‌نام کلاس جدید</h3>
                <div class="join-subtitle">کد دعوت را از معلم بگیر و وارد کن تا عضو کلاس شوی.</div>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success join-alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger join-alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger join-alert">
                    <div class="fw-bold mb-1">لطفاً خطاها را بررسی کن:</div>
                    <ul class="mb-0 pe-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="join-card">
                <div class="join-card-title">ورود کد دعوت</div>
                <div class="join-card-note">
                    کد دعوت معمولاً ۶ تا ۱۲ کاراکتر است (عدد/حروف). دقیق وارد کن.
                </div>

                <form method="POST" action="{{ route('student.classrooms.join') }}" class="join-form" novalidate>
                    @csrf

<label for="join_code" class="join-label">کد دعوت کلاس</label>
<input
    type="text"
    id="join_code"
    name="join_code"
    value="{{ old('join_code') }}"
    class="form-control join-input @error('join_code') is-invalid @enderror"
    placeholder="مثلاً: 494C5446"
    autocomplete="off"
    inputmode="text"
    maxlength="32"
    required
>

@error('join_code')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror


                    <div class="join-actions mt-3">
                        <button type="submit" class="btn btn-success join-btn">
                            عضویت در کلاس
                        </button>

                        <a href="{{ route('student.classrooms.index') }}" class="btn btn-outline-primary join-btn">
                            بازگشت به کلاس‌های من
                        </a>
                    </div>
                </form>
            </div>

            <div class="join-tip-card mt-3">
                <div class="join-tip-title">راهنما</div>
                <ul class="join-tip-list">
                    <li>اگر کد را اشتباه بزنی، سیستم کلاس را پیدا نمی‌کند.</li>
                    <li>بعد از عضویت، می‌توانی آزمون‌های کلاسی را از صفحه «شروع آزمون» ببینی.</li>
                    <li>اگر چند بار خطا گرفتی، از معلم بخواه کد جدید بدهد.</li>
                </ul>

                <div class="join-tip-actions">
                    <a href="{{ route('student.exams.classroom') }}" class="btn btn-outline-success join-btn">
                        دیدن آزمون‌های کلاسی
                    </a>
                    <a href="{{ route('student.support.index') }}" class="btn btn-outline-secondary join-btn">
                        پشتیبانی
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection
