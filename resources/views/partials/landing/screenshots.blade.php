<section id="screenshots" class="bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">تصاویر واقعی پنل‌ها</h2>
            <p class="section-sub">یک نگاه سریع به تجربه‌ی دانش‌آموز و معلم</p>
        </div>

        <div class="carousel-card">
            <div id="panelCarousel" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#panelCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#panelCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#panelCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#panelCarousel" data-bs-slide-to="3"></button>
                </div>

                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="carousel-img"
                            src="{{ asset('assets/images/landing/screenshots/student-dashboard.png') }}"
                            alt="پنل دانش‌آموز">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">پنل دانش‌آموز</h5>
                            <p class="small">دسترسی به آزمون‌ها + گزارش پیشرفت + پیشنهاد تمرین</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img class="carousel-img"
                            src="{{ asset('assets/images/landing/screenshots/student-report.png') }}"
                            alt="گزارش آزمون دانش‌آموز">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">گزارش هوشمند آزمون</h5>
                            <p class="small">تحلیل نقاط ضعف و قوت به‌صورت خودکار</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img class="carousel-img"
                            src="{{ asset('assets/images/landing/screenshots/teacher-panel.png') }}" alt="پنل معلم">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">پنل معلم</h5>
                            <p class="small">ساخت آزمون، مدیریت کلاس و بررسی عملکرد هر دانش‌آموز</p>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img class="carousel-img"
                            src="{{ asset('assets/images/landing/screenshots/class-analytics.png') }}" alt="تحلیل کلاس">
                        <div class="carousel-caption d-none d-md-block">
                            <h5 class="fw-bold">تحلیل عملکرد کلاس</h5>
                            <p class="small">نمودار رشد، میانگین کلاس و رتبه‌بندی</p>
                        </div>
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#panelCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">قبلی</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#panelCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">بعدی</span>
                </button>

            </div>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#authModal">
                ورود و تجربه پنل‌ها
            </button>
        </div>
    </div>
</section>
