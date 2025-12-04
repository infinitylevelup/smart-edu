@extends('layouts.app')
@section('title', 'پشتیبانی - SmartEdu')

@push('styles')
    <style>
        /* تم رنگ هماهنگ با SmartEdu */
        :root {
            --primary: #7B68EE;
            --primary-light: rgba(123, 104, 238, 0.1);
            --primary-gradient: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --secondary: #FF6B9D;
            --secondary-light: rgba(255, 107, 157, 0.1);
            --accent: #00D4AA;
            --accent-light: rgba(0, 212, 170, 0.1);
            --gold: #FFD166;
            --light: #ffffff;
            --dark: #2D3047;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        * {
            font-family: 'Vazirmatn', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .support-page {
            animation: fadeIn 0.6s ease both;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 15px 100px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(50px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* هدر صفحه */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            animation: slideInRight 0.5s ease-out;
        }

        .page-title-section h1 {
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .page-title-section h1 i {
            color: var(--primary);
            background: var(--primary-light);
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce 2s infinite;
        }

        .page-subtitle {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 600px;
        }

        /* دکمه بازگشت */
        .btn-outline-custom {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--primary);
            padding: 12px 24px;
            border-radius: var(--radius-lg);
            font-weight: 700;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-custom:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            color: var(--dark);
            box-shadow: var(--shadow-md);
        }

        /* کارت اصلی */
        .main-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        /* آلرت‌ها */
        .alert-custom {
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            border: none;
            box-shadow: var(--shadow-sm);
            margin-bottom: 25px;
            animation: slideInRight 0.6s ease-out;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
            border-right: 4px solid var(--accent);
            color: var(--dark);
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.1), rgba(255, 107, 157, 0.05));
            border-right: 4px solid var(--secondary);
            color: var(--dark);
        }

        .alert-info-custom {
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.1), rgba(123, 104, 238, 0.05));
            border-right: 4px solid var(--primary);
            color: var(--dark);
            margin-top: 25px;
            animation: pulse 2s infinite;
        }

        /* توضیحات راهنما */
        .help-text {
            color: var(--gray);
            font-size: 1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            border: 2px dashed rgba(123, 104, 238, 0.2);
            position: relative;
            z-index: 2;
        }

        /* کارت‌های پشتیبانی */
        .support-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px;
            box-shadow: var(--shadow-md);
            border: 2px solid rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
        }

        .support-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .support-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .support-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .support-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .support-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .support-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: 3px solid rgba(123, 104, 238, 0.2);
            transition: all 0.3s;
            animation: float 3s ease-in-out infinite;
        }

        .support-card:hover .support-icon {
            transform: scale(1.1) rotate(10deg);
            background: var(--gradient-1);
            color: white;
        }

        .support-title {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--dark);
            margin: 0;
        }

        .support-description {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
            min-height: 80px;
        }

        .support-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .action-btn {
            padding: 15px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .action-btn:active {
            transform: scale(0.97);
        }

        .btn-primary-custom {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 6px 16px rgba(123, 104, 238, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.4);
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.6s;
        }

        .btn-primary-custom:hover::before {
            right: 100%;
        }

        .btn-outline-secondary-custom {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-secondary-custom:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            color: var(--dark);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-success-custom {
            background: transparent;
            color: #16a34a;
            border: 2px solid #16a34a;
            box-shadow: var(--shadow-sm);
        }

        .btn-outline-success-custom:hover {
            background: rgba(22, 163, 74, 0.1);
            transform: translateY(-3px);
            color: #16a34a;
            box-shadow: var(--shadow-md);
        }

        /* وضعیت غیرفعال */
        .coming-soon {
            position: relative;
            opacity: 0.8;
        }

        .coming-soon::after {
            content: 'به زودی';
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--gradient-1);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            z-index: 3;
            animation: pulse 1.5s infinite;
        }

        /* بخش FAQ */
        .faq-section {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px dashed var(--primary-light);
        }

        .faq-title {
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .faq-item {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            margin-bottom: 15px;
            border: 2px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
        }

        .faq-item:hover {
            border-color: var(--primary-light);
            transform: translateX(-5px);
        }

        .faq-question {
            font-weight: 700;
            color: var(--dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .faq-answer {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-top: 10px;
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        /* بهینه‌سازی موبایل */
        @media (max-width: 768px) {
            .support-page {
                padding: 15px 10px 90px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .page-title-section h1 {
                font-size: 1.5rem;
            }

            .page-title-section h1 i {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .main-card {
                padding: 20px;
            }

            .support-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .support-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }

            .support-description {
                min-height: auto;
            }

            .action-btn {
                padding: 12px;
                font-size: 0.9rem;
            }

            .faq-item {
                padding: 15px;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .action-btn,
        .btn-outline-custom {
            min-height: 44px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    @php
        $dashboardRoute = route('student.index');
    @endphp

    <div class="support-page">
        {{-- ================= HEADER ================= --}}
        <div class="page-header">
            <div class="page-title-section">
                <h1>
                    <i class="fas fa-headset"></i>
                    پشتیبانی دانش‌آموز
                </h1>
                <p class="page-subtitle">
                    اگر مشکلی در آزمون‌ها، کلاس‌ها یا حساب کاربری داشتی، از اینجا با ما در ارتباط باش.
                    تیم پشتیبانی همیشه کنارته! 🤝
                </p>
            </div>

            <a href="{{ $dashboardRoute }}" class="btn-outline-custom">
                <i class="fas fa-home"></i>
                داشبورد
            </a>
        </div>

        {{-- ================= MAIN CARD ================= --}}
        <div class="main-card">
            {{-- آلرت‌ها --}}
            @if (session('success'))
                <div class="alert-custom alert-success-custom d-flex align-items-center gap-3">
                    <div style="font-size: 1.5rem; color: var(--accent);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-custom alert-danger-custom d-flex align-items-center gap-3">
                    <div style="font-size: 1.5rem; color: var(--secondary);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- توضیحات راهنما --}}
            <div class="help-text">
                <div class="d-flex align-items-start gap-3">
                    <div style="font-size: 1.8rem; color: var(--primary);">💡</div>
                    <div>
                        تیم پشتیبانی تلاش می‌کند در سریع‌ترین زمان پاسخگو باشد.
                        لطفاً هنگام ارسال پیام یا تیکت، موضوع و توضیح مشکل را دقیق بنویس تا بتونیم بهتر کمکت کنیم.
                        معمولاً ظرف ۲۴ ساعت پاسخ می‌دیم! ⏰
                    </div>
                </div>
            </div>

            {{-- کارت‌های پشتیبانی --}}
            <div class="row g-4">
                {{-- کارت تیکت --}}
                <div class="col-12 col-md-6">
                    <div class="support-card coming-soon">
                        <div class="support-header">
                            <div class="support-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div>
                                <h3 class="support-title">ارسال تیکت</h3>
                            </div>
                        </div>

                        <p class="support-description">
                            مشکلت رو به صورت تیکت ثبت کن تا تیم پشتیبانی بررسیش کنه.
                            می‌تونی وضعیت تیکتت رو پیگیری کنی و پاسخ رو در همین صفحه ببینی.
                            سیستم تیکت بهترین راه برای مشکلات پیچیده‌تره.
                        </p>

                        <div class="support-actions">
                            <button class="action-btn btn-primary-custom" onclick="showComingSoonModal('سیستم تیکت')">
                                <i class="fas fa-plus-circle"></i>
                                ایجاد تیکت جدید
                            </button>
                        </div>
                    </div>
                </div>

                {{-- کارت تماس مستقیم --}}
                <div class="col-12 col-md-6">
                    <div class="support-card coming-soon">
                        <div class="support-header">
                            <div class="support-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h3 class="support-title">تماس مستقیم</h3>
                            </div>
                        </div>

                        <p class="support-description">
                            برای مشکلات فوری و سوالات سریع می‌تونی مستقیماً با ما تماس بگیری.
                            شماره پشتیبانی و واتساپ به زودی اینجا قرار می‌گیره.
                            ساعات پاسخگویی: شنبه تا چهارشنبه، ۹ صبح تا ۵ بعدازظهر 🕔
                        </p>

                        <div class="support-actions">
                            <button class="action-btn btn-outline-secondary-custom"
                                onclick="showComingSoonModal('تماس تلفنی')">
                                <i class="fas fa-phone"></i>
                                تماس تلفنی
                            </button>
                            <button class="action-btn btn-outline-success-custom"
                                onclick="showComingSoonModal('واتساپ پشتیبانی')">
                                <i class="fab fa-whatsapp"></i>
                                واتساپ پشتیبانی
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- آلرت اطلاعاتی --}}
            <div class="alert-info-custom d-flex align-items-center gap-3">
                <div style="font-size: 1.5rem; color: var(--primary);">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="flex-grow-1">
                    فعلاً این صفحه نمونه است و سیستم کامل پشتیبانی به زودی راه‌اندازی می‌شه.
                    در این مدت، از طریق ایمیل یا پیام به معلم می‌تونی مشکلاتت رو مطرح کنی. 🚀
                </div>
            </div>

            {{-- ================= FAQ SECTION ================= --}}
            <div class="faq-section">
                <h3 class="faq-title">
                    <i class="fas fa-question-circle" style="color: var(--primary);"></i>
                    سوالات متداول
                </h3>

                <div class="faq-list">
                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div class="faq-question">
                            <span>چقدر طول می‌کشه تا پاسخ تیکت رو بگیرم؟</span>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                        <div class="faq-answer">
                            معمولاً ظرف ۲۴ ساعت کاری پاسخ می‌دیم. اگر مشکل فوری باشه، سعی می‌کنیم سریع‌تر پاسخ بدیم.
                        </div>
                    </div>

                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div class="faq-question">
                            <span>برای مشکلات فنی آزمون چیکار کنم؟</span>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                        <div class="faq-answer">
                            اول مرورگرت رو رفرش کن. اگر مشکل ادامه داشت، از تیکت استفاده کن و عکس از خطا برامون بفرست.
                        </div>
                    </div>

                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div class="faq-question">
                            <span>چطور می‌تونم به کلاس جدید اضافه بشم؟</span>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                        <div class="faq-answer">
                            کد کلاس رو از معلمت بگیر و در صفحه "کلاس‌های من" از گزینه "عضویت با کد کلاس" استفاده کن.
                        </div>
                    </div>

                    <div class="faq-item" onclick="toggleFaq(this)">
                        <div class="faq-question">
                            <span>اگر نتایج آزمون رو نبینم چطور؟</span>
                            <i class="fas fa-chevron-down" style="color: var(--primary);"></i>
                        </div>
                        <div class="faq-answer">
                            نتایج معمولاً بلافاصله بعد از اتمام آزمون نمایش داده می‌شن. اگر دیدی، چند دقیقه صبر کن و دوباره
                            صفحه رو رفرش کن.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // لرزش موبایل برای تعاملات
                if (navigator.vibrate) {
                    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate ||
                        navigator.msVibrate;
                }

                // انیمیشن‌های کارت‌ها
                const supportCards = document.querySelectorAll('.support-card');
                supportCards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        if (navigator.vibrate) {
                            navigator.vibrate(20);
                        }
                    });

                    card.addEventListener('click', function(e) {
                        if (!e.target.closest('.action-btn')) {
                            // افکت کلیک روی کل کارت
                            this.style.transform = 'scale(0.98)';
                            setTimeout(() => {
                                this.style.transform = '';
                            }, 150);

                            if (navigator.vibrate) {
                                navigator.vibrate(30);
                            }
                        }
                    });
                });

                // دکمه‌های action
                const actionButtons = document.querySelectorAll('.action-btn');
                actionButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                });

                // دکمه بازگشت
                const backButton = document.querySelector('.btn-outline-custom');
                if (backButton) {
                    backButton.addEventListener('click', function(e) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);

                        if (navigator.vibrate) {
                            navigator.vibrate(30);
                        }
                    });
                }
            });

            // تابع نمایش مودال "به زودی"
            function showComingSoonModal(feature) {
                const modal = document.createElement('div');
                modal.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        z-index: 1000;
        text-align: center;
        max-width: 350px;
        width: 85%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

                modal.innerHTML = `
        <div style="font-size: 3rem; margin-bottom: 20px; color: var(--primary);">
            <i class="fas fa-tools"></i>
        </div>
        <h3 style="margin-bottom: 15px; color: var(--dark); font-size: 1.3rem; font-weight: 700;">${feature}</h3>
        <p style="color: var(--gray); margin-bottom: 25px; font-size: 1rem; line-height: 1.6;">
            این قابلیت در حال توسعه است و به زودی در دسترس قرار خواهد گرفت.
            در این مدت می‌تونی از طریق ایمیل با ما در ارتباط باشی.
        </p>
        <div style="display: flex; gap: 10px;">
            <button onclick="this.parentElement.parentElement.remove(); if (this.parentElement.parentElement.nextElementSibling) this.parentElement.parentElement.nextElementSibling.remove();"
                    style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
                باشه
            </button>
            <button onclick="this.parentElement.parentElement.remove(); if (this.parentElement.parentElement.nextElementSibling) this.parentElement.parentElement.nextElementSibling.remove(); window.location.href='mailto:support@s-mart-edu.ir';"
                    style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
                ارسال ایمیل
            </button>
        </div>
    `;

                document.body.appendChild(modal);

                const overlay = document.createElement('div');
                overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease;
    `;
                document.body.appendChild(overlay);

                // لرزش موبایل
                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }

                // حذف خودکار بعد از 5 ثانیه
                setTimeout(() => {
                    if (document.body.contains(modal)) {
                        modal.remove();
                        overlay.remove();
                    }
                }, 5000);
            }

            // تابع toggle FAQ
            function toggleFaq(element) {
                element.classList.toggle('active');

                // لرزش موبایل
                if (navigator.vibrate) {
                    navigator.vibrate(30);
                }

                // بستن بقیه FAQ‌ها
                if (element.classList.contains('active')) {
                    document.querySelectorAll('.faq-item').forEach(item => {
                        if (item !== element) {
                            item.classList.remove('active');
                        }
                    });
                }
            }

            // اضافه کردن استایل‌های انیمیشن
            const style = document.createElement('style');
            style.textContent = `
    @keyframes scaleIn {
        from { transform: translate(-50%, -50%) scale(0.9); opacity: 0; }
        to { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
            document.head.appendChild(style);
        </script>
    @endpush
@endsection
