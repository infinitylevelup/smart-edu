@extends('layouts.app')
@section('title', 'آزمون‌ها')

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

        .exams-container {
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

        @keyframes shimmer {
            0% {
                background-position: -200% center;
            }

            100% {
                background-position: 200% center;
            }
        }

        /* ========== HEADER ========== */
        .page-header {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px 30px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 30px;
            border: 2px solid rgba(123, 104, 238, 0.08);
            position: relative;
            overflow: hidden;
            animation: slideInRight 0.5s ease-out;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, rgba(123, 104, 238, 0.05), transparent);
            border-radius: 0 var(--radius-xl) 0 0;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .header-title h1 {
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title h1::before {
            content: '';
            width: 8px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .header-subtitle {
            color: var(--gray);
            font-size: 1.05rem;
            line-height: 1.8;
            max-width: 600px;
        }

        .btn-create-exam {
            padding: 15px 28px;
            border-radius: var(--radius-lg);
            font-weight: 800;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--gradient-1);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(123, 104, 238, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-create-exam:active {
            transform: scale(0.97);
        }

        .btn-create-exam:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(123, 104, 238, 0.4);
        }

        .btn-create-exam::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.6s;
        }

        .btn-create-exam:hover::before {
            left: 100%;
        }

        /* ========== FILTER SECTION ========== */
        .filter-section {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 25px 30px;
            box-shadow: var(--shadow-md);
            margin-bottom: 30px;
            border: 2px solid rgba(123, 104, 238, 0.08);
            animation: slideInLeft 0.5s ease-out;
        }

        .filter-title {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 15px;
            align-items: end;
        }

        @media (max-width: 768px) {
            .filter-form {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            color: var(--gray);
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-select-custom {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--light-gray);
            border-radius: var(--radius-md);
            background: var(--light);
            color: var(--dark);
            font-weight: 700;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%237B68EE' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left 18px center;
            background-size: 16px;
            padding-left: 45px;
        }

        .form-select-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 104, 238, 0.2);
        }

        .btn-filter {
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 1rem;
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn-filter:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* ========== EXAMS TABLE ========== */
        .exams-table-container {
            background: var(--light);
            border-radius: var(--radius-xl);
            padding: 0;
            box-shadow: var(--shadow-lg);
            border: 2px solid rgba(123, 104, 238, 0.08);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            animation-delay: 0.1s;
            animation-fill-mode: both;
        }

        .table-header {
            padding: 25px 30px;
            border-bottom: 2px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-weight: 900;
            font-size: 1.3rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .table-title i {
            color: var(--primary);
            background: var(--primary-light);
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .exams-count {
            background: var(--primary-light);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .exams-table {
            width: 100%;
            border-collapse: collapse;
        }

        .exams-table thead {
            background: linear-gradient(90deg, rgba(123, 104, 238, 0.05), rgba(255, 107, 157, 0.05));
        }

        .exams-table th {
            padding: 20px 25px;
            text-align: right;
            font-weight: 900;
            color: var(--dark);
            font-size: 0.95rem;
            border-bottom: 2px solid var(--light-gray);
            white-space: nowrap;
        }

        .exams-table tbody tr {
            transition: all 0.3s;
            border-bottom: 1px solid var(--light-gray);
        }

        .exams-table tbody tr:last-child {
            border-bottom: none;
        }

        .exams-table tbody tr:hover {
            background: var(--primary-light);
            transform: translateX(-5px);
        }

        .exams-table td {
            padding: 20px 25px;
            vertical-align: middle;
            font-weight: 700;
            color: var(--dark);
        }

        .exam-title-cell {
            font-weight: 900 !important;
            font-size: 1.05rem;
        }

        .exam-classroom {
            color: var(--gray);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .exam-duration {
            color: var(--dark);
            font-weight: 900;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .exam-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .status-active {
            background: rgba(0, 212, 170, 0.15);
            color: #00D4AA;
        }

        .status-inactive {
            background: rgba(138, 141, 155, 0.15);
            color: var(--gray);
        }

        .exam-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-weight: 800;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.3s;
            border: 2px solid transparent;
            min-width: 90px;
            justify-content: center;
        }

        .btn-action:active {
            transform: scale(0.95);
        }

        .btn-details {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-details:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        .btn-edit {
            background: transparent;
            color: var(--dark);
            border: 2px solid var(--gray);
        }

        .btn-edit:hover {
            background: var(--light-gray);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            padding: 60px 30px;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--light-gray);
            margin-bottom: 25px;
            opacity: 0.7;
        }

        .empty-title {
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 15px;
        }

        .empty-description {
            color: var(--gray);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        /* ========== ALERTS ========== */
        .alert-success-custom {
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.1), rgba(0, 212, 170, 0.05));
            border: 2px solid rgba(0, 212, 170, 0.2);
            border-radius: var(--radius-lg);
            padding: 20px 25px;
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 30px;
            animation: slideInRight 0.5s ease-out;
            position: relative;
            overflow: hidden;
        }

        .alert-success-custom::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(0, 212, 170, 0.08), transparent);
            border-radius: 0 var(--radius-lg) 0 0;
        }

        .alert-success-custom i {
            color: #00D4AA;
            font-size: 1.3rem;
            margin-left: 10px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .exams-table {
                display: block;
                overflow-x: auto;
            }

            .exams-table thead {
                display: none;
            }

            .exams-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 2px solid var(--light-gray);
                border-radius: var(--radius-lg);
                padding: 20px;
            }

            .exams-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px dashed var(--light-gray);
            }

            .exams-table td:last-child {
                border-bottom: none;
                padding-top: 20px;
                justify-content: flex-end;
            }

            .exams-table td::before {
                content: attr(data-label);
                font-weight: 900;
                color: var(--gray);
                font-size: 0.9rem;
                min-width: 100px;
                text-align: left;
            }

            .exam-actions {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .exams-container {
                padding: 15px 10px 60px;
            }

            .page-header {
                padding: 20px;
            }

            .header-title h1 {
                font-size: 1.5rem;
            }

            .btn-create-exam {
                width: 100%;
                justify-content: center;
            }

            .filter-section {
                padding: 20px;
            }

            .table-header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-icon {
                font-size: 4rem;
            }
        }

        @media (max-width: 480px) {
            .btn-action {
                min-width: auto;
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .exam-status {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        /* دکمه‌های لمسی بزرگ */
        .btn-create-exam,
        .btn-filter,
        .btn-action {
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
    <div class="exams-container">
        {{-- ========== PAGE HEADER ========== --}}
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>
                        <span
                            style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            آزمون‌های من
                        </span>
                        📝
                    </h1>
                    <p class="header-subtitle">
                        همه آزمون‌های کلاس‌های شما در یک نگاه.
                        می‌توانید آزمون‌ها را مدیریت، ویرایش و نتایج را مشاهده کنید.
                    </p>
                </div>

                <a href="{{ route('teacher.exams.create') }}" class="btn-create-exam">
                    <i class="fas fa-plus-circle"></i>
                    ساخت آزمون جدید
                </a>
            </div>
        </div>

        {{-- ========== SUCCESS ALERT ========== --}}
        @if (session('success'))
            <div class="alert-success-custom d-flex align-items-center">
                <i class="fas fa-check-circle"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- ========== FILTER SECTION ========== --}}
        <div class="filter-section">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i>
                فیلتر و جستجوی پیشرفته
            </h3>

            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label class="form-label">فیلتر بر اساس کلاس</label>
                    <select name="classroom_id" class="form-select-custom">
                        <option value="">همه کلاس‌ها</option>
@foreach (($classrooms ?? []) as $c)
                            <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>
                                {{ $c->title ?? $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-sliders-h"></i>
                    اعمال فیلتر
                </button>
            </form>
        </div>

        {{-- ========== EXAMS TABLE ========== --}}
        @if ($exams->count() === 0)
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="empty-title">هنوز آزمونی نساخته‌اید!</h3>
                <p class="empty-description">
                    برای شروع، اولین آزمون خود را ایجاد کنید.
                    می‌توانید انواع مختلفی از آزمون‌ها شامل تستی، تشریحی و ترکیبی بسازید.
                </p>
                <a href="{{ route('teacher.exams.create') }}" class="btn-create-exam">
                    <i class="fas fa-plus-circle"></i>
                    ایجاد اولین آزمون
                </a>
            </div>
        @else
            <div class="exams-table-container">
                <div class="table-header">
                    <h3 class="table-title">
                        <i class="fas fa-list-check"></i>
                        لیست آزمون‌ها
                    </h3>
                    <div class="exams-count">
                        <i class="fas fa-hashtag"></i>
                        {{ $exams->count() }} آزمون
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="exams-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان آزمون</th>
                                <th>کلاس</th>
                                <th>مدت زمان</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exams as $exam)
                                <tr>
                                    <td data-label="شماره">
                                        <span style="color: var(--primary); font-weight: 900; font-size: 1.1rem;">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>
                                    <td data-label="عنوان آزمون" class="exam-title-cell">
                                        {{ $exam->title }}
                                    </td>
                                    <td data-label="کلاس">
                                        <div class="exam-classroom">
                                            <i class="fas fa-people-group"></i>
                                            {{ $exam->classroom->title ?? ($exam->classroom->name ?? '—') }}
                                        </div>
                                    </td>
                                    <td data-label="مدت زمان">
                                        <div class="exam-duration">
                                            <i class="fas fa-clock"></i>
                                            {{ $exam->duration ?? ($exam->duration_minutes ?? '—') }} دقیقه
                                        </div>
                                    </td>
                                    <td data-label="وضعیت">
                                        @if ($exam->is_active)
                                            <span class="exam-status status-active">
                                                <i class="fas fa-check-circle"></i>
                                                فعال
                                            </span>
                                        @else
                                            <span class="exam-status status-inactive">
                                                <i class="fas fa-pause-circle"></i>
                                                غیرفعال
                                            </span>
                                        @endif
                                    </td>
                                    <td data-label="عملیات">
                                        <div class="exam-actions">
                                            <a href="{{ route('teacher.exams.show', $exam) }}"
                                                class="btn-action btn-details">
                                                <i class="fas fa-eye"></i>
                                                جزئیات
                                            </a>
                                            <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                                ویرایش
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ویبره برای موبایل
            if (navigator.vibrate) {
                const clickableItems = document.querySelectorAll(
                    '.btn-create-exam, .btn-filter, .btn-action, .exams-table tbody tr');
                clickableItems.forEach(item => {
                    item.addEventListener('click', function() {
                        navigator.vibrate(20);
                    });
                });
            }

            // افکت hover برای سطرهای جدول
            const tableRows = document.querySelectorAll('.exams-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(10);
                    }
                });

                // کلیک روی سطر برای مشاهده جزئیات
                const detailsBtn = row.querySelector('.btn-details');
                if (detailsBtn) {
                    row.addEventListener('click', function(e) {
                        if (!e.target.closest('.btn-action')) {
                            window.location.href = detailsBtn.href;
                        }
                    });
                }
            });

            // انیمیشن ورود المان‌ها
            const animateElements = () => {
                const elements = document.querySelectorAll('.exams-table tbody tr');
                elements.forEach((el, i) => {
                    el.style.animationDelay = `${i * 0.05}s`;
                    el.style.animation = 'fadeIn 0.5s ease-out forwards';
                    el.style.opacity = '0';
                });
            };

            // اجرای انیمیشن بعد از لود صفحه
            setTimeout(animateElements, 300);

            // اعتبارسنجی فیلتر
            const filterForm = document.querySelector('.filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    const select = this.querySelector('select[name="classroom_id"]');
                    if (select.value) {
                        // نمایش بارگیری
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin"></i> در حال اعمال فیلتر...';
                        submitBtn.disabled = true;

                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 1000);
                    }
                });
            }

            // نمایش تعداد کلاس‌های فیلتر شده
            const updateFilterCount = () => {
                const selectedClass = document.querySelector('select[name="classroom_id"]').value;
                const examCount = document.querySelectorAll('.exams-table tbody tr').length;
                const countElement = document.querySelector('.exams-count');

                if (countElement && selectedClass) {
                    const className = document.querySelector(`option[value="${selectedClass}"]`).textContent;
                    countElement.innerHTML = `<i class="fas fa-filter"></i> ${examCount} آزمون (${className})`;
                }
            };

            // رویداد تغییر فیلتر
            const classSelect = document.querySelector('select[name="classroom_id"]');
            if (classSelect) {
                classSelect.addEventListener('change', updateFilterCount);
            }
        });

        // اضافه کردن استایل‌های انیمیشن
        const style = document.createElement('style');
        style.textContent = `
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
