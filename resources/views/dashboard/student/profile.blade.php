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

        .gm-stat {
            border: 1px dashed #e2e8f0;
            border-radius: 12px;
            padding: .6rem .8rem;
            background: #f8fafc;
        }
    </style>
@endpush

@section('content')
    @php
        $user = $user ?? auth()->user();
        $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/images/samples/user.png');

        // ===== Gamification safe defaults =====
        $gm =
            $gamification ??
            (object) [
                'total_xp' => 0,
                'level' => 1,
                'current_streak' => 0,
                'longest_streak' => 0,
            ];

        // Level formula (Phase A): each level = 200 XP
        $xpPerLevel = 200;
        $levelBaseXp = max(0, ($gm->level - 1) * $xpPerLevel);
        $nextLevelXp = $gm->level * $xpPerLevel;

        $progressXp = max(0, $gm->total_xp - $levelBaseXp);
        $needXp = max(1, $nextLevelXp - $levelBaseXp);
        $percent = min(100, round(($progressXp / $needXp) * 100));
    @endphp

    <div class="container py-3 py-md-4" style="max-width:1100px;">

        {{-- alerts --}}
        @if (session('success'))
            <div class="alert alert-success small fw-bold">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning small fw-bold">{{ session('warning') }}</div>
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

            {{-- ===== Left / Avatar + Role + Gamification Quick View ===== --}}
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

                    {{-- ===== Gamification mini card (left column) ===== --}}
                    <div class="text-start">
                        <div class="fw-bold mb-2">
                            <i class="bi bi-trophy-fill text-warning ms-1"></i>
                            پیشرفت شما
                        </div>

                        <div class="gm-stat mb-2 text-center">
                            <div class="fw-bold">Level {{ $gm->level }}</div>
                            <div class="small text-muted mt-1">
                                XP: {{ $gm->total_xp }} / {{ $nextLevelXp }}
                            </div>

                            <div class="progress mt-2" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>

                            <div class="d-flex justify-content-between small text-muted mt-2">
                                <span>Streak: {{ $gm->current_streak }} روز</span>
                                <span>بهترین: {{ $gm->longest_streak }} روز</span>
                            </div>
                        </div>

                        <div class="text-muted small" style="line-height:1.8">
                            با شرکت در آزمون‌ها و تمرین‌ها XP می‌گیری و Levelت بالا میره.
                        </div>
                    </div>

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
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>دانش‌آموز
                                </option>
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

            {{-- ===== Right / Gamification Detail + Edit info ===== --}}
            <div class="col-12 col-lg-8">

                {{-- ===== Gamification detail (right column) ===== --}}
                <div class="profile-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold m-0">
                            <i class="bi bi-stars text-warning ms-1"></i>
                            گیمفیکیشن و انگیزه
                        </h6>
                        <span class="badge bg-success">
                            Streak فعلی: {{ $gm->current_streak }} روز
                        </span>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <div class="gm-stat text-center">
                                <div class="small text-muted">Level</div>
                                <div class="fw-bold fs-5">{{ $gm->level }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="gm-stat text-center">
                                <div class="small text-muted">XP کل</div>
                                <div class="fw-bold fs-5">{{ $gm->total_xp }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="gm-stat text-center">
                                <div class="small text-muted">XP تا Level بعد</div>
                                <div class="fw-bold fs-5">{{ max(0, $nextLevelXp - $gm->total_xp) }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="gm-stat text-center">
                                <div class="small text-muted">بهترین Streak</div>
                                <div class="fw-bold fs-5">{{ $gm->longest_streak }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            پیشرفت Level
                        </small>
                        <div class="progress mt-2" style="height: 12px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%"></div>
                        </div>

                        <div class="d-flex justify-content-between small text-muted mt-2">
                            <span>{{ $progressXp }} XP از {{ $needXp }} XP این Level</span>
                            <span>{{ $percent }}%</span>
                        </div>
                    </div>

                    <div class="alert alert-light mt-3 mb-0 small" style="line-height:1.9;">
                        🎯 هر آزمون که کامل کنی XP می‌گیری. حتی اگر نتیجه‌ت عالی نباشه، تلاش تو ارزشمند حساب میشه.
                        هر 200 XP یک Level بالا می‌ری و به چالش‌ها و امکانات جدید نزدیک‌تر میشی.
                    </div>
                </div>


                {{-- ===== Edit info ===== --}}
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
