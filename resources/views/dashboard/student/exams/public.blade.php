@extends('layouts.student-app')

@section('title', 'شروع آزمون')

@section('content')
    <section id="learning" class="mobile-section active">
        <div class="start-exam">
            <div class="start-exam-header">
                <h3 class="mb-1">⭐ شروع آزمون</h3>
                <div class="start-exam-subtitle">فقط آزمون‌های پایه‌ی خودت نمایش داده می‌شود.</div>
            </div>

            <div class="start-exam-grid">

                {{-- کارت A: آزمون رایگان --}}
                <div class="se-card se-card-accent">
                    <div class="se-title">آزمون‌های رایگان</div>
                    <div class="se-note">پایه شما: <strong>—</strong></div>

                    <label class="se-label" for="freeSubject">فیلتر درس (اختیاری)</label>
                    <select class="form-select form-select-sm" id="freeSubject">
                        <option selected>همه درس‌ها</option>
                        <option>ریاضی</option>
                        <option>فیزیک</option>
                        <option>شیمی</option>
                        <option>زیست</option>
                    </select>

                    <button class="btn btn-success se-btn mt-3" type="button"
                            onclick="window.location.href='{{ route('student.exams.index') }}'">
                        مشاهده آزمون‌های رایگان
                    </button>

                    <div class="se-empty mt-2">
                        لیست همه آزمون‌های رایگان در صفحه آزمون‌ها نمایش داده می‌شود.
                    </div>
                </div>

                {{-- کارت B: آزمون‌های کلاسی --}}
                <div class="se-card">
                    <div class="se-title">آزمون‌های کلاسی</div>
                    <div class="se-note">بر اساس کلاس‌هایی که عضو هستی.</div>

                    <div class="se-split">
                        <div class="se-split-col">
                            <div class="se-split-title">کلاس تک</div>
                            <div class="se-count">آزمون فعال: <strong>—</strong></div>
                            <button class="btn btn-primary btn-sm se-btn-lite" type="button"
                                    onclick="window.location.href='{{ route('student.exams.classroom') }}'">
                                مشاهده آزمون‌های تک
                            </button>
                        </div>

                        <div class="se-split-col">
                            <div class="se-split-title">کلاس جامع</div>
                            <div class="se-count">آزمون فعال: <strong>—</strong></div>
                            <button class="btn btn-primary btn-sm se-btn-lite" type="button"
                                    onclick="window.location.href='{{ route('student.exams.classroom') }}'">
                                مشاهده آزمون‌های جامع
                            </button>
                        </div>
                    </div>

                    <a class="se-link mt-3" href="{{ route('student.classrooms.join.form') }}">
                        اگر کلاسی نداری: برای ثبت‌نام کلاس جدید اینجا →
                    </a>
                </div>

                {{-- کارت C: ادامه آزمون قبلی --}}
                <div class="se-card">
                    <div class="se-title">ادامه آزمون قبلی</div>
                    <div class="se-note">اگر آزمون نیمه‌کاره داشته باشی، از همینجا ادامه می‌دهی.</div>

                    <div class="se-resume">
                        <div class="se-resume-row">
                            <span class="se-muted">عنوان:</span>
                            <span>—</span>
                        </div>
                        <div class="se-resume-row">
                            <span class="se-muted">پیشرفت:</span>
                            <span>—٪</span>
                        </div>
                        <div class="se-resume-row">
                            <span class="se-muted">زمان باقی‌مانده:</span>
                            <span>—</span>
                        </div>
                    </div>

                    <button class="btn btn-outline-success se-btn mt-3" type="button" disabled>
                        ادامه آزمون
                    </button>

                    <div class="se-empty mt-2">
                        اگر آزمون نیمه‌کاره نداری: «آزمون نیمه‌کاره نداری»
                        <a class="se-link-inline" href="{{ route('student.exams.index') }}">رفتن به آزمون‌ها →</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
