@extends('layouts.app')
@section('title', 'معلمان من')

@push('styles')
    <style>
        .teachers-page {
            animation: fadeIn .5s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .teacher-card {
            border-radius: 1.25rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(15, 23, 42, .06);
            transition: .25s ease;
            position: relative;
            overflow: hidden;
        }

        .teacher-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(37, 99, 235, .15);
            border-color: #bfdbfe;
        }

        .teacher-header {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            color: #fff;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .teacher-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fff;
            display: grid;
            place-items: center;
            color: #2563eb;
            font-size: 1.6rem;
            font-weight: 900;
            flex-shrink: 0;
            box-shadow: inset 0 0 0 2px #dbeafe;
        }

        .teacher-name {
            font-weight: 900;
            font-size: 1.05rem;
            margin: 0;
        }

        .teacher-meta {
            font-size: .85rem;
            opacity: .9;
            margin-top: .25rem;
        }

        .teacher-body {
            padding: 1rem 1.1rem;
        }

        .teacher-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            font-weight: 800;
            font-size: .78rem;
            background: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            margin: .15rem;
            white-space: nowrap;
        }

        .teacher-actions .btn {
            border-radius: .9rem;
            font-weight: 900;
        }
    </style>
@endpush

@section('content')
    <div class="teachers-page container py-3 py-md-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold m-0">
                    <i class="bi bi-people-fill text-primary ms-1"></i>
                    معلمان من
                </h5>
                <div class="text-muted small mt-1">
                    لیست معلم‌هایی که در کلاس‌های شما فعال هستند.
                </div>
            </div>

            <a href="{{ route('student.classrooms.index') ?? '#' }}" class="btn btn-outline-secondary btn-sm fw-bold">
                کلاس‌های من
                <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>

        @if ($teachers->count())

            <div class="row g-3">
                @foreach ($teachers as $t)
                    @php
                        $initials = mb_substr($t->name ?? 'م', 0, 1);
                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="teacher-card h-100">

                            <div class="teacher-header">
                                <div class="teacher-avatar">
                                    {{ $initials }}
                                </div>

                                <div class="flex-grow-1">
                                    <p class="teacher-name">
                                        {{ $t->name ?? 'معلم' }}
                                    </p>
                                    <div class="teacher-meta">
                                        {{ $t->email ?? 'ایمیل ثبت نشده' }}
                                    </div>
                                </div>
                            </div>

                            <div class="teacher-body">

                                {{-- اگر تخصص/درس/بیو داشته باشی --}}
                                @if (!empty($t->specialty))
                                    <div class="text-muted small mb-2">
                                        <i class="bi bi-star-fill text-warning ms-1"></i>
                                        {{ $t->specialty }}
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <span class="teacher-chip">
                                        <i class="bi bi-person-badge"></i>
                                        نقش: {{ $t->role ?? 'teacher' }}
                                    </span>

                                    @if (isset($t->phone))
                                        <span class="teacher-chip">
                                            <i class="bi bi-telephone"></i>
                                            {{ $t->phone }}
                                        </span>
                                    @endif
                                </div>

                                <div class="teacher-actions d-grid gap-2 mt-3">
                                    {{-- لینک پروفایل معلم (اگر داری) --}}
                                    <a href="{{ route('student.teachers.show', $t->id) ?? '#' }}"
                                        class="btn btn-outline-primary">
                                        مشاهده پروفایل
                                        <i class="bi bi-eye ms-1"></i>
                                    </a>

                                    {{-- شروع چت/ارسال پیام (اگر سیستم پیام داری) --}}
                                    <a href="{{ route('student.messages.create', ['teacher' => $t->id]) ?? '#' }}"
                                        class="btn btn-primary">
                                        پیام به معلم
                                        <i class="bi bi-chat-dots-fill ms-1"></i>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card-soft text-center text-muted fw-bold">
                هنوز به کلاسی متصل نشدی یا معلمی برای کلاس‌هات ثبت نشده 🙂
            </div>
        @endif

    </div>
@endsection
