<section id="samples">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">نمونه سوال رایگان</h2>
            <p class="section-sub">قبل از خرید، یک آزمون نمونه را همین حالا امتحان کنید.</p>
        </div>

        <div class="row g-4">
            {{-- sample 1 --}}
            <div class="col-md-4">
                <div class="sample-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="fw-bold mb-0">نمونه آزمون تقویتی</h5>
                        <span class="badge text-bg-primary">سطح ۱</span>
                    </div>
                    <div class="sample-meta mb-3">
                        <span><i class="fa-regular fa-clock"></i> 15 دقیقه</span>
                        <span><i class="fa-solid fa-list-check"></i> 10 سوال</span>
                        <span><i class="fa-solid fa-signal"></i> متوسط</span>
                    </div>
                    <p class="text-muted small">
                        مناسب دانش‌آموزان پایه‌های مختلف برای آشنایی با سبک سوالات و گزارش‌ها.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ asset('assets/samples/taghviyati-sample.pdf') }}"
                            class="btn btn-outline-primary w-50">
                            دانلود PDF
                        </a>
                        <button class="btn btn-primary w-50" data-bs-toggle="modal" data-bs-target="#authModal">
                            شروع آنلاین
                        </button>
                    </div>
                </div>
            </div>

            {{-- sample 2 --}}
            <div class="col-md-4">
                <div class="sample-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="fw-bold mb-0">نمونه آزمون کنکور</h5>
                        <span class="badge text-bg-success">سطح ۲</span>
                    </div>
                    <div class="sample-meta mb-3">
                        <span><i class="fa-regular fa-clock"></i> 20 دقیقه</span>
                        <span><i class="fa-solid fa-list-check"></i> 15 سوال</span>
                        <span><i class="fa-solid fa-signal"></i> سخت</span>
                    </div>
                    <p class="text-muted small">
                        شبیه‌سازی کوتاه از آزمون کنکور همراه با رتبه و تحلیل درصدی.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ asset('assets/samples/konkur-sample.pdf') }}" class="btn btn-outline-success w-50">
                            دانلود PDF
                        </a>
                        <button class="btn btn-success w-50" data-bs-toggle="modal" data-bs-target="#authModal">
                            شروع آنلاین
                        </button>
                    </div>
                </div>
            </div>

            {{-- sample 3 --}}
            <div class="col-md-4">
                <div class="sample-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="fw-bold mb-0">نمونه آزمون المپیاد</h5>
                        <span class="badge text-bg-warning">سطح ۳</span>
                    </div>
                    <div class="sample-meta mb-3">
                        <span><i class="fa-regular fa-clock"></i> 25 دقیقه</span>
                        <span><i class="fa-solid fa-list-check"></i> 8 سوال</span>
                        <span><i class="fa-solid fa-signal"></i> خیلی سخت</span>
                    </div>
                    <p class="text-muted small">
                        مناسب دانش‌آموزان مستعد برای آشنایی با سوالات ترکیبی و تحلیل مهارتی.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ asset('assets/samples/olympiad-sample.pdf') }}"
                            class="btn btn-outline-warning w-50">
                            دانلود PDF
                        </a>
                        <button class="btn btn-warning w-50" data-bs-toggle="modal" data-bs-target="#authModal">
                            شروع آنلاین
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <div class="alert alert-light border small d-inline-block">
                برای شروع آنلاین نمونه‌ها کافیست ثبت‌نام کنید. (کمتر از ۱ دقیقه)
            </div>
        </div>
    </div>
</section>
