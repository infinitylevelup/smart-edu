@extends('layouts.app')
@section('title', 'ساخت کلاس جدید')

@section('content')
<div class="container-fluid"
     data-page="teacher-classes-create"
     data-sections-url="{{ route('teacher.classes.data.sections') }}"
     data-grades-url="{{ url('/dashboard/teacher/classes/data/grades/:section') }}"
     data-branches-url="{{ url('/dashboard/teacher/classes/data/branches/:grade') }}"
     data-fields-url="{{ url('/dashboard/teacher/classes/data/fields/:branch') }}"
     data-subfields-url="{{ url('/dashboard/teacher/classes/data/subfields/:field') }}"
     data-subject-types-url="{{ url('/dashboard/teacher/classes/data/subject-types/:field') }}"
     data-subjects-url="{{ url('/dashboard/teacher/classes/data/subjects/:subjectType') }}"
>

  {{-- Header --}}
  <div class="form-shell mb-4 fade-up">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 text-end">
      <div>
        <h1 class="h4 fw-bold mb-1"><i class="bi bi-plus-circle me-1 text-primary"></i> ساخت کلاس جدید</h1>
        <p class="text-muted mb-0">برای شروع آموزش، یک کلاس بساز و دانش‌آموزها را اضافه کن.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('teacher.classes.index') }}"
           class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 shadow-sm">
          <i class="bi bi-arrow-right"></i> بازگشت
        </a>
      </div>
    </div>
  </div>

  {{-- Errors --}}
  @if ($errors->any())
    <div class="alert alert-danger fade-up">
      <div class="fw-semibold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> لطفاً خطاهای زیر را بررسی کن:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">

    {{-- Form column --}}
    <div class="col-lg-8">
      <div class="form-card p-3 p-md-4 fade-up">

        {{-- Wizard Steps --}}
        <div class="wizard mb-3" id="wizard">
          <div class="wiz-step active" data-step="1"><div class="num">1</div> اطلاعات پایه</div>
          <div class="wiz-step" data-step="2"><div class="num">2</div> جزئیات و تنظیمات</div>
          <div class="wiz-step" data-step="3"><div class="num">3</div> مرور نهایی</div>
        </div>

        <form action="{{ route('teacher.classes.store') }}" method="POST" class="row g-3" novalidate>
          @csrf

          {{-- Step 1 --}}
          <div class="col-12 step" data-step="1">
            <label class="form-label fw-semibold">نام کلاس <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control input-soft" required
                   value="{{ old('title') }}" placeholder="مثلاً: ریاضی نهم - کلاس A">
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">مقطع / بخش (Section) <span class="text-danger">*</span></label>
            <select name="section_id" id="section_id" class="form-select input-soft" required>
              <option value="">انتخاب مقطع</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">پایه (Grade) <span class="text-danger">*</span></label>
            <select name="grade_id" id="grade_id" class="form-select input-soft" required disabled>
              <option value="">انتخاب پایه</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">شاخه (Branch) <span class="text-danger">*</span></label>
            <select name="branch_id" id="branch_id" class="form-select input-soft" required disabled>
              <option value="">انتخاب شاخه</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">رشته (Field) <span class="text-danger">*</span></label>
            <select name="field_id" id="field_id" class="form-select input-soft" required disabled>
              <option value="">انتخاب رشته</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">زیررشته (Subfield) <span class="text-muted">(اختیاری)</span></label>
            <select name="subfield_id" id="subfield_id" class="form-select input-soft" disabled>
              <option value="">انتخاب زیررشته</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">نوع درس (Subject Type) <span class="text-muted">(اختیاری)</span></label>
            <select name="subject_type_id" id="subject_type_id" class="form-select input-soft" disabled>
              <option value="">انتخاب نوع درس</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">درس (Subject) <span class="text-muted">(اختیاری)</span></label>
            <select name="subject_id" id="subject_id" class="form-select input-soft" disabled>
              <option value="">انتخاب درس</option>
            </select>
          </div>

          <div class="col-md-6 step" data-step="1">
            <label class="form-label fw-semibold">نوع کلاس <span class="text-danger">*</span></label>
            <select name="classroom_type" id="classroom_type" class="form-select input-soft" required>
              <option value="">انتخاب نوع کلاس</option>
              <option value="single" {{ old('classroom_type')==='single'?'selected':'' }}>تکی</option>
              <option value="comprehensive" {{ old('classroom_type')==='comprehensive'?'selected':'' }}>جامع</option>
            </select>
          </div>

          {{-- Step 2 --}}
          <div class="col-12 step" data-step="2" style="display:none">
            <label class="form-label fw-semibold">توضیحات کلاس</label>
            <textarea name="description" rows="4" class="form-control input-soft"
                      placeholder="توضیح کوتاه دربارهٔ اهداف، منابع، زمان‌بندی و...">{{ old('description') }}</textarea>
          </div>

          <div class="col-md-6 step" data-step="2" style="display:none">
            <label class="form-label fw-semibold">وضعیت کلاس</label>
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="activeSwitch" name="is_active"
                     value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
              <label class="form-check-label" for="activeSwitch">کلاس فعال باشد</label>
            </div>
            <div class="hint mt-2 text-muted small">کلاس غیرفعال برای آرشیو یا کلاس‌های تمام‌شده مناسب است.</div>
          </div>

          <div class="col-md-6 step" data-step="2" style="display:none">
            <label class="form-label fw-semibold">کد ورود دانش‌آموز</label>
            <div class="input-group">
              <input type="text" class="form-control input-soft" id="joinCode"
                     value="{{ old('join_code') }}" placeholder="خودکار ساخته می‌شود" disabled>
              <button type="button" class="btn btn-outline-primary btn-wizard" id="genCodeBtn">
                <i class="bi bi-shuffle"></i> ساخت کد
              </button>
            </div>
            <div class="text-muted small mt-1">بعد از ساخت کلاس این کد را به دانش‌آموزان بده.</div>
          </div>

          {{-- Step 3 Review --}}
          <div class="col-12 step" data-step="3" style="display:none">
            <div class="preview-card p-3">
              <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> مرور نهایی</div>
              <div class="small text-muted">قبل از ذخیره یکبار چک کن.</div>
              <div class="border rounded-3 p-3 bg-white mt-2" id="reviewBox"></div>
            </div>
          </div>

          {{-- Nav buttons --}}
          <div class="col-12 d-flex flex-wrap gap-2 mt-2">
            <button type="button" class="btn btn-outline-secondary btn-wizard" id="prevBtn" disabled>
              <i class="bi bi-arrow-right"></i> قبلی
            </button>
            <button type="button" class="btn btn-primary btn-wizard shadow-sm" id="nextBtn">
              بعدی <i class="bi bi-arrow-left"></i>
            </button>
            <button type="submit" class="btn btn-success btn-wizard shadow-sm" id="submitBtn" style="display:none">
              <i class="bi bi-check2-circle"></i> ساخت کلاس
            </button>
          </div>

        </form>
      </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-4">
      <div class="floating fade-up">

        <div class="preview-card p-3 mb-3">
          <div class="fw-bold mb-2"><i class="bi bi-eye me-1"></i> پیش‌نمایش زنده</div>
          <div class="border rounded-3 p-3 bg-white" id="livePreview">
            <div class="fw-semibold fs-6" id="pvTitle">نام کلاس</div>
            <div class="text-muted small mt-2" id="pvTax">تاکسونومی: —</div>
            <div class="text-muted small mt-1" id="pvType">نوع کلاس: —</div>
            <div class="d-flex flex-wrap gap-2 mt-2">
              <span class="meta-pill" id="pvActive"><i class="bi bi-broadcast"></i> فعال</span>
              <span class="meta-pill" id="pvCode"><i class="bi bi-key"></i> کد: —</span>
            </div>
            <div class="text-muted small mt-3" id="pvDesc"></div>
          </div>
        </div>

        <div class="form-card p-3">
          <div class="fw-bold mb-2"><i class="bi bi-lightbulb me-1 text-warning"></i> نکته‌ها</div>
          <ul class="small text-muted mb-0 ps-3">
            <li>نام کلاس واضح و قابل تشخیص انتخاب کن.</li>
            <li>کد ورود را بعد از ساخت در گروه کلاس ارسال کن.</li>
            <li>می‌توانی کلاس را بعداً آرشیو کنی.</li>
          </ul>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
