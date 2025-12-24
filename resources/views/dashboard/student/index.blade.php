@extends('layouts.student-app')

@section('title', 'داشبورد دانش‌آموز')

@section('content')
    <section id="dashboard" class="mobile-section active">
        <div class="dash-cta4">

            <div class="dash-header">
                <h3 class="mb-1">داشبورد</h3>
                <div class="dash-subtitle">۴ کارت سریع برای اقدام</div>
            </div>

            <div class="dash-grid-4">
                <div class="dash-card-cta">
                    <div class="dash-card-cta-title">وضعیت فعلی و گیمیفیکیشن</div>
                    <div class="dash-card-cta-body">
                        <div class="dash-mini">
                            <div class="dash-mini-label">سطح</div>
                            <div class="dash-mini-value">—</div>
                        </div>
                        <div class="dash-mini">
                            <div class="dash-mini-label">امتیاز</div>
                            <div class="dash-mini-value">—</div>
                        </div>
                        <div class="dash-mini">
                            <div class="dash-mini-label">استریک</div>
                            <div class="dash-mini-value">—</div>
                        </div>
                    </div>
                    <a href="#" class="dash-card-cta-link">مشاهده جزئیات وضعیت →</a>
                </div>

                <div class="dash-card-cta dash-card-cta-accent">
                    <div class="dash-card-cta-title">آزمون‌های رایگان (همه پایه‌ها)</div>
                    <div class="dash-card-cta-body">
                        <div class="dash-note">لیست همه آزمون‌های رایگان را نشان می‌دهد.</div>
                        <div class="dash-pill-row">
                            <span class="dash-pill">پایه ۱۰</span>
                            <span class="dash-pill">پایه ۱۱</span>
                            <span class="dash-pill">پایه ۱۲</span>
                        </div>
                    </div>
                    <button class="btn btn-success dash-card-cta-btn" type="button">
                        مشاهده آزمون‌های رایگان
                    </button>
                </div>

                <div class="dash-card-cta">
                    <div class="dash-card-cta-title">کلاس‌های ثبت‌نام کرده</div>
                    <div class="dash-card-cta-body">
                        <div class="dash-list">
                            <div class="dash-list-item">
                                <span class="dash-dot dash-dot-ok"></span>
                                <span>کلاس: —</span>
                                <span class="dash-status">فعال</span>
                            </div>
                            <div class="dash-list-item">
                                <span class="dash-dot dash-dot-warn"></span>
                                <span>کلاس: —</span>
                                <span class="dash-status">نیاز به تکمیل</span>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="dash-card-cta-link">مدیریت کلاس‌ها / ثبت‌نام جدید →</a>
                </div>

                <div class="dash-card-cta">
                    <div class="dash-card-cta-title">آخرین وضعیت</div>
                    <div class="dash-card-cta-body">
                        <div class="dash-row2">
                            <span class="dash-muted">آخرین آزمون:</span>
                            <span>—</span>
                        </div>
                        <div class="dash-row2">
                            <span class="dash-muted">روند ۷ روز:</span>
                            <span class="trend-up">↑ رو به بهبود</span>
                        </div>
                    </div>
                    <a href="#" class="dash-card-cta-link">رفتن به کارنامه و تحلیل →</a>
                </div>
            </div>
        </div>
    </section>
@endsection
