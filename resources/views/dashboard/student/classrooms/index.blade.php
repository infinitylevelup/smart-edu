@extends('layouts.student-app')

@section('title', 'کلاس‌های من')

@section('content')
    <section id="classrooms" class="mobile-section active">
        <div class="cls-wrap">

            <div class="cls-header">
                <div>
                    <h3 class="mb-1">کلاس‌های من</h3>
                    <div class="cls-subtitle">کلاس‌هایی که عضو هستی + امکان ثبت‌نام با کد دعوت</div>
                </div>

                <a href="{{ route('student.classrooms.join.form') }}" class="btn btn-success cls-cta">
                    <i class="bi bi-plus-lg ms-1"></i>
                    ثبت‌نام کلاس جدید
                </a>
            </div>

            @php
                /** @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $classrooms */
                $totalClasses = $classrooms->count();
                $lastClass = $classrooms->first();
                $teachersCount = $classrooms->pluck('teacher_id')->unique()->count();
            @endphp

            {{-- Summary cards (real) --}}
            <div class="cls-kpi-grid">
                <div class="cls-card">
                    <div class="cls-title">تعداد کلاس‌ها</div>
                    <div class="cls-kpi">
                        <span class="cls-kpi-value">{{ $totalClasses }}</span>
                        <span class="cls-kpi-unit">کلاس</span>
                    </div>
                    <div class="cls-note">کل کلاس‌های فعال</div>
                </div>

                <div class="cls-card cls-card-accent">
                    <div class="cls-title">آخرین کلاس</div>
                    <div class="cls-kpi">
                        <span class="cls-kpi-value">{{ $lastClass?->title ?? '—' }}</span>
                    </div>
                    <div class="cls-note">آخرین کلاسی که عضو شدی</div>
                </div>

                <div class="cls-card">
                    <div class="cls-title">معلم‌ها</div>
                    <div class="cls-kpi">
                        <span class="cls-kpi-value">{{ $teachersCount }}</span>
                        <span class="cls-kpi-unit">نفر</span>
                    </div>
                    <div class="cls-note">در کلاس‌های شما</div>
                </div>

                <div class="cls-card">
                    <div class="cls-title">وضعیت</div>
                    <div class="cls-kpi">
                        <span class="cls-kpi-value cls-trend-ok">✓</span>
                        <span class="cls-kpi-unit">فعال</span>
                    </div>
                    <div class="cls-note">عضویت‌ها فعال هستند</div>
                </div>
            </div>

            {{-- Class list --}}
            <div class="cls-section">
                <div class="cls-section-head">
                    <h5 class="mb-0">لیست کلاس‌ها</h5>
                    <a class="cls-link" href="{{ route('student.classrooms.join.form') }}">ثبت‌نام با کد →</a>
                </div>

                <div class="cls-list">
                    @forelse ($classrooms as $classroom)
                        <div class="cls-item">
                            <div class="cls-dot cls-dot-ok"></div>

                            <div class="cls-item-main">
                                <div class="cls-item-title">
                                    {{ $classroom->title }}

                                    @if (session('highlight_class_id') == $classroom->id)
                                        <span class="badge bg-success ms-2">جدید</span>
                                    @endif
                                </div>

                                <div class="cls-item-meta">
                                    <span class="cls-muted">نوع:</span>
                                    {{ $classroom->classroom_type === 'comprehensive' ? 'جامع' : 'تک' }}

                                    <span class="cls-sep">•</span>

                                    <span class="cls-muted">پایه:</span>
                                    {{ $classroom->grade?->title ?? '—' }}

                                    <span class="cls-sep">•</span>

                                    <span class="cls-muted">معلم:</span>
                                    {{ $classroom->teacher?->name ?? '—' }}
                                </div>

                                <div class="cls-item-meta mt-1">
                                    <span class="cls-muted">آزمون‌ها:</span>
                                    {{ $classroom->exams_count ?? 0 }}

                                    <span class="cls-sep">•</span>

                                    <span class="cls-muted">دانش‌آموزان:</span>
                                    {{ $classroom->students_count ?? 0 }}
                                </div>
                            </div>

                            <div class="cls-item-actions">
                                <a href="{{ route('student.classrooms.show', $classroom) }}"
                                   class="btn btn-primary btn-sm">
                                    ورود به کلاس
                                </a>

                                <a href="{{ route('student.classrooms.show', $classroom) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    جزئیات
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="cls-empty">
                            هنوز هیچ کلاسی نداری. با «ثبت‌نام کلاس جدید» و وارد کردن کد دعوت، عضو کلاس شو.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Help / Tips --}}
            <div class="cls-section">
                <div class="cls-tip-card">
                    <div class="cls-tip-title">راهنما</div>
                    <ul class="cls-tip-list">
                        <li>برای عضویت در کلاس، از صفحه ثبت‌نام کد دعوت را وارد کن.</li>
                        <li>بعد از عضویت، آزمون‌های کلاسی از مسیر «شروع آزمون» قابل دسترسی است.</li>
                        <li>اگر کلاس پیدا نشد، از معلم کد صحیح را بگیر.</li>
                    </ul>

                    <div class="cls-tip-actions">
                        <a href="{{ route('student.classrooms.join.form') }}" class="btn btn-success cls-btn">
                            ثبت‌نام با کد دعوت
                        </a>
                        <a href="{{ route('student.exams.classroom') }}" class="btn btn-outline-success cls-btn">
                            آزمون‌های کلاسی
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
