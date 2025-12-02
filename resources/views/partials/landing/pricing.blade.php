<section id="pricing">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">پلن‌های دسترسی و هزینه</h2>
            <p class="section-sub">پلن خود را هر زمان خواستید ارتقا دهید.</p>
        </div>

        <div
            class="discount-banner mb-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div>
                <div class="fw-bold fs-5">
                    <i class="fa-solid fa-bolt text-danger me-1"></i>
                    تخفیف ویژه شروع سال تحصیلی
                </div>
                <div class="text-muted small">
                    تا پایان شمارش معکوس، پلن‌ها با <b>۱۵٪ تخفیف</b> قابل خرید هستند.
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">زمان باقی‌مانده:</span>
                <div class="countdown" id="discountCountdown">--:--:--</div>
                <span class="badge text-bg-danger" id="discountStatus">فعال</span>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-2 mb-5 cycle-selector">
            <button class="btn btn-outline-primary active" data-cycle="monthly"
                onclick="setCycle('monthly', this)">ماهانه</button>
            <button class="btn btn-outline-primary" data-cycle="quarterly" onclick="setCycle('quarterly', this)">سه‌ماهه
                (10٪ تخفیف)</button>
            <button class="btn btn-outline-primary" data-cycle="yearly" onclick="setCycle('yearly', this)">سالیانه (25٪
                تخفیف)</button>
        </div>

        <div class="row g-4 align-items-stretch">
            {{-- Free --}}
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card h-100">
                    <h5 class="fw-bold mb-1">رایگان</h5>
                    <div class="text-muted small mb-3">شروع بدون پرداخت</div>
                    <div class="price mb-3">۰ <small>تومان / همیشه</small></div>

                    <ul class="list-unstyled list-check text-muted small">
                        <li>۳ آزمون تقویتی در هفته</li>
                        <li>۲ آزمون کنکور در هفته</li>
                        <li>۱ آزمون المپیاد در هفته</li>
                        <li>تحلیل پایه نتایج</li>
                        <li class="muted">مسیر یادگیری اختصاصی</li>
                        <li class="muted">گزارش پیشرفت حرفه‌ای</li>
                    </ul>

                    <button class="btn btn-outline-primary w-100 mt-3" data-bs-toggle="modal"
                        data-bs-target="#authModal">
                        شروع رایگان
                    </button>
                </div>
            </div>

            {{-- Plan 1 --}}
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card h-100">
                    <h5 class="fw-bold mb-1">پلن اول</h5>
                    <div class="text-muted small mb-3">اقتصادی برای تقویتی</div>

                    <div class="price mb-1">
                        <span class="price-val" data-monthly="99000" data-quarterly="267000"
                            data-yearly="891000">99,000</span>
                        <small>تومان</small>
                    </div>
                    <div class="text-muted small mb-3 price-label">ماهانه</div>

                    <div class="alert alert-light small py-2">
                        فقط سطح <b>تقویتی</b> فعال می‌شود.
                    </div>

                    <ul class="list-unstyled list-check text-muted small">
                        <li>تقویتی نامحدود</li>
                        <li>تحلیل هوشمند کامل</li>
                        <li>پیشنهاد تمرین خودکار</li>
                        <li class="muted">کنکور</li>
                        <li class="muted">المپیاد</li>
                    </ul>

                    <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#authModal">
                        خرید پلن اول
                    </button>
                </div>
            </div>

            {{-- Plan 2 --}}
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card featured h-100">
                    <div class="pricing-badge">پیشنهادی</div>
                    <h5 class="fw-bold mb-1">پلن دوم</h5>
                    <div class="text-muted small mb-3">برای مسیر کنکور</div>

                    <div class="price mb-1">
                        <span class="price-val" data-monthly="169000" data-quarterly="456000"
                            data-yearly="1521000">169,000</span>
                        <small>تومان</small>
                    </div>
                    <div class="text-muted small mb-3 price-label">ماهانه</div>

                    <div class="alert alert-primary small py-2">
                        سطح‌های <b>تقویتی + کنکور</b> فعال می‌شوند.
                    </div>

                    <ul class="list-unstyled list-check text-muted small">
                        <li>تقویتی نامحدود</li>
                        <li>کنکور نامحدود</li>
                        <li>گزارش رتبه و تراز</li>
                        <li>مسیر یادگیری کنکوری</li>
                        <li class="muted">المپیاد</li>
                    </ul>

                    <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#authModal">
                        خرید پلن دوم
                    </button>
                </div>
            </div>

            {{-- Plan 3 --}}
            <div class="col-md-6 col-lg-3">
                <div class="pricing-card h-100">
                    <h5 class="fw-bold mb-1">پلن سوم</h5>
                    <div class="text-muted small mb-3">کامل و حرفه‌ای</div>

                    <div class="price mb-1">
                        <span class="price-val" data-monthly="249000" data-quarterly="672000"
                            data-yearly="2241000">249,000</span>
                        <small>تومان</small>
                    </div>
                    <div class="text-muted small mb-3 price-label">ماهانه</div>

                    <div class="alert alert-warning small py-2">
                        هر سه سطح <b>تقویتی + کنکور + المپیاد</b> فعال می‌شوند.
                    </div>

                    <ul class="list-unstyled list-check text-muted small">
                        <li>تقویتی نامحدود</li>
                        <li>کنکور نامحدود</li>
                        <li>المپیاد نامحدود</li>
                        <li>تحلیل پیشرفته مهارتی</li>
                        <li>مسیر ویژه المپیاد</li>
                    </ul>

                    <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#authModal">
                        خرید پلن سوم
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center text-muted small mt-4">
            * قیمت‌ها نمونه هستند و از داخل HTML قابل تغییرند.
        </div>
    </div>
</section>
