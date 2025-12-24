@extends('layouts.student-app')

@section('title', 'مسیر یادگیری من')

@section('content')
    <section id="learning-path" class="mobile-section active">
        <div class="lp-wrap">

            <div class="lp-header">
                <h3 class="mb-1">مسیر یادگیری من</h3>
                <div class="lp-subtitle">قدم به قدم تا پیشرفت واقعی — پیشنهادها بر اساس عملکردت</div>
            </div>

            {{-- Summary / KPIs --}}
            <div class="lp-kpi-grid">
                <div class="lp-card">
                    <div class="lp-title">سطح فعلی</div>
                    <div class="lp-kpi">
                        <span class="lp-kpi-value">—</span>
                        <span class="lp-kpi-unit">سطح</span>
                    </div>
                    <div class="lp-note">براساس آخرین فعالیت‌ها</div>
                </div>

                <div class="lp-card lp-card-accent">
                    <div class="lp-title">تمرین این هفته</div>
                    <div class="lp-kpi">
                        <span class="lp-kpi-value">—</span>
                        <span class="lp-kpi-unit">مورد</span>
                    </div>
                    <div class="lp-note">تعداد تمرین‌های انجام شده</div>
                </div>

                <div class="lp-card">
                    <div class="lp-title">هدف هفتگی</div>
                    <div class="lp-kpi">
                        <span class="lp-kpi-value">—</span>
                        <span class="lp-kpi-unit">٪</span>
                    </div>
                    <div class="lp-note">پیشرفت به سمت هدف</div>
                </div>

                <div class="lp-card">
                    <div class="lp-title">روند پیشرفت</div>
                    <div class="lp-kpi">
                        <span class="lp-kpi-value lp-trend-up">↑</span>
                        <span class="lp-kpi-unit">رو به بهبود</span>
                    </div>
                    <div class="lp-note">۷ روز اخیر</div>
                </div>
            </div>

            {{-- Recommended next steps --}}
            <div class="lp-section">
                <div class="lp-section-head">
                    <h5 class="mb-0">پیشنهاد مرحله بعد</h5>
                    <a class="lp-link" href="{{ route('student.exams.public') }}">شروع آزمون جدید →</a>
                </div>

                <div class="lp-reco-grid">
                    <div class="lp-card lp-reco">
                        <div class="lp-reco-title">۱) یک آزمون کوتاه بزن</div>
                        <div class="lp-reco-text">
                            یک آزمون رایگان کوتاه انتخاب کن تا سیستم مسیرت رو دقیق‌تر پیشنهاد بده.
                        </div>
                        <a class="btn btn-success lp-btn" href="{{ route('student.exams.public') }}">
                            رفتن به شروع آزمون ⭐
                        </a>
                    </div>

                    <div class="lp-card lp-reco">
                        <div class="lp-reco-title">۲) تحلیل آزمون قبلی</div>
                        <div class="lp-reco-text">
                            اگر اخیراً آزمون دادی، وارد تحلیل شو تا نقاط ضعف و قوت مشخص بشه.
                        </div>
                        <a class="btn btn-outline-primary lp-btn" href="{{ route('student.reports.index') }}">
                            رفتن به کارنامه و تحلیل
                        </a>
                    </div>
                </div>
            </div>

            {{-- Path blocks --}}
            <div class="lp-section">
                <div class="lp-section-head">
                    <h5 class="mb-0">مسیر پیشنهادی (Placeholder)</h5>
                    <span class="lp-muted">به‌زودی داده واقعی وصل می‌شود</span>
                </div>

                <div class="lp-path-grid">
                    <div class="lp-card lp-path">
                        <div class="lp-path-head">
                            <span class="lp-badge lp-badge-ok">قوی</span>
                            <div class="lp-path-title">ریاضی — مبحث: تابع</div>
                        </div>

                        <div class="lp-progress">
                            <div class="lp-progress-bar" style="width: 78%"></div>
                        </div>
                        <div class="lp-progress-meta">
                            <span class="lp-muted">پیشرفت</span>
                            <strong>۷۸٪</strong>
                        </div>

                        <div class="lp-actions">
                            <button class="btn btn-outline-success btn-sm" type="button" disabled>تمرین</button>
                            <button class="btn btn-outline-primary btn-sm" type="button" disabled>آزمونک</button>
                        </div>
                    </div>

                    <div class="lp-card lp-path">
                        <div class="lp-path-head">
                            <span class="lp-badge lp-badge-warn">نیاز به کار</span>
                            <div class="lp-path-title">فیزیک — مبحث: حرکت‌شناسی</div>
                        </div>

                        <div class="lp-progress">
                            <div class="lp-progress-bar" style="width: 42%"></div>
                        </div>
                        <div class="lp-progress-meta">
                            <span class="lp-muted">پیشرفت</span>
                            <strong>۴۲٪</strong>
                        </div>

                        <div class="lp-actions">
                            <button class="btn btn-success btn-sm" type="button" disabled>شروع یادگیری</button>
                            <button class="btn btn-outline-primary btn-sm" type="button" disabled>دیدن تحلیل</button>
                        </div>
                    </div>

                    <div class="lp-card lp-path">
                        <div class="lp-path-head">
                            <span class="lp-badge lp-badge-mid">متوسط</span>
                            <div class="lp-path-title">شیمی — مبحث: استوکیومتری</div>
                        </div>

                        <div class="lp-progress">
                            <div class="lp-progress-bar" style="width: 61%"></div>
                        </div>
                        <div class="lp-progress-meta">
                            <span class="lp-muted">پیشرفت</span>
                            <strong>۶۱٪</strong>
                        </div>

                        <div class="lp-actions">
                            <button class="btn btn-outline-success btn-sm" type="button" disabled>تمرین</button>
                            <button class="btn btn-outline-primary btn-sm" type="button" disabled>آزمونک</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
