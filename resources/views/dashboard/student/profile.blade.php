@extends('layouts.app')
@section('title', 'پروفایل من')

@push('styles')
    <style>
        .profile-card {
            border-radius: 1.25rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
            padding: 1.1rem;
        }

        .avatar-wrap {
            width: 110px;
            height: 110px;
            border-radius: 999px;
            overflow: hidden;
            border: 3px solid #e2e8f0;
            background: #f8fafc;
            display: grid;
            place-items: center;
        }

        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    @php
        $user = $user ?? auth()->user();
        $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/samples/user.png'); // اگر sample نداری مسیرشو عوض کن
    @endphp

    <div class="container py-3 py-md-4" style="max-width:1100px;">

        {{-- alerts --}}
        @if (session('success'))
            <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">

            {{-- ===== Left / Avatar + Role ===== --}}
            <div class="col-12 col-lg-4">
                <div class="profile-card text-center h-100">

                    <div class="d-flex justify-content-center mb-2">
                        <div class="avatar-wrap">
                            <img id="avatarPreview" src="{{ $avatarUrl }}" alt="avatar">
                        </div>
                    </div>

                    <div class="fw-bold fs-5">{{ $user->name }}</div>
                    <div class="text-muted small mt-1">
                        نقش فعلی: <span class="fw-bold">{{ $user->role }}</span>
                    </div>

                    {{-- upload avatar --}}
                    <form action="{{ route('student.profile.avatar') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3">
                        @csrf
                        <input type="file" name="avatar" id="avatarInput" class="form-control form-control-sm"
                            accept="image/*">
                        <button class="btn btn-primary w-100 fw-bold mt-2">
                            <i class="bi bi-camera-fill ms-1"></i>
                            تغییر عکس پروفایل
                        </button>
                    </form>

                    <hr class="my-3">

                    {{-- change role --}}
                    <div class="text-start">
                        <div class="fw-bold mb-2">
                            <i class="bi bi-shuffle text-primary ms-1"></i>
                            تغییر نقش
                        </div>

                        <form action="{{ route('profile.change-role') }}" method="POST" class="d-grid gap-2">
                            @csrf
                            <select name="role" class="form-select form-select-sm">
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>دانش‌آموز</option>
                                <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>معلم</option>
                            </select>
                            <button class="btn btn-outline-secondary fw-bold">
                                ذخیره نقش جدید
                            </button>
                        </form>

                        <div class="text-muted small mt-2" style="line-height:1.8">
                            اگر نقش رو عوض کنی، بعد از رفرش به پنل همون نقش منتقل میشی.
                        </div>
                    </div>

                </div>
            </div>

            {{-- ===== Right / Edit info ===== --}}
            <div class="col-12 col-lg-8">
                <div class="profile-card h-100">

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-lines-fill text-primary fs-4"></i>
                        <h5 class="fw-bold m-0">ویرایش اطلاعات</h5>
                    </div>

                    <form action="{{ route('student.profile.update') }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small">نام و نام خانوادگی</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control" required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control" placeholder="اختیاری">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small">شماره موبایل</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                class="form-control" placeholder="مثلاً 09xxxxxxxxx">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small">کد ملی (اختیاری)</label>
                            <input type="text" name="national_code"
                                value="{{ old('national_code', $user->national_code ?? '') }}" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small">درباره من</label>
                            <textarea name="bio" rows="4" class="form-control" placeholder="چند خط درباره خودت بنویس...">{{ old('bio', $user->bio ?? '') }}</textarea>
                        </div>

                        <div class="col-12 d-grid">
                            <button class="btn btn-success fw-bold py-2">
                                <i class="bi bi-check2-circle ms-1"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // preview avatar before upload
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');
        if (input) {
            input.addEventListener('change', e => {
                const file = e.target.files?.[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                preview.src = url;
            });
        }
    </script>
@endpush
