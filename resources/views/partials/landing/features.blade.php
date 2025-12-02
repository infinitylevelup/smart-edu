<section id="features">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">چرا این سامانه تفاوت ایجاد می‌کند؟</h2>
            <p class="section-sub">هر جلسه آزمون، یک قدم واقعی به سمت پیشرفت است.</p>
        </div>

        <div class="row g-4">
            @php
                $features = [
                    [
                        'icon' => 'fa-brain',
                        'title' => 'تحلیل هوشمند نتایج',
                        'text' =>
                            'سامانه به‌صورت خودکار مبحث‌های ضعیف و قوی را تشخیص می‌دهد، و پیشنهاد تمرین‌ها و آزمون‌های بعدی را دقیق‌تر می‌کند.',
                    ],
                    [
                        'icon' => 'fa-route',
                        'title' => 'مسیر یادگیری شخصی',
                        'text' =>
                            'با توجه به سطح و هدف شما (تقویتی/کنکور/المپیاد)، برنامه آزمون و تمرین اختصاصی می‌سازیم.',
                    ],
                    [
                        'icon' => 'fa-chart-line',
                        'title' => 'گزارش پیشرفت دقیق',
                        'text' =>
                            'نمودار رشد، رتبه در کلاس، مقایسه با میانگین، و روند بهبود در هر درس را شفاف می‌بینید.',
                    ],
                    [
                        'icon' => 'fa-clock',
                        'title' => 'آزمون‌های زمان‌بندی‌شده',
                        'text' => 'شبیه‌سازی کامل فضای آزمون واقعی با تایمر، کنترل تقلب و ثبت پاسخ‌ها.',
                    ],
                    [
                        'icon' => 'fa-chalkboard-teacher',
                        'title' => 'پنل ویژه معلمان',
                        'text' => 'ساخت آزمون، گروه‌بندی کلاس‌ها، مشاهده عملکرد دانش‌آموزان و ارسال تکلیف.',
                    ],
                    [
                        'icon' => 'fa-medal',
                        'title' => 'مناسب اهداف رقابتی',
                        'text' => 'برای کنکور و المپیاد، سوالات استاندارد شده و با تحلیل عمیق‌تر ارائه می‌شوند.',
                    ],
                ];
            @endphp

            @foreach ($features as $f)
                <div class="col-md-4">
                    <div class="feature card p-4">
                        <div class="icon mb-3"><i class="fas {{ $f['icon'] }}"></i></div>
                        <h5 class="fw-bold">{{ $f['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $f['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
