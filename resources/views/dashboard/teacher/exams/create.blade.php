@extends('layouts.app')
@section('title', 'ساخت آزمون جدید - فنی و حرفه‌ای')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/exam-wizard.css') }}">
@endpush

@section('content')
    <div class="create-exam-container">

        {{-- ========== HEADER ========== --}}
        <div class="page-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>
                        <span
                            style="background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
                        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            ساخت آزمون جدید 
                        </span> 🔧
                    </h1>
<p class="page-subtitle">آزمون خود را برای هنرجویان به صورت مرحله‌ای ایجاد کنید.</p>

                </div>
                <a href="{{ route('teacher.exams.index') }}" class="btn-back">
                    <i class="fas fa-arrow-right"></i>
                    بازگشت به لیست آزمون‌ها
                </a>
            </div>
        </div>

        {{-- ========== PROGRESS BAR ========== --}}
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 14%;"></div>
            </div>
            <div class="progress-steps">
                @php
                    $steps = [
                        1 => 'نوع آزمون',
                        2 => 'پایه تحصیلی',
                        3 => 'شاخه تحصیلی',
                        4 => 'زمینه فنی',
                        5 => 'زیررشته',
                        6 => 'دسته درسی',
                        7 => 'انتخاب درس',
                        8 => 'جزئیات',
                    ];
                @endphp
                @foreach ($steps as $num => $name)
                    <div class="step-item @if ($num == 1) active @endif" data-step="{{ $num }}">
                        <div class="step-number">{{ $num }}</div>
                        <div class="step-name">{{ $name }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ========== FORM CONTAINER ========== --}}
        <div class="form-container">
            <form method="POST" action="{{ route('teacher.exams.store') }}" id="examForm"
                onsubmit="return validateFinalStep()">
                @csrf

                {{-- Hidden Inputs (UUID expected by controller) --}}
                <input type="hidden" name="exam_type" id="examType" value="">
                <input type="hidden" name="classroom_id" id="classroomId" value="{{ $selectedClassroomId ?? '' }}">

                <input type="hidden" name="section_id" id="sectionId" value="">
                <input type="hidden" name="grade_id" id="gradeId" value="">
                <input type="hidden" name="branch_id" id="branchId" value="">
                <input type="hidden" name="field_id" id="fieldId" value="">
                <input type="hidden" name="subfield_id" id="subfieldId" value="">
                <input type="hidden" name="subject_type_id" id="subjectTypeId" value="">
                <input type="hidden" name="subjects" id="subjectsInput" value="">

                {{-- ===== STEP 1: EXAM TYPE ===== --}}
                <div class="form-section active" id="step1">
                    <div class="section-header">
                        <div class="section-icon">🎯</div>
                        <h2 class="section-title">نوع آزمون را انتخاب کنید</h2>
                        <p class="section-description">
                            بر اساس نیاز آموزشی خود، یکی از گزینه‌های زیر را انتخاب نمایید.
                        </p>
                    </div>

                    <div class="exam-type-grid">
<div class="type-card" data-type="public" onclick="selectExamType('public')">
    <span class="type-badge type-badge--public">آزمون</span>
    <div class="type-icon">🌐</div>
    <h4>آزمون</h4>
    <p>آزمونی آزاد برای عموم دانش‌آموزان؛ بدون وابستگی به کلاس.</p>
