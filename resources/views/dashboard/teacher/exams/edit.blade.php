{{-- resources/views/dashboard/teacher/exams/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'ویرایش آزمون')

@push('styles')
@include('dashboard.teacher.exams.edit-style')
@endpush

@section('content')
<div class="edit-exam-container">
    {{-- Header --}}
    <div class="page-header-edit">
        <div class="header-content-edit">
            <div class="header-title-edit">
                <h1>
                    <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        ویرایش آزمون
                    </span>
                    ✏️
                </h1>
                <p class="header-subtitle-edit">
                    ویرایش اطلاعات آزمون "<strong>{{ $exam->title }}</strong>"
                </p>
            </div>
            
            <a href="{{ route('teacher.exams.show', $exam) }}" class="btn-action-edit btn-back">
                <i class="fas fa-arrow-right"></i>
                مشاهده آزمون
            </a>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <i class="fas fa-check-circle" style="color: #00D4AA; font-size: 1.5rem;"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.2rem; cursor: pointer;">×</button>
    </div>
    @endif

    <div class="form-container-edit">
        {{-- Main Form --}}
        <div class="main-form-card">
            <form action="{{ route('teacher.exams.update', $exam) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section-title">
                    <i class="fas fa-edit"></i>
                    ویرایش اطلاعات آزمون
                </div>

                {{-- Title --}}
                <div class="form-group-edit">
                    <label class="form-label-edit">
                        <i class="fas fa-heading"></i>
                        عنوان آزمون
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="title" class="form-input-edit" 
                           placeholder="مثال: آزمون فصل اول ریاضی" 
                           value="{{ old('title', $exam->title) }}" required>
                </div>

                {{-- Description --}}
                <div class="form-group-edit">
                    <label class="form-label-edit">
                        <i class="fas fa-align-left"></i>
                        توضیحات آزمون
                    </label>
                    <textarea name="description" class="form-textarea-edit" 
                              placeholder="توضیحات کامل آزمون..." rows="4">{{ old('description', $exam->description) }}</textarea>
                </div>

                {{-- Exam Type Display (READONLY) --}}
                <div class="form-group-edit">
                    <label class="form-label-edit">
                        <i class="fas fa-layer-group"></i>
                        نوع آزمون
                    </label>
                    <div class="exam-type-display" data-type="{{ $exam->exam_type }}">
                        {{-- Will be populated by JavaScript --}}
                    </div>
                    <small class="text-muted" style="display: block; margin-top: 8px; font-size: 0.85rem;">
                        <i class="fas fa-info-circle"></i>
                        نوع آزمون پس از ایجاد غیرقابل تغییر است.
                    </small>
                </div>

                {{-- Classroom (if class exam) --}}
                @if(in_array($exam->exam_type, ['class', 'class_single', 'class_comprehensive']))
                <div class="form-group-edit">
                    <label class="form-label-edit">
                        <i class="fas fa-people-group"></i>
                        کلاس مربوطه
                    </label>
                    <select name="classroom_id" class="form-select-edit">
                        <option value="">انتخاب کلاس...</option>
                        @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" 
                                {{ (string) old('classroom_id', $exam->classroom_id) === (string) $c->id ? 'selected' : '' }}>
                            {{ $c->title ?? $c->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Subject & Level (if public exam) --}}
                @if($exam->exam_type == 'public')
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group-edit">
                            <label class="form-label-edit">
                                <i class="fas fa-book"></i>
                                درس / موضوع
                            </label>
                            <select name="subject" class="form-select-edit">
                                <option value="" disabled {{ old('subject', $exam->subject) ? '' : 'selected' }}>انتخاب درس</option>
                                @foreach($subjects ?? [] as $s)
                                <option value="{{ $s }}" {{ old('subject', $exam->subject) === $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-edit">
                            <label class="form-label-edit">
                                <i class="fas fa-chart-line"></i>
                                سطح آزمون
                            </label>
                            @php $lvl = old('level', $exam->level ?? 'taghviyati'); @endphp
                            <select name="level" class="form-select-edit">
                                <option value="taghviyati" {{ $lvl === 'taghviyati' ? 'selected' : '' }}>تقویتی</option>
                                <option value="konkur" {{ $lvl === 'konkur' ? 'selected' : '' }}>کنکور</option>
                                <option value="olympiad" {{ $lvl === 'olympiad' ? 'selected' : '' }}>المپیاد</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Duration & Start Time --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group-edit">
                            <label class="form-label-edit">
                                <i class="fas fa-clock"></i>
                                مدت آزمون (دقیقه)
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="duration" class="form-input-edit" 
                                   min="1" value="{{ old('duration', $exam->duration_minutes) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-edit">
                            <label class="form-label-edit">
                                <i class="fas fa-calendar-alt"></i>
                                تاریخ و زمان شروع
                            </label>
                            <input type="datetime-local" name="start_at" class="form-input-edit" 
                                   value="{{ old('start_at', optional($exam->start_at)->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>

                {{-- Publish Switch --}}
                <div class="form-group-edit">
                    <div class="form-switch-custom">
                        <input class="form-check-input" type="checkbox" role="switch" 
                               id="publishSwitch" name="is_published" value="1" 
                               {{ old('is_published', $exam->is_published) ? 'checked' : '' }}
                               data-was-checked="{{ $exam->is_published ? 'true' : 'false' }}">
                        <label class="form-check-label" for="publishSwitch">
                            <i class="fas fa-broadcast-tower me-2"></i>
                            آزمون منتشر باشد
                        </label>
                    </div>
                </div>

                {{-- Tips Section --}}
                <div class="tips-section">
                    <div class="tips-title">
                        <i class="fas fa-lightbulb"></i>
                        نکات مهم
                    </div>
                    <ul class="tips-list">
                        <li>
                            <i class="fas fa-check-circle"></i>
                            قبل از انتشار، تمام سوالات را بررسی کنید
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            مدت زمان مناسب برای آزمون تنظیم کنید
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            نوع آزمون غیرقابل تغییر است
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            تغییرات پس از ذخیره اعمال می‌شوند
                        </li>
                    </ul>
                </div>

                {{-- Form Actions --}}
                <div class="action-buttons">
                    <button type="submit" class="btn-action-edit btn-save">
                        <i class="fas fa-save"></i>
                        ذخیره تغییرات
                    </button>
                </div>
            </form>

            {{-- Danger Zone --}}
            <div class="danger-zone-edit">
                <div class="danger-title">
                    <i class="fas fa-trash-alt"></i>
                    حذف آزمون
                </div>
                <p class="danger-description">
                    این عمل غیرقابل بازگشت است. تمامی سوالات، نتایج و اطلاعات مربوط به این آزمون حذف خواهند شد.
                </p>
                <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i>
                        حذف دائم آزمون
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="sidebar-card">
            {{-- Live Preview --}}
            <div class="preview-section">
                <div class="preview-title">
                    <i class="fas fa-eye"></i>
                    پیش‌نمایش زنده
                </div>
                <div class="preview-box">
                    <div class="preview-item">
                        <div class="preview-label">عنوان:</div>
                        <div class="preview-value" id="livePreviewTitle">{{ $exam->title }}</div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">نوع:</div>
                        <div class="preview-value" id="livePreviewType">
                            {{-- Will be populated by JavaScript --}}
                        </div>
                    </div>
                    
                    {{-- Additional info based on exam type --}}
                    @if(in_array($exam->exam_type, ['class', 'class_single', 'class_comprehensive']))
                    <div class="preview-item">
                        <div class="preview-label">کلاس:</div>
                        <div class="preview-value" id="livePreviewClassroom">
                            {{ $exam->classroom->title ?? ($exam->classroom->name ?? '—') }}
                        </div>
                    </div>
                    @endif
                    
                    @if($exam->exam_type == 'public')
                    <div class="preview-item">
                        <div class="preview-label">درس:</div>
                        <div class="preview-value" id="livePreviewSubject">{{ $exam->subject ?? '—' }}</div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">سطح:</div>
                        <div class="preview-value" id="livePreviewLevel">
                            @if($exam->level == 'taghviyati') تقویتی
                            @elseif($exam->level == 'konkur') کنکور
                            @elseif($exam->level == 'olympiad') المپیاد
                            @else — @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="preview-item">
                        <div class="preview-label">مدت زمان:</div>
                        <div class="preview-value" id="livePreviewDuration">{{ $exam->duration_minutes }} دقیقه</div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">وضعیت:</div>
                        <div class="preview-value">
                            <span id="livePreviewStatus">
                                @if($exam->is_published)
                                <span style="color: #00D4AA; font-weight: 900;">منتشر شده</span>
                                @else
                                <span style="color: #FF9F43; font-weight: 900;">پیش‌نویس</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($exam->start_at)
                    <div class="preview-item">
                        <div class="preview-label">زمان شروع:</div>
                        <div class="preview-value">{{ $exam->start_at->format('Y/m/d H:i') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="preview-section">
                <div class="preview-title">
                    <i class="fas fa-bolt"></i>
                    اقدامات سریع
                </div>
                <div class="action-buttons">
                    <a href="{{ route('teacher.exams.questions.index', $exam) }}" 
                       class="btn-action-edit btn-questions">
                        <i class="fas fa-question-circle"></i>
                        مدیریت سوالات
                    </a>
                    <a href="{{ route('teacher.exams.index') }}" 
                       class="btn-action-edit btn-back">
                        <i class="fas fa-list"></i>
                        لیست آزمون‌ها
                    </a>
                </div>
            </div>

            {{-- Exam Info --}}
            <div class="preview-section">
                <div class="preview-title">
                    <i class="fas fa-info-circle"></i>
                    اطلاعات آزمون
                </div>
                <div class="preview-box">
                    <div class="preview-item">
                        <div class="preview-label">شناسه:</div>
                        <div class="preview-value">#{{ $exam->id }}</div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">تاریخ ایجاد:</div>
                        <div class="preview-value">{{ $exam->created_at->format('Y/m/d') }}</div>
                    </div>
                    <div class="preview-item">
                        <div class="preview-label">تعداد سوالات:</div>
                        <div class="preview-value">{{ $exam->questions_count ?? 0 }} سوال</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('dashboard.teacher.exams.edit-script')
@endpush