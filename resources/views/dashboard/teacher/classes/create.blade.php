@extends('layouts.app')
@section('title', 'ساخت کلاس جدید')

@push('styles')
<style>
  .form-shell{background:radial-gradient(900px circle at 100% -30%,rgba(13,110,253,.12),transparent 55%),linear-gradient(180deg,#fff,#f8fafc);border-radius:1.5rem;padding:1rem;box-shadow:0 10px 30px rgba(18,38,63,.08)}
  .form-card{border:0;border-radius:1.25rem;background:#fff;box-shadow:0 8px 24px rgba(18,38,63,.06)}
  .input-soft{border:0;box-shadow:0 6px 18px rgba(18,38,63,.06);border-radius:.9rem;padding:.7rem .9rem}
  .input-soft:focus{box-shadow:0 0 0 .25rem rgba(13,110,253,.15)}
  .wizard{display:flex;gap:.5rem;flex-wrap:wrap}
  .wiz-step{flex:1 1 140px;background:#f8fafc;border-radius:1rem;padding:.7rem .9rem;display:flex;align-items:center;gap:.6rem;font-weight:800;font-size:.9rem;color:#334155}
  .wiz-step.active{background:rgba(13,110,253,.12);color:#0d6efd}
  .wiz-step .num{width:28px;height:28px;border-radius:.7rem;display:grid;place-items:center;background:#fff;border:1px solid #e2e8f0}
  .btn-wizard{border-radius:1rem;padding:.7rem 1rem;display:inline-flex;align-items:center;gap:.5rem}
  .preview-card{border:1px dashed #e2e8f0;border-radius:1.25rem;background:linear-gradient(180deg,#fff,#f8fafc)}
  .meta-pill{background:#f8fafc;border:1px solid #eef2f7;border-radius:999px;padding:.25rem .6rem;display:inline-flex;align-items:center;gap:.35rem;font-weight:700;font-size:.85rem;color:#475569}
  .floating{position:sticky;top:90px}
</style>
@endpush

@section('content')
<div class="container-fluid">

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  // ---------------- Wizard ----------------
  const steps    = [...document.querySelectorAll('.step')];
  const wizSteps = [...document.querySelectorAll('.wiz-step')];
  const prevBtn  = document.getElementById('prevBtn');
  const nextBtn  = document.getElementById('nextBtn');
  const submitBtn= document.getElementById('submitBtn');
  let current = 1;

  function setStep(n) {
    current = n;
    steps.forEach(s => s.style.display = (Number(s.dataset.step) === n) ? '' : 'none');
    wizSteps.forEach(w => w.classList.toggle('active', Number(w.dataset.step) === n));
    prevBtn.disabled = n === 1;
    nextBtn.style.display = n < 3 ? '' : 'none';
    submitBtn.style.display = n === 3 ? '' : 'none';
    if (n === 3) fillReview();
  }

  prevBtn.addEventListener('click', () => setStep(Math.max(1, current - 1)));

  // validate step1 before next
  nextBtn.addEventListener('click', () => {
    if(current === 1){
      const required = [title, section, grade, branch, field, classroomType];
      const ok = required.every(el => el && el.value);
      if(!ok){
        alert('لطفاً همه فیلدهای ستاره‌دار را پر کنید.');
        return;
      }
    }
    setStep(Math.min(3, current + 1));
  });

  // ---------------- Fields ----------------
  const title   = document.querySelector('input[name="title"]');
  const desc    = document.querySelector('textarea[name="description"]');
  const active  = document.getElementById('activeSwitch');

  const section   = document.getElementById('section_id');
  const grade     = document.getElementById('grade_id');
  const branch    = document.getElementById('branch_id');
  const field     = document.getElementById('field_id');
  const subfield  = document.getElementById('subfield_id');
  const subjectType = document.getElementById('subject_type_id');
  const subject   = document.getElementById('subject_id');
  const classroomType = document.getElementById('classroom_type');

  // ---------------- Preview ----------------
  const pvTitle = document.getElementById('pvTitle');
  const pvTax   = document.getElementById('pvTax');
  const pvType  = document.getElementById('pvType');
  const pvDesc  = document.getElementById('pvDesc');
  const pvActive= document.getElementById('pvActive');
  const pvCode  = document.getElementById('pvCode');

  const joinCode = document.getElementById('joinCode');
  const genCodeBtn = document.getElementById('genCodeBtn');

  function genCode() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let c = '';
    for (let i = 0; i < 6; i++) c += chars[Math.floor(Math.random() * chars.length)];
    joinCode.value = c;
    pvCode.innerHTML = `<i class="bi bi-key"></i> کد: ${c}`;
  }
  genCodeBtn?.addEventListener('click', genCode);

  function optionText(sel){
    return sel?.options[sel.selectedIndex]?.text || '—';
  }

  function updateTaxPreview(){
    const txt = [
      optionText(section),
      optionText(grade),
      optionText(branch),
      optionText(field),
      subfield?.value ? optionText(subfield) : null,
      subjectType?.value ? optionText(subjectType) : null,
      subject?.value ? optionText(subject) : null,
    ].filter(Boolean).join(' / ');
    pvTax.textContent = 'تاکسونومی: ' + (txt || '—');
  }

  function updateTypePreview(){
    pvType.textContent = 'نوع کلاس: ' + (optionText(classroomType));
  }

  function fillReview() {
    const t = title?.value?.trim() || '—';
    const d = desc?.value?.trim() || '—';
    const a = active?.checked ? 'فعال' : 'آرشیو';
    const c = joinCode?.value || 'خودکار';
    const tax = pvTax.textContent.replace('تاکسونومی: ','') || '—';
    const typ = pvType.textContent.replace('نوع کلاس: ','') || '—';

    document.getElementById('reviewBox').innerHTML = `
      <div class="fw-semibold mb-1">نام کلاس:</div><div>${t}</div>
      <div class="mt-2 fw-semibold mb-1">تاکسونومی:</div><div>${tax}</div>
      <div class="mt-2 fw-semibold mb-1">نوع کلاس:</div><div>${typ}</div>
      <div class="mt-2 text-muted small">وضعیت: ${a}</div>
      <div class="mt-2 fw-semibold mb-1">کد ورود:</div><div>${c}</div>
      <div class="mt-2 fw-semibold mb-1">توضیحات:</div><div>${d}</div>
    `;
  }

  title?.addEventListener('input', () => pvTitle.textContent = title.value || 'نام کلاس');
  desc?.addEventListener('input', () => pvDesc.textContent = desc.value || '');
  active?.addEventListener('change', () => {
    pvActive.innerHTML = active.checked
      ? '<i class="bi bi-broadcast"></i> فعال'
      : '<i class="bi bi-archive"></i> آرشیو';
  });
  [section, grade, branch, field, subfield, subjectType, subject].forEach(s => {
    s?.addEventListener('change', updateTaxPreview);
  });
  classroomType?.addEventListener('change', updateTypePreview);

  // ---------------- AJAX Taxonomy ----------------
  function resetSelect(sel, placeholder='انتخاب کنید') {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    sel.disabled = true;
  }

  function fillSelect(sel, items, placeholder='انتخاب کنید') {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(it => {
      sel.innerHTML += `<option value="${it.id}">${it.name}</option>`;
    });
    sel.disabled = false;
  }

  async function getJson(url){
    const r = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
    return await r.json();
  }

  resetSelect(grade, 'انتخاب پایه');
  resetSelect(branch, 'انتخاب شاخه');
  resetSelect(field, 'انتخاب رشته');
  resetSelect(subfield, 'انتخاب زیررشته');
  resetSelect(subjectType, 'انتخاب نوع درس');
  resetSelect(subject, 'انتخاب درس');

  // 1) load sections
  (async () => {
    const sections = await getJson("{{ route('teacher.classes.data.sections') }}");
    fillSelect(section, sections, 'انتخاب مقطع');
  })();

  // 2) section -> grades
  section.addEventListener('change', async () => {
    resetSelect(grade, 'انتخاب پایه');
    resetSelect(branch, 'انتخاب شاخه');
    resetSelect(field, 'انتخاب رشته');
    resetSelect(subfield, 'انتخاب زیررشته');
    resetSelect(subjectType, 'انتخاب نوع درس');
    resetSelect(subject, 'انتخاب درس');

    if(!section.value) return;

    const grades = await getJson(
      "{{ url('/dashboard/teacher/classes/data/grades') }}/" + section.value
    );
    fillSelect(grade, grades, 'انتخاب پایه');
  });

  // 3) grade -> branches
  grade.addEventListener('change', async () => {
    resetSelect(branch, 'انتخاب شاخه');
    resetSelect(field, 'انتخاب رشته');
    resetSelect(subfield, 'انتخاب زیررشته');
    resetSelect(subjectType, 'انتخاب نوع درس');
    resetSelect(subject, 'انتخاب درس');

    if(!grade.value) return;

    const branches = await getJson(
      "{{ url('/dashboard/teacher/classes/data/branches') }}/" + grade.value
    );
    fillSelect(branch, branches, 'انتخاب شاخه');
  });

  // 4) branch -> fields
  branch.addEventListener('change', async () => {
    resetSelect(field, 'انتخاب رشته');
    resetSelect(subfield, 'انتخاب زیررشته');
    resetSelect(subjectType, 'انتخاب نوع درس');
    resetSelect(subject, 'انتخاب درس');

    if(!branch.value) return;

    const fields = await getJson(
      "{{ url('/dashboard/teacher/classes/data/fields') }}/" + branch.value
    );
    fillSelect(field, fields, 'انتخاب رشته');
  });

  // 5) field -> subfields + subjectTypes
  field.addEventListener('change', async () => {
    resetSelect(subfield, 'انتخاب زیررشته');
    resetSelect(subjectType, 'انتخاب نوع درس');
    resetSelect(subject, 'انتخاب درس');

    if(!field.value) return;

    const [subfields, subjectTypes] = await Promise.all([
      getJson("{{ url('/dashboard/teacher/classes/data/subfields') }}/" + field.value),
      getJson("{{ url('/dashboard/teacher/classes/data/subject-types') }}/" + field.value),
    ]);

    if(subfields.length) fillSelect(subfield, subfields, 'انتخاب زیررشته');
    else { subfield.disabled=false; subfield.innerHTML=`<option value="">بدون زیررشته</option>`; }

    if(subjectTypes.length) fillSelect(subjectType, subjectTypes, 'انتخاب نوع درس');
    else { subjectType.disabled=false; subjectType.innerHTML=`<option value="">بدون نوع درس</option>`; }
  });

  // 6) subjectType -> subjects
  subjectType.addEventListener('change', async () => {
    resetSelect(subject, 'انتخاب درس');

    if(!subjectType.value) return;

    const subjects = await getJson(
      "{{ url('/dashboard/teacher/classes/data/subjects') }}/" + subjectType.value
    );

    if(subjects.length) fillSelect(subject, subjects, 'انتخاب درس');
    else { subject.disabled=false; subject.innerHTML=`<option value="">بدون درس</option>`; }
  });

  // init
  setStep(1);
  genCode();
  updateTaxPreview();
  updateTypePreview();
});
</script>
@endpush
@endsection
