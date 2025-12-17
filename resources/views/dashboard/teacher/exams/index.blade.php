@extends('layouts.app')
@section('title', 'آزمون‌ها')

@push('styles')
    @include('dashboard.teacher.exams.index-style')
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
                                <th>نوع آزمون</th>
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
                                    <td data-label="نوع آزمون">
                                        <div class="exam-type {{ $exam->type_class }}">
                                            <i class="fas {{ $exam->type_icon }}"></i>
                                            {{ $exam->type_fa }}
                                        </div>
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
    @include('dashboard.teacher.exams.index-script')
@endpush