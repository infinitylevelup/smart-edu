@extends('layouts.app')
@section('title', 'گزارش‌های تحلیلی - پنل معلم')

@push('styles')
    <style>
        /* تم کامل SmartEdu */
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
            --dark-light: #3A3F6D;
            --gray: #8A8D9B;
            --light-gray: #F8F9FF;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.16);
            --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.2);
            --gradient-1: linear-gradient(135deg, #7B68EE, #FF6B9D);
            --gradient-2: linear-gradient(135deg, #00D4AA, #4361EE);
            --gradient-3: linear-gradient(135deg, #FFD166, #FF9A3D);
            --gradient-4: linear-gradient(135deg, #7209B7, #3A0CA3);
            --radius-xl: 24px;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
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

        .reports-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 15px 80px;
            animation: fadeIn 0.6s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
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

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-15px) translateX(-10px);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        @keyframes progress-animation {
            from {
                width: 0%;
            }
        }

        /* ========== HERO HEADER ========== */
        .hero-section {
            background: linear-gradient(135deg,
                    rgba(123, 104, 238, 0.1) 0%,
                    rgba(255, 107, 157, 0.1) 50%,
                    rgba(0, 212, 170, 0.1) 100%);
            border-radius: var(--radius-xl);
            padding: 35px 40px;
            margin-bottom: 35px;
            border: 2px solid rgba(123, 104, 238, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(123, 104, 238, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 8s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.08), transparent 70%);
            border-radius: 50%;
            animation: floaty 10s ease-in-out infinite reverse;
        }

        .hero-content h1 {
            font-weight: 900;
            font-size: 2.2rem;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-content h1::before {
            content: '';
            width: 8px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .hero-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.8;
            max-width: 700px;
            margin: 0;
        }

        /* ========== DASHBOARD CARDS ========== */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .dashboard-card {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 2px solid transparent;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.08), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .card-title {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ========== PERFORMANCE METRICS ========== */
        .metric-item {
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .metric-name {
            font-weight: 800;
            font-size: 1rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .metric-value {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .metric-bar {
            height: 12px;
            background: var(--light-gray);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .metric-fill {
            height: 100%;
            border-radius: 10px;
            background: var(--gradient-1);
            width: 0;
            transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .metric-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        /* ========== QUICK INSIGHTS ========== */
        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .insight-card {
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .insight-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-5px);
            background: var(--light);
        }

        .insight-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .insight-value {
            font-size: 1.8rem;
            font-weight: 900;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
            line-height: 1;
        }

        .insight-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 700;
        }

        /* ========== RECOMMENDATIONS ========== */
        .recommendation-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: var(--light-gray);
            border-radius: var(--radius-lg);
            border-right: 4px solid var(--primary);
            transition: all 0.3s;
        }

        .recommendation-item:hover {
            background: var(--primary-light);
            transform: translateX(5px);
        }

        .recommendation-icon {
            color: var(--primary);
            font-size: 1.3rem;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .recommendation-text {
            flex: 1;
            color: var(--dark);
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* ========== STUDENT PROGRESS ========== */
        .progress-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .progress-table th {
            padding: 15px;
            text-align: right;
            font-weight: 900;
            color: var(--dark);
            font-size: 0.9rem;
            border-bottom: 2px solid var(--light-gray);
            background: rgba(123, 104, 238, 0.05);
        }

        .progress-table td {
            padding: 15px;
            text-align: right;
            font-weight: 700;
            color: var(--dark);
            border-bottom: 1px solid var(--light-gray);
        }

        .progress-table tr:hover {
            background: var(--primary-light);
        }

        .student-name-cell {
            font-weight: 900 !important;
            color: var(--dark);
        }

        .progress-cell {
            min-width: 150px;
        }

        .progress-bar-small {
            height: 8px;
            background: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill-small {
            height: 100%;
            border-radius: 4px;
            background: var(--gradient-2);
        }

        /* ========== CLASS COMPARISON ========== */
        .comparison-chart {
            height: 200px;
            display: flex;
            align-items: flex-end;
            gap: 15px;
            margin-top: 25px;
            padding: 20px;
            background: var(--light-gray);
            border-radius: var(--radius-lg);
        }

        .chart-bar {
            flex: 1;
            background: var(--gradient-1);
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
            position: relative;
            transition: all 0.3s;
            min-height: 20px;
        }

        .chart-bar:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .chart-label {
            position: absolute;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 900;
            color: var(--gray);
        }

        .chart-value {
            position: absolute;
            top: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.9rem;
            font-weight: 900;
            color: var(--dark);
        }

        /* ========== ACTION BUTTONS ========== */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 25px;
        }

        .btn-action {
            padding: 18px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .btn-action:active {
            transform: scale(0.97);
        }

        .btn-download {
            background: var(--gradient-1);
            color: white;
            box-shadow: 0 10px 25px rgba(123, 104, 238, 0.3);
        }

        .btn-download:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(123, 104, 238, 0.4);
        }

        .btn-download::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-download:hover::before {
            left: 100%;
        }

        .btn-export {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--primary);
        }

        .btn-export:hover {
            background: var(--primary-light);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .btn-share {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-share:hover {
            background: var(--light-gray);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .insights-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .reports-container {
                padding: 15px 10px 60px;
            }

            .hero-section {
                padding: 25px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .dashboard-card {
                padding: 25px;
            }

            .insights-grid {
                grid-template-columns: 1fr;
            }

            .progress-table {
                display: block;
                overflow-x: auto;
            }

            .comparison-chart {
                flex-direction: column;
                height: auto;
                align-items: stretch;
            }

            .chart-bar {
                height: 40px !important;
                border-radius: var(--radius-sm);
            }

            .chart-label {
                position: relative;
                bottom: 0;
                margin-top: 5px;
            }

            .chart-value {
                position: relative;
                top: 0;
                margin-bottom: 5px;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .card-badge {
                align-self: flex-start;
            }

            .recommendation-item {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-action {
            min-height: 48px;
        }

        /* انتخاب متن */
        ::selection {
            background: rgba(123, 104, 238, 0.2);
            color: var(--dark);
        }
    </style>
@endpush

@section('content')
    <div class="reports-container">
        {{-- ========== HERO SECTION ========== --}}
        <div class="hero-section">
            <div class="hero-content">
                <h1>
                    <span
                        style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        داشبورد گزارش‌های تحلیلی
                    </span>
                    📊
                </h1>
                <p class="hero-subtitle">
                    تحلیل جامع عملکرد کلاس‌ها و دانش‌آموزان شما. داده‌های هوشمند برای تصمیم‌گیری‌های آموزشی بهتر.
                </p>
            </div>
        </div>

        {{-- ========== DASHBOARD CARDS ========== --}}
        <div class="dashboard-cards">
            {{-- کارت تحلیل کلاسی --}}
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-chart-line"></i>
                        تحلیل کلاسی
                    </div>
                    <div class="card-badge">
                        <i class="fas fa-trend-up"></i>
                        روند مثبت
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <div class="metric-name">
                            <i class="fas fa-user-graduate"></i>
                            میانگین نمرات کلاس
                        </div>
                        <div class="metric-value">۸۲.۵%</div>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" data-width="82.5"></div>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <div class="metric-name">
                            <i class="fas fa-clipboard-check"></i>
                            نرخ تکمیل آزمون‌ها
                        </div>
                        <div class="metric-value">۹۴%</div>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" data-width="94"></div>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <div class="metric-name">
                            <i class="fas fa-clock"></i>
                            میانگین زمان پاسخگویی
                        </div>
                        <div class="metric-value">۲۴ دقیقه</div>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" data-width="75"></div>
                    </div>
                </div>
            </div>

            {{-- کارت بینش‌های سریع --}}
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-lightbulb"></i>
                        بینش‌های سریع
                    </div>
                    <div class="card-badge">
                        <i class="fas fa-bolt"></i>
                        لحظه‌ای
                    </div>
                </div>

                <div class="insights-grid">
                    <div class="insight-card">
                        <div class="insight-icon">📈</div>
                        <div class="insight-value">+۱۵%</div>
                        <div class="insight-label">رشد عملکرد</div>
                    </div>

                    <div class="insight-card">
                        <div class="insight-icon">🎯</div>
                        <div class="insight-value">۸۷%</div>
                        <div class="insight-label">تحقق اهداف</div>
                    </div>

                    <div class="insight-card">
                        <div class="insight-icon">⏱️</div>
                        <div class="insight-value">۳.۲</div>
                        <div class="insight-label">میانگین ساعت مطالعه</div>
                    </div>

                    <div class="insight-card">
                        <div class="insight-icon">⭐</div>
                        <div class="insight-value">۴.۸/۵</div>
                        <div class="insight-label">رضایت دانش‌آموزان</div>
                    </div>
                </div>
            </div>

            {{-- کارت توصیه‌های آموزشی --}}
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-handshake"></i>
                        توصیه‌های مشاوره‌ای
                    </div>
                    <div class="card-badge">
                        <i class="fas fa-star"></i>
                        پیشنهاد ویژه
                    </div>
                </div>

                <div class="recommendation-item">
                    <div class="recommendation-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="recommendation-text">
                        بر روی مباحث هندسه و مثلثات تمرکز بیشتری داشته باشید، ضعف عمومی در این بخش‌ها مشاهده می‌شود.
                    </div>
                </div>

                <div class="recommendation-item">
                    <div class="recommendation-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="recommendation-text">
                        برای ۳ دانش‌آموز با عملکرد ضعیف، جلسات رفع اشکال گروهی ترتیب دهید.
                    </div>
                </div>

                <div class="recommendation-item">
                    <div class="recommendation-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="recommendation-text">
                        زمان‌بندی آزمون‌ها را به گونه‌ای تنظیم کنید که فشار روانی کم‌تری ایجاد شود.
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== STUDENT PROGRESS TABLE ========== --}}
        <div class="dashboard-card" style="margin-bottom: 35px;">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-user-graduate"></i>
                    روند پیشرفت دانش‌آموزان
                </div>
                <div class="card-badge">
                    <i class="fas fa-sort"></i>
                    بر اساس پیشرفت
                </div>
            </div>

            <div class="table-responsive">
                <table class="progress-table">
                    <thead>
                        <tr>
                            <th>نام دانش‌آموز</th>
                            <th>میانگین نمرات</th>
                            <th>پیشرفت تحصیلی</th>
                            <th>مشارکت کلاسی</th>
                            <th>وضعیت انگیزشی</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="student-name-cell">
                                <i class="fas fa-user" style="color: var(--primary); margin-left: 8px;"></i>
                                محمد احمدی
                            </td>
                            <td>۹۴%</td>
                            <td class="progress-cell">
                                ۹۵%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 95%;"></div>
                                </div>
                            </td>
                            <td class="progress-cell">
                                ۸۸%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 88%;"></div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #00D4AA; font-weight: 900;">
                                    <i class="fas fa-fire"></i>
                                    عالی
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="student-name-cell">
                                <i class="fas fa-user" style="color: var(--primary); margin-left: 8px;"></i>
                                سارا محمدی
                            </td>
                            <td>۸۷%</td>
                            <td class="progress-cell">
                                ۸۲%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 82%;"></div>
                                </div>
                            </td>
                            <td class="progress-cell">
                                ۹۱%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 91%;"></div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #FFD166; font-weight: 900;">
                                    <i class="fas fa-smile"></i>
                                    خوب
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="student-name-cell">
                                <i class="fas fa-user" style="color: var(--primary); margin-left: 8px;"></i>
                                علی رضایی
                            </td>
                            <td>۷۵%</td>
                            <td class="progress-cell">
                                ۶۵%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 65%;"></div>
                                </div>
                            </td>
                            <td class="progress-cell">
                                ۷۰%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 70%;"></div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #FF6B9D; font-weight: 900;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    نیاز به توجه
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="student-name-cell">
                                <i class="fas fa-user" style="color: var(--primary); margin-left: 8px;"></i>
                                فاطمه کریمی
                            </td>
                            <td>۹۱%</td>
                            <td class="progress-cell">
                                ۸۹%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 89%;"></div>
                                </div>
                            </td>
                            <td class="progress-cell">
                                ۹۵%
                                <div class="progress-bar-small">
                                    <div class="progress-fill-small" style="width: 95%;"></div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #00D4AA; font-weight: 900;">
                                    <i class="fas fa-trophy"></i>
                                    برجسته
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========== CLASS COMPARISON ========== --}}
        <div class="dashboard-card" style="margin-bottom: 35px;">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-balance-scale"></i>
                    مقایسه عملکرد کلاس‌ها
                </div>
                <div class="card-badge">
                    <i class="fas fa-chart-bar"></i>
                    آماری
                </div>
            </div>

            <div class="comparison-chart">
                <div class="chart-bar" style="height: 85%;">
                    <div class="chart-value">۸۵%</div>
                    <div class="chart-label">ریاضی ۱۰۱</div>
                </div>
                <div class="chart-bar" style="height: 92%;">
                    <div class="chart-value">۹۲%</div>
                    <div class="chart-label">فیزیک ۱۰۲</div>
                </div>
                <div class="chart-bar" style="height: 78%;">
                    <div class="chart-value">۷۸%</div>
                    <div class="chart-label">شیمی ۱۰۳</div>
                </div>
                <div class="chart-bar" style="height: 88%;">
                    <div class="chart-value">۸۸%</div>
                    <div class="chart-label">زیست ۱۰۴</div>
                </div>
                <div class="chart-bar" style="height: 95%;">
                    <div class="chart-value">۹۵%</div>
                    <div class="chart-label">هندسه ۱۰۵</div>
                </div>
            </div>
        </div>

        {{-- ========== ACTION BUTTONS ========== --}}
        <div class="action-buttons">
            <a href="#" class="btn-action btn-download">
                <i class="fas fa-file-pdf"></i>
                دانلود گزارش کامل (PDF)
            </a>
            <a href="#" class="btn-action btn-export">
                <i class="fas fa-file-excel"></i>
                خروجی Excel داده‌ها
            </a>
            <a href="#" class="btn-action btn-share">
                <i class="fas fa-share-alt"></i>
                اشتراک‌گذاری با همکاران
            </a>
            <button onclick="window.print()" class="btn-action" style="background: var(--gradient-3); color: white;">
                <i class="fas fa-print"></i>
                چاپ گزارش
            </button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // انیمیشن نوارهای پیشرفت
            document.querySelectorAll('.metric-fill').forEach(fill => {
                const width = fill.getAttribute('data-width') || '0';
                setTimeout(() => {
                    fill.style.width = width + '%';
                }, 300);
            });

            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-action, .insight-card, .recommendation-item, .progress-table tr');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // افکت hover برای کارت‌ها
            const dashboardCards = document.querySelectorAll('.dashboard-card');
            dashboardCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(10);
                    }
                });
            });

            // انیمیشن ورود المان‌ها
            const animateElements = () => {
                const cards = document.querySelectorAll('.dashboard-card');
                cards.forEach((card, i) => {
                    card.style.animationDelay = `${i * 0.2}s`;
                    card.style.animation = 'fadeIn 0.5s ease-out forwards';
                    card.style.opacity = '0';
                });
            };

            // اجرای انیمیشن بعد از لود صفحه
            setTimeout(animateElements, 300);

            // کلیک روی سطر جدول برای مشاهده جزئیات دانش‌آموز
            const tableRows = document.querySelectorAll('.progress-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) {
                        const studentName = this.querySelector('.student-name-cell').textContent
                            .trim();
                        showStudentReport(studentName);
                    }
                });
            });

            // انیمیشن نمودار مقایسه
            const chartBars = document.querySelectorAll('.chart-bar');
            chartBars.forEach((bar, i) => {
                const originalHeight = bar.style.height;
                bar.style.height = '20px';
                setTimeout(() => {
                    bar.style.height = originalHeight;
                }, 500 + (i * 150));
            });
        });

        // تابع نمایش گزارش دانش‌آموز
        function showStudentReport(studentName) {
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
        max-width: 500px;
        width: 90%;
        animation: scaleIn 0.3s ease forwards;
        border: 3px solid var(--primary);
    `;

            modal.innerHTML = `
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
            <div style="font-size: 2.5rem; color: var(--primary);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: var(--dark); font-size: 1.3rem; font-weight: 900;">گزارش تحلیلی ${studentName}</h3>
                <p style="margin: 5px 0 0; color: var(--gray); font-size: 0.9rem;">تحلیل جامع عملکرد و پیشرفت</p>
            </div>
        </div>

        <div style="background: var(--light-gray); padding: 20px; border-radius: 12px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: 900; color: var(--primary);">۸۷%</div>
                    <div style="font-size: 0.8rem; color: var(--gray);">میانگین نمرات</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.8rem; font-weight: 900; color: var(--accent);">+۱۲%</div>
                    <div style="font-size: 0.8rem; color: var(--gray);">پیشرفت ماهانه</div>
                </div>
            </div>
            <div style="font-size: 0.9rem; color: var(--dark); line-height: 1.6;">
                <strong>نقاط قوت:</strong> حل مسائل تحلیلی، مشارکت فعال در کلاس
                <br>
                <strong>نیاز به بهبود:</strong> سرعت پاسخگویی در آزمون‌ها
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button onclick="this.parentElement.remove(); if (this.parentElement.nextElementSibling) this.parentElement.nextElementSibling.remove();"
                    style="flex:1; padding: 14px; border: none; background: var(--light-gray); color: var(--dark); border-radius: 12px; font-weight: 700; font-size: 1rem;">
                بستن
            </button>
            <button onclick="window.location.href='{{ route('teacher.students.index') }}';"
                    style="flex:1; padding: 14px; border: none; background: var(--gradient-1); color: white; border-radius: 12px; font-weight: 700; font-size: 1rem;">
                مشاهده پروفایل
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

            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            setTimeout(() => {
                if (document.body.contains(modal)) {
                    modal.remove();
                    overlay.remove();
                }
            }, 10000);
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
