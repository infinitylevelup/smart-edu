@extends('layouts.student-app')

@section('title', 'کارنامه و تحلیل')

@section('content')
    <section id="reports" class="mobile-section active">
        <div class="reports-wrap">

            <div class="reports-header">
                <h3 class="mb-1">کارنامه و تحلیل</h3>
                <div class="reports-subtitle">خلاصه عملکرد + دسترسی سریع به تحلیل هر آزمون</div>
            </div>

            {{-- KPI cards --}}
            <div class="reports-kpi-grid">
                <div class="rep-card">
                    <div class="rep-title">میانگین کل</div>
                    <div class="rep-kpi">
                        <span class="rep-kpi-value">—</span>
                        <span class="rep-kpi-unit">٪</span>
                    </div>
                    <div class="rep-note">براساس آخرین آزمون‌ها</div>
                </div>

                <div class="rep-card rep-card-accent">
                    <div class="rep-title">تعداد آزمون‌ها</div>
                    <div class="rep-kpi">
                        <span class="rep-kpi-value">—</span>
                        <span class="rep-kpi-unit">مورد</span>
                    </div>
                    <div class="rep-note">کل آزمون‌های ثبت‌شده</div>
                </div>

                <div class="rep-card">
                    <div class="rep-title">بهترین درس</div>
                    <div class="rep-kpi">
                        <span class="rep-kpi-value">—</span>
                        <span class="rep-kpi-unit">٪</span>
                    </div>
                    <div class="rep-note">درس: <strong>—</strong></div>
                </div>

                <div class="rep-card">
                    <div class="rep-title">روند ۷ روز</div>
                    <div class="rep-kpi">
                        <span class="rep-kpi-value rep-trend-up">↑</span>
                        <span class="rep-kpi-unit">رو به بهبود</span>
                    </div>
                    <div class="rep-note">براساس آخرین فعالیت‌ها</div>
                </div>
            </div>

            {{-- Latest attempts --}}
            <div class="rep-section">
                <div class="rep-section-head">
                    <h5 class="mb-0">آخرین آزمون‌ها</h5>
                    <a class="rep-link" href="{{ route('student.exams.index') }}">رفتن به لیست آزمون‌ها →</a>
                </div>

                <div class="rep-list">
                    {{-- Placeholder row --}}
                    <div class="rep-item">
                        <div class="rep-dot rep-dot-ok"></div>

                        <div class="rep-item-main">
                            <div class="rep-item-title">عنوان آزمون: —</div>
                            <div class="rep-item-meta">
                                <span class="rep-muted">تاریخ:</span> —
                                <span class="rep-sep">•</span>
                                <span class="rep-muted">امتیاز:</span> —٪
                                <span class="rep-sep">•</span>
                                <span class="rep-muted">زمان:</span> —
                            </div>
                        </div>

                        <div class="rep-item-actions">
                            <button class="btn btn-outline-primary btn-sm" type="button" disabled>
                                مشاهده نتیجه
                            </button>
                            <button class="btn btn-primary btn-sm" type="button" disabled>
                                تحلیل
                            </button>
                        </div>
                    </div>

                    <div class="rep-empty">
                        وقتی در آزمون‌ها شرکت کنی، اینجا «لیست آزمون‌ها + دکمه نتیجه/تحلیل» نمایش داده می‌شود.
                    </div>
                </div>
            </div>

            {{-- Tips / CTA --}}
            <div class="rep-section">
                <div class="rep-tip-card">
                    <div class="rep-tip-title">پیشنهاد هوشمند</div>
                    <div class="rep-tip-text">
                        بعد از هر آزمون، حتماً وارد «تحلیل» شو تا نقاط ضعف و قوتت مشخص بشه.
                    </div>
                    <div class="rep-tip-actions">
                        <a href="{{ route('student.exams.public') }}" class="btn btn-success rep-btn">
                            شروع آزمون جدید ⭐
                        </a>
                        <a href="{{ route('student.learning-path') }}" class="btn btn-outline-success rep-btn">
                            رفتن به مسیر یادگیری
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