</div>


                        <div class="type-card" data-type="class_single" onclick="selectExamType('class_single')">
                            <div class="type-icon">📚</div>
                            <div class="type-title">کلاسی تک درس</div>
                            <p class="type-description">برای یک کلاس خاص و فقط یک درس مشخص.</p>
                            <div class="type-badge">تخصصی</div>
                        </div>

                        <div class="type-card" data-type="class_comprehensive"
                            onclick="selectExamType('class_comprehensive')">
                            <div class="type-icon">🎓</div>
                            <div class="type-title">کلاسی جامع</div>
                            <p class="type-description">برای یک کلاس شامل تمام دروس پایه.</p>
                            <div class="type-badge">جامع</div>
                        </div>
                    </div>

                    {{-- انتخاب کلاس --}}
                    <div id="classroomSelectionSection" style="display: none; margin-top: 30px;">
                        <div class="section-header" style="margin-bottom: 20px;">
                            <h3 class="section-title">انتخاب کلاس</h3>
                            <p class="section-description">
                                لطفاً کلاس مورد نظر را انتخاب کنید یا یک کلاس جدید ایجاد نمایید.
                            </p>
                        </div>

                        <div id="existingClassroomsContainer" class="selection-grid"
                            style="grid-template-columns: repeat(2, 1fr);">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                                در حال بارگذاری کلاس‌ها...
                            </div>
                        </div>

                        <div id="createNewClassContainer" style="margin-top: 25px; text-align: center;">
                            <div class="type-card" onclick="createNewClassroom()"
                                style="max-width: 400px; margin: 0 auto; cursor: pointer;
                            background: linear-gradient(135deg, rgba(0, 206, 209, 0.1), rgba(70, 130, 180, 0.1));">
                                <div class="type-icon">➕</div>
                                <div class="type-title">ایجاد کلاس جدید</div>
                                <p class="type-description">هنوز کلاسی ندارید؟ یک کلاس جدید ایجاد کنید.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 2: GRADE (dynamic) ===== --}}
                <div class="form-section" id="step2">
                    <div class="section-header">
                        <div class="section-icon">📊</div>
                        <h2 class="section-title">پایه تحصیلی را انتخاب کنید</h2>
                        <p class="section-description">پایه مورد نظر برای آزمون خود را انتخاب نمایید.</p>
                    </div>
                    <div class="selection-grid" id="gradesGrid" style="grid-template-columns: repeat(3, 1fr);">
                        {{-- ajax --}}
                    </div>
                </div>

                {{-- ===== STEP 3: BRANCH (dynamic) ===== --}}
                <div class="form-section" id="step3">
                    <div class="section-header">
                        <div class="section-icon">🎓</div>
                        <h2 class="section-title">شاخه تحصیلی را انتخاب کنید</h2>
                    </div>
                    <div class="selection-grid" id="branchesGrid" style="grid-template-columns: repeat(2, 1fr);"></div>
                </div>

                {{-- ===== STEP 4: FIELD (dynamic) ===== --}}
                <div class="form-section" id="step4">
                    <div class="section-header">
                        <div class="section-icon">🏭</div>
                        <h2 class="section-title">زمینه فنی را انتخاب کنید</h2>
                    </div>
                    <div class="selection-grid" id="fieldsGrid" style="grid-template-columns: repeat(2, 1fr);"></div>
                </div>

                {{-- ===== STEP 5: SUBFIELD (dynamic) ===== --}}
                <div class="form-section" id="step5">
                    <div class="section-header">
                        <div class="section-icon">🔬</div>
                        <h2 class="section-title">زیررشته را انتخاب کنید</h2>
                    </div>
                    <div class="selection-grid" id="subfieldGrid" style="grid-template-columns: repeat(2, 1fr);"></div>
                </div>

                {{-- ===== STEP 6: SUBJECT TYPE (dynamic) ===== --}}
                <div class="form-section" id="step6">
                    <div class="section-header">
                        <div class="section-icon">📚</div>
                        <h2 class="section-title">دسته درسی را انتخاب کنید</h2>
                    </div>
                    <div class="selection-grid" id="subjectTypesGrid" style="grid-template-columns: repeat(3, 1fr);">
                    </div>

                    <div class="coefficient-settings" id="coefficientSettings"></div>
                </div>

                {{-- ===== STEP 7: SUBJECTS ===== --}}
                <div class="form-section" id="step7">
                    <div class="section-header">
                        <div class="section-icon">📖</div>
                        <h2 class="section-title">درس‌های آزمون را انتخاب کنید</h2>
                    </div>

                    <div class="subject-selection">
                        <div id="subjectsContainer">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                                در حال بارگذاری دروس...
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 8: DETAILS ===== --}}
                <div class="form-section" id="step8">
                    <div class="section-header">
                        <div class="section-icon">📋</div>
                        <h2 class="section-title">جزئیات آزمون را تکمیل کنید</h2>
                    </div>

                    <div class="preview-section">
                        <div class="preview-title">
                            <i class="fas fa-eye"></i>
                            پیش‌نمایش آزمون
                        </div>
                        <div class="preview-grid">
                            <div class="preview-item">
                                <div class="preview-label">نوع آزمون</div>
                                <div class="preview-value" id="previewExamType">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">پایه تحصیلی</div>
                                <div class="preview-value" id="previewGrade">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">شاخه تحصیلی</div>
                                <div class="preview-value" id="previewBranch">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">زمینه فنی</div>
                                <div class="preview-value" id="previewField">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">زیررشته</div>
                                <div class="preview-value" id="previewSubfield">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">دسته درسی</div>
                                <div class="preview-value" id="previewSubjectType">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">تعداد دروس</div>
                                <div class="preview-value" id="previewSubjectsCount">--</div>
                            </div>
                            <div class="preview-item">
                                <div class="preview-label">کل سوالات</div>
                                <div class="preview-value" id="previewTotalQuestions">--</div>
                            </div>
                        </div>
                    </div>

                    <div class="details-form">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-heading"></i>عنوان آزمون</label>
                            <input type="text" name="title" class="form-input" id="examTitle"
                                placeholder="مثال: آزمون کارگاه برق صنعتی - پایه یازدهم" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i>مدت زمان آزمون (دقیقه)</label>
                            <input type="number" name="duration" class="form-input" value="90" min="15"
                                max="300" step="5" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-align-left"></i>توضیحات آزمون</label>
                            <textarea name="description" class="form-textarea" rows="4" placeholder="هدف آزمون، منابع مطالعاتی، نکات ..."></textarea>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck"
                                    value="1" checked>
                                <label class="form-check-label" for="activeCheck">آزمون بلافاصله فعال شود</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NAV BUTTONS --}}
                <div class="nav-buttons">
                    <button type="button" class="btn-nav btn-prev" onclick="prevStep()" style="display:none;">
                        <i class="fas fa-arrow-right"></i> مرحله قبل
                    </button>
                    <button type="button" class="btn-nav btn-next" onclick="nextStep()">
                        مرحله بعد <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="submit" class="btn-nav btn-submit" style="display:none;">
                        <i class="fas fa-check"></i> ایجاد آزمون
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ✅ createNewClassroom حفظ شده --}}
    <script>
        async function createNewClassroom() {

            const endpoints = {
                sections: "/dashboard/teacher/exams/data/sections",
                grades: "/dashboard/teacher/exams/data/grades",
                branches: "/dashboard/teacher/exams/data/branches",
                fields: "/dashboard/teacher/exams/data/fields",
                subfields: "/dashboard/teacher/exams/data/subfields",
                subjects: "/dashboard/teacher/exams/data/subjects",
            };

            const getJSON = async (url) => {
                const res = await fetch(url, {
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                if (!res.ok) throw new Error("network");
                return res.json();
            };

            const opt = (items, placeholder = "-- انتخاب کنید --", labelKey = "name_fa") => {
                let html = `<option value="">${placeholder}</option>`;
                items.forEach(i => {
                    html +=
                        `<option value="${i.id}" data-name="${i[labelKey] || i.title_fa || ''}">${i[labelKey] || i.title_fa}</option>`;
                });
                return html;
            };

            // قدم اول: لود مقطع
            let sections = [];
            try {
                const s = await getJSON(endpoints.sections);
                sections = s.sections || [];
            } catch (e) {
                Swal.fire("خطا", "دریافت مقاطع ممکن نیست", "error");
                return;
            }

            Swal.fire({
                title: 'ایجاد کلاس جدید',
                html: `
        <div style="text-align:right">

            <label class="mb-2 fw-bold d-block">🎓 مقطع</label>
            <select id="cc_section" class="swal2-input">${opt(sections, "مقطع را انتخاب کنید")}</select>

            <label class="mb-2 fw-bold d-block mt-3">📊 پایه</label>
            <select id="cc_grade" class="swal2-input" disabled>
                <option value="">ابتدا مقطع را انتخاب کنید</option>
            </select>

            <label class="mb-2 fw-bold d-block mt-3">🧩 شاخه / رشته</label>
            <select id="cc_branch" class="swal2-input" disabled>
                <option value="">ابتدا پایه را انتخاب کنید</option>
            </select>

            <label class="mb-2 fw-bold d-block mt-3">🏭 زمینه آموزشی</label>
            <select id="cc_field" class="swal2-input" disabled>
                <option value="">ابتدا شاخه را انتخاب کنید</option>
            </select>

            <label class="mb-2 fw-bold d-block mt-3">🔬 زیررشته</label>
            <select id="cc_subfield" class="swal2-input" disabled>
                <option value="">ابتدا زمینه را انتخاب کنید</option>
            </select>

            <label class="mb-2 fw-bold d-block mt-3">📖 درس</label>
            <select id="cc_subject" class="swal2-input" disabled>
                <option value="">ابتدا زیررشته را انتخاب کنید</option>
            </select>

            <label class="mb-2 fw-bold d-block mt-3">🏷️ نام کلاس</label>
            <input type="text" id="cc_title" class="swal2-input" placeholder="مثال: کلاس یازدهم شبکه - پایگاه داده">

        </div>
        `,
                showCancelButton: true,
                confirmButtonText: "ایجاد کلاس",
                cancelButtonText: "انصراف",
                reverseButtons: true,
                width: 650,
                didOpen: () => {

                    const elSection = document.getElementById("cc_section");
                    const elGrade = document.getElementById("cc_grade");
                    const elBranch = document.getElementById("cc_branch");
                    const elField = document.getElementById("cc_field");
                    const elSubfield = document.getElementById("cc_subfield");
                    const elSubject = document.getElementById("cc_subject");

                    // ====== مقطع → پایه‌ها ======
                    elSection.addEventListener("change", async () => {
                        const sectionId = elSection.value;

                        elGrade.innerHTML = `<option>در حال بارگذاری...</option>`;
                        elGrade.disabled = true;

                        elBranch.innerHTML =
                            `<option value="">ابتدا پایه را انتخاب کنید</option>`;
                        elBranch.disabled = true;

                        elField.innerHTML =
                            `<option value="">ابتدا شاخه را انتخاب کنید</option>`;
                        elField.disabled = true;

                        elSubfield.innerHTML =
                            `<option value="">ابتدا زمینه را انتخاب کنید</option>`;
                        elSubfield.disabled = true;

                        elSubject.innerHTML =
                            `<option value="">ابتدا زیررشته را انتخاب کنید</option>`;
                        elSubject.disabled = true;

                        if (!sectionId) {
                            elGrade.innerHTML =
                                `<option value="">ابتدا مقطع را انتخاب کنید</option>`;
                            return;
                        }

                        const g = await getJSON(`${endpoints.grades}?section_id=${sectionId}`);
                        elGrade.innerHTML = opt(g.grades || [], "پایه را انتخاب کنید");
                        elGrade.disabled = false;
                    });

                    // ====== پایه → شاخه‌ها ======
                    elGrade.addEventListener("change", async () => {
                        const sectionId = elSection.value;

                        elBranch.innerHTML = `<option>در حال بارگذاری...</option>`;
                        elBranch.disabled = true;

                        elField.innerHTML =
                            `<option value="">ابتدا شاخه را انتخاب کنید</option>`;
                        elField.disabled = true;

                        elSubfield.innerHTML =
                            `<option value="">ابتدا زمینه را انتخاب کنید</option>`;
                        elSubfield.disabled = true;

                        elSubject.innerHTML =
                            `<option value="">ابتدا زیررشته را انتخاب کنید</option>`;
                        elSubject.disabled = true;

                        if (!sectionId) return;

                        const b = await getJSON(
                        `${endpoints.branches}?section_id=${sectionId}`);
                        elBranch.innerHTML = opt(b.branches || [], "شاخه/رشته را انتخاب کنید");
                        elBranch.disabled = false;
                    });

                    // ====== شاخه → زمینه‌ها ======
                    elBranch.addEventListener("change", async () => {
                        const branchId = elBranch.value;

                        elField.innerHTML = `<option>در حال بارگذاری...</option>`;
                        elField.disabled = true;

                        elSubfield.innerHTML =
                            `<option value="">ابتدا زمینه را انتخاب کنید</option>`;
                        elSubfield.disabled = true;

                        elSubject.innerHTML =
                            `<option value="">ابتدا زیررشته را انتخاب کنید</option>`;
                        elSubject.disabled = true;

                        if (!branchId) {
                            elField.innerHTML =
                                `<option value="">ابتدا شاخه را انتخاب کنید</option>`;
                            return;
                        }

                        const f = await getJSON(`${endpoints.fields}?branch_id=${branchId}`);
                        elField.innerHTML = opt(f.fields || [], "زمینه را انتخاب کنید");
                        elField.disabled = false;
                    });

                    // ====== زمینه → زیررشته ======
                    elField.addEventListener("change", async () => {
                        const fieldId = elField.value;

                        elSubfield.innerHTML = `<option>در حال بارگذاری...</option>`;
                        elSubfield.disabled = true;

                        elSubject.innerHTML =
                            `<option value="">ابتدا زیررشته را انتخاب کنید</option>`;
                        elSubject.disabled = true;

                        if (!fieldId) {
                            elSubfield.innerHTML =
                                `<option value="">ابتدا زمینه را انتخاب کنید</option>`;
                            return;
                        }

                        const sf = await getJSON(`${endpoints.subfields}?field_id=${fieldId}`);
                        elSubfield.innerHTML = opt(sf.subfields || [],
                        "زیررشته را انتخاب کنید");
                        elSubfield.disabled = false;
                    });

                    // ====== زیررشته → دروس ======
                    elSubfield.addEventListener("change", async () => {
                        const gradeId = elGrade.value;
                        const branchId = elBranch.value;
                        const fieldId = elField.value;
                        const subfieldId = elSubfield.value;

                        elSubject.innerHTML = `<option>در حال بارگذاری...</option>`;
                        elSubject.disabled = true;

                        if (!subfieldId) {
                            elSubject.innerHTML =
                                `<option value="">ابتدا زیررشته را انتخاب کنید</option>`;
                            return;
                        }

                        const params = new URLSearchParams();
                        if (gradeId) params.append("grade_id", gradeId);
                        if (branchId) params.append("branch_id", branchId);
                        if (fieldId) params.append("field_id", fieldId);
                        if (subfieldId) params.append("subfield_id", subfieldId);

                        const sub = await getJSON(`${endpoints.subjects}?${params.toString()}`);
                        elSubject.innerHTML = opt(sub.subjects || [], "درس را انتخاب کنید",
                            "title_fa");
                        elSubject.disabled = false;
                    });

                },
                preConfirm: () => {
                    const sectionId = document.getElementById("cc_section").value;
                    const gradeId = document.getElementById("cc_grade").value;
                    const branchId = document.getElementById("cc_branch").value;
                    const fieldId = document.getElementById("cc_field").value;
                    const subfieldId = document.getElementById("cc_subfield").value;
                    const subjectId = document.getElementById("cc_subject").value;
                    const title = document.getElementById("cc_title").value.trim();

                    if (!sectionId || !gradeId || !branchId || !fieldId || !subfieldId || !subjectId) {
                        Swal.showValidationMessage("لطفاً همه موارد آموزشی را انتخاب کنید.");
                        return false;
                    }
                    if (!title) {
                        Swal.showValidationMessage("نام کلاس الزامی است.");
                        return false;
                    }

                    const getName = (selectId) => {
                        const el = document.getElementById(selectId);
                        return el.options[el.selectedIndex]?.dataset?.name || el.options[el
                            .selectedIndex]?.text || "";
                    };

                    return {
                        section_id: sectionId,
                        grade_id: gradeId,
                        branch_id: branchId,
                        field_id: fieldId,
                        subfield_id: subfieldId,
                        subject_id: subjectId,

                        section_name: getName("cc_section"),
                        grade_name: getName("cc_grade"),
                        branch_name: getName("cc_branch"),
                        field_name: getName("cc_field"),
                        subfield_name: getName("cc_subfield"),
                        subject_name: getName("cc_subject"),

                        title
                    };
                }
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                const data = result.value;

                Swal.fire({
                    title: "در حال ایجاد کلاس...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    const fd = new FormData();
                    fd.append("title", data.title);
                    fd.append("section_id", data.section_id);
                    fd.append("grade_id", data.grade_id);
                    fd.append("branch_id", data.branch_id);
                    fd.append("field_id", data.field_id);
                    fd.append("subfield_id", data.subfield_id);
                    fd.append("subject_id", data.subject_id);
                    fd.append("is_active", 1);

                    // metadata برای نمایش خوانا
                    fd.append("metadata", JSON.stringify({
                        section_name: data.section_name,
                        grade_name: data.grade_name,
                        branch_name: data.branch_name,
                        field_name: data.field_name,
                        subfield_name: data.subfield_name,
                        subject_name: data.subject_name
                    }));

                    const res = await fetch("{{ route('teacher.classes.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        },
                        body: fd
                    });

                    const responseData = await res.json();
                    Swal.close();

                    if (responseData.success) {
                        Swal.fire("✅ موفقیت", "کلاس ایجاد شد", "success").then(() => {
                            loadExistingClassrooms();

                            if (responseData.classroom) {
                                setTimeout(() => {
                                    selectClassroom({
                                            target: document.querySelector(
                                                `[data-classroom-id="${responseData.classroom.id}"]`
                                                )
                                        },
                                        responseData.classroom.id,
                                        responseData.classroom.title
                                    );
                                }, 300);
                            }
                        });
                    } else {
                        Swal.fire("❌ خطا", responseData.message || "خطا در ایجاد کلاس", "error");
                    }

                } catch (e) {
                    console.error(e);
                    Swal.close();
                    Swal.fire("❌ خطای شبکه", "ارتباط با سرور مشکل دارد.", "error");
                }
            });
        }
    </script>


    {{-- ✅ exam wizard main js --}}
    <script src="{{ asset('assets/js/exam-wizard.js') }}"></script>
@endpush
