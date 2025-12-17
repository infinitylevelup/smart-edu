<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.js"></script>

@php
// تبدیل subjects به آرایه ساده با ID و UUID
$subjectsArray = [];
if (isset($subjects) && $subjects->count() > 0) {
    foreach ($subjects as $subject) {
        $subjectsArray[] = [
            'id' => $subject->id ?? null,
            'uuid' => $subject->uuid ?? null,
            'title_fa' => $subject->title_fa ?? '',
            'slug' => $subject->slug ?? '',
            'code' => $subject->code ?? '',
            'grade_id' => $subject->grade_id ?? null,
            'branch_id' => $subject->branch_id ?? null,
            'field_id' => $subject->field_id ?? null,
            'subfield_id' => $subject->subfield_id ?? null,
            'subject_type_id' => $subject->subject_type_id ?? null,
        ];
    }
}

$examWizardData = [
    'branches' => $branches ?? [],
    'fields' => $fields ?? [],
    'subfields' => $subfields ?? [],
    'subjects' => $subjectsArray,
    'classrooms' => $classrooms ?? [],
];
@endphp

<script>
window.examWizardData = @json($examWizardData);

// داده‌های جلسه
let examData = {
    exam_type: null,
    classroom_id: null,
    classroom_type: null,
    grade_id: null,
    section_id: null,
    branch_id: null,
    field_id: null,
    subfield_id: null,
    subject_type_id: null,
    selected_subjects: [],
    current_step: 1
};

// مقداردهی اولیه
$(document).ready(function() {
    console.log('🎯 Exam Wizard Initialized');
    
    // تاریخ‌پیکر فارسی
    if ($.fn.persianDatepicker !== undefined) {
        $("#start_at").persianDatepicker({
            format: "YYYY/MM/DD HH:mm",
            timePicker: { enabled: true }
        });
        
        $("#end_at").persianDatepicker({
            format: "YYYY/MM/DD HH:mm",
            timePicker: { enabled: true }
        });
    }
    
    // ============================================
    // STEP 1 - انتخاب نوع آزمون
    // ============================================
    
    // رویدادهای انتخاب نوع آزمون
    $('.exam-type-card').click(function() {
        const type = $(this).data('type');
        
        // حذف انتخاب قبلی
        $('.exam-type-card').removeClass('selected');
        $(this).addClass('selected');
        
        // ذخیره نوع آزمون
        examData.exam_type = type;
        $('#exam_type').val(type);
        
        // نمایش/مخفی کردن بخش کلاس
        if (type === 'class') {
            $('#classExamBox').slideDown(300);
        } else {
            $('#classExamBox').slideUp(300);
            examData.classroom_id = null;
            examData.classroom_type = null;
            $('#classroom_id').val('');
            $('#classroom_type').val('');
        }
        
        // فعال کردن دکمه بعدی
        validateStep1();
    });
    
    // رویدادهای انتخاب نوع کلاس
    $('.class-type-card').click(function() {
        const type = $(this).attr('id') === 'classTypeSingle' ? 'single' : 'comprehensive';
        
        // حذف انتخاب قبلی
        $('.class-type-card').removeClass('selected');
        $(this).addClass('selected');
        
        // ذخیره نوع کلاس
        examData.classroom_type = type;
        $('#classroom_type').val(type);
        
        // نمایش بخش انتخاب کلاس
        $('#classSelectionArea').slideDown(300);
        
        // نمایش کلاس‌های مربوطه
        if (type === 'single') {
            $('#singleClassSection').slideDown();
            $('#comprehensiveClassSection').slideUp();
        } else {
            $('#singleClassSection').slideUp();
            $('#comprehensiveClassSection').slideDown();
        }
    });
    
    // رویداد انتخاب کلاس
    $(document).on('click', '.select-class', function() {
        const classItem = $(this).closest('.class-item');
        const classId = classItem.data('id');
        const classType = classItem.data('type');
        
        // حذف انتخاب قبلی
        $('.class-item').removeClass('selected');
        classItem.addClass('selected');
        
        // ذخیره اطلاعات کلاس
        examData.classroom_id = classId;
        $('#classroom_id').val(classId);
        
        // به‌روزرسانی دکمه انتخاب
        $(this).text('✓ انتخاب شد').addClass('btn-success').removeClass('btn-outline-primary');
        
        validateStep1();
    });
    
    // ============================================
    // STEP 2 - دسته‌بندی آموزشی
    // ============================================
    
    // رویداد تغییر پایه - FIXED برای section_id
    $('#gradeSelect').on('change', function() {
        const gradeId = $(this).val();
        const gradeOption = $(this).find('option:selected');
        const sectionId = gradeOption.data('section');
        
        console.log('Grade changed - ID:', gradeId, 'Section:', sectionId);
        
        // ذخیره اطلاعات
        examData.grade_id = gradeId;
        examData.section_id = sectionId; // ✅ ذخیره section_id در examData
        
        $('#grade_id').val(gradeId);
        $('#section_id').val(sectionId); // ✅ پر کردن hidden input
        
        // پر کردن شاخه‌ها
        if (sectionId && window.examWizardData.branches) {
            const branches = window.examWizardData.branches.filter(b => 
                String(b.section_id) === String(sectionId)
            );
            
            $('#branchSelect').html('<option value="">انتخاب شاخه...</option>');
            if (branches.length) {
                branches.forEach(b => {
                    $('#branchSelect').append(
                        `<option value="${b.id}">${b.name_fa || b.name || b.slug}</option>`
                    );
                });
                $('#branchSelect').prop('disabled', false);
            }
        }
        
        validateStep2();
    });
    
    // رویداد تغییر شاخه
    $('#branchSelect').on('change', function() {
        const branchId = $(this).val();
        examData.branch_id = branchId;
        $('#branch_id').val(branchId);
        
        // پر کردن زمینه‌ها
        if (branchId && window.examWizardData.fields) {
            const fields = window.examWizardData.fields.filter(f => 
                String(f.branch_id) === String(branchId)
            );
            
            $('#fieldSelect').html('<option value="">انتخاب زمینه...</option>');
            if (fields.length) {
                fields.forEach(f => {
                    $('#fieldSelect').append(
                        `<option value="${f.id}">${f.name_fa || f.name || f.slug}</option>`
                    );
                });
                $('#fieldSelect').prop('disabled', false);
            }
        }
        
        // ریست زیررشته
        $('#subfieldSelect').html('<option value="">ابتدا زمینه را انتخاب کنید...</option>')
            .prop('disabled', true);
        examData.subfield_id = null;
        $('#subfield_id').val('');
        
        validateStep2();
    });
    
    // رویداد تغییر زمینه - FIXED برای subfield
    $('#fieldSelect').on('change', function() {
        const fieldId = $(this).val();
        examData.field_id = fieldId;
        $('#field_id').val(fieldId);
        
        // پر کردن زیررشته‌ها
        if (fieldId && window.examWizardData.subfields) {
            const subfields = window.examWizardData.subfields.filter(sf => 
                String(sf.field_id) === String(fieldId)
            );
            
            $('#subfieldSelect').html('<option value="">انتخاب زیررشته...</option>');
            if (subfields.length) {
                subfields.forEach(sf => {
                    $('#subfieldSelect').append(
                        `<option value="${sf.id}">${sf.name_fa || sf.name || sf.slug}</option>`
                    );
                });
                $('#subfieldSelect').prop('disabled', false);
            } else {
                $('#subfieldSelect').html('<option value="">هیچ زیررشته‌ای یافت نشد</option>');
            }
        } else {
            $('#subfieldSelect').html('<option value="">ابتدا زمینه را انتخاب کنید...</option>')
                .prop('disabled', true);
        }
        
        validateStep2();
    });
    
    // رویداد تغییر زیررشته - FIXED (نسخه بهبود یافته)
    $('#subfieldSelect').on('change', function() {
        const subfieldId = $(this).val();
        
        // ذخیره در examData
        examData.subfield_id = subfieldId;
        
        // 🔥 FIX: مطمئن شو hidden input پر می‌شود
        $('#subfield_id').val(subfieldId);
        
        console.log('✅ subfield_id updated:', subfieldId, 'examData.subfield_id:', examData.subfield_id);
        validateStep2();
    });
    
    // ============================================
    // STEP 3 - انتخاب درس‌ها
    // ============================================
    
    // رویداد تغییر نوع درس
    $('#subjectTypeSelect').on('change', function() {
        const typeId = $(this).val();
        examData.subject_type_id = typeId;
        $('#subject_type_id').val(typeId);
        
        // بارگذاری درس‌ها
        loadSubjects();
        validateStep3();
    });
    
    // ============================================
    // STEP 4 - تنظیمات آزمون
    // ============================================
    
    // دکمه‌های AI
    $('#aiTitleBtn').click(suggestTitle);
    $('#aiDescBtn').click(suggestDescription);
    
    // رویداد تغییر فرم
    $('#title, #description, #duration_minutes, #passing_score').on('input', function() {
        validateStep4();
        updatePreview();
    });
    
    // ============================================
    // ناوبری و ارسال
    // ============================================
    
    // دکمه‌های ناوبری
    $('#nextBtn').click(nextStep);
    $('#prevBtn').click(prevStep);
    
    // رویداد ارسال فرم - نسخه اصلاح شده
    $('#examForm').on('submit', function(e) {
        console.log('=== FORM SUBMIT VALIDATION ===');
        
        // 🔥 FIX نهایی: مطمئن شو همه hidden inputs پر شده‌اند
        syncAllHiddenInputs();
        
        // بررسی نهایی مقادیر hidden
        if (!validateAllSteps()) {
            e.preventDefault();
            
            // نمایش جزئیات مشکل
            const problems = getMissingFields();
            
            alert('فیلدهای خالی یا نادرست:\n' + problems.join('\n'));
            return false;
        }
        
        // نمایش تأیید نهایی
        if (!confirm('آیا از ایجاد این آزمون اطمینان دارید؟\n\nتوجه: نوع آزمون پس از ایجاد غیرقابل تغییر خواهد بود.')) {
            e.preventDefault();
            return false;
        }
        
        // نمایش لودینگ
        $('#submitBtn').html('<i class="bi bi-hourglass-split"></i> در حال ایجاد...').prop('disabled', true);
    });
    
    // 🔥 FIX: همگام‌سازی دوره‌ای hidden inputs
    setInterval(syncAllHiddenInputs, 1000);
});

// ============================================
// توابع اصلی
// ============================================

function nextStep() {
    if (!validateCurrentStep()) {
        showError('لطفاً این مرحله را کامل کنید.');
        return;
    }
    
    const currentStep = examData.current_step;
    const nextStep = currentStep + 1;
    
    // مخفی کردن مرحله فعلی
    $(`#step${currentStep}`).removeClass('active');
    $(`#stepIndicator${currentStep}`).removeClass('active');
    
    // نمایش مرحله بعدی
    $(`#step${nextStep}`).addClass('active');
    $(`#stepIndicator${nextStep}`).addClass('active');
    
    // به‌روزرسانی وضعیت
    examData.current_step = nextStep;
    updateNavigationButtons();
    
    // به‌روزرسانی پیش‌نمایش در مرحله 5
    if (nextStep === 5) {
        updatePreview();
    }
}

function prevStep() {
    const currentStep = examData.current_step;
    const prevStep = currentStep - 1;
    
    if (prevStep < 1) return;
    
    // مخفی کردن مرحله فعلی
    $(`#step${currentStep}`).removeClass('active');
    $(`#stepIndicator${currentStep}`).removeClass('active');
    
    // نمایش مرحله قبلی
    $(`#step${prevStep}`).addClass('active');
    $(`#stepIndicator${prevStep}`).addClass('active');
    
    // به‌روزرسانی وضعیت
    examData.current_step = prevStep;
    updateNavigationButtons();
}

// ============================================
// توابع اعتبارسنجی
// ============================================

function validateCurrentStep() {
    switch(examData.current_step) {
        case 1:
            return validateStep1();
        case 2:
            return validateStep2();
        case 3:
            return validateStep3();
        case 4:
            return validateStep4();
        default:
            return true;
    }
}

function validateStep1() {
    const isValid = examData.exam_type !== null;
    
    if (examData.exam_type === 'class') {
        return isValid && examData.classroom_id !== null && examData.classroom_type !== null;
    }
    
    return isValid;
}

function validateStep2() {
    // برای آزمون عمومی
    if (examData.exam_type === 'public') {
        return examData.grade_id !== null && 
               examData.branch_id !== null && 
               examData.field_id !== null && 
               examData.subfield_id !== null; // ✅ subfield_id الزامی است
    }
    
    // برای آزمون کلاسی نیاز به این فیلدها نیست
    return true;
}

function validateStep3() {
    // فقط برای آزمون عمومی نیاز به انتخاب درس داریم
    if (examData.exam_type === 'public') {
        return examData.subject_type_id !== null && 
               examData.selected_subjects.length > 0;
    }
    
    // برای آزمون کلاسی، درس‌ها از کلاس گرفته می‌شوند
    return true;
}

function validateStep4() {
    const title = $('#title').val().trim();
    const duration = $('#duration_minutes').val();
    
    return title.length >= 3 && duration && duration > 0;
}

function validateAllSteps() {
    return validateStep1() && validateStep2() && validateStep3() && validateStep4();
}

// 🔥 FIX: همگام‌سازی همه hidden inputs
function syncAllHiddenInputs() {
    // 1. exam_type
    if (examData.exam_type && !$('#exam_type').val()) {
        $('#exam_type').val(examData.exam_type);
    }
    
    // 2. grade_id و section_id
    if (examData.grade_id && !$('#grade_id').val()) {
        $('#grade_id').val(examData.grade_id);
    }
    
    // 3. section_id از grade
    if ($('#gradeSelect').val() && !$('#section_id').val()) {
        const sectionId = $('#gradeSelect option:selected').data('section');
        if (sectionId) {
            $('#section_id').val(sectionId);
            examData.section_id = sectionId;
        }
    }
    
    // 4. subfield_id - FIX اصلی
    const subfieldSelectVal = $('#subfieldSelect').val();
    if (subfieldSelectVal && !$('#subfield_id').val()) {
        $('#subfield_id').val(subfieldSelectVal);
        examData.subfield_id = subfieldSelectVal;
    }
    
    // 5. subjects_json
    if (examData.selected_subjects.length > 0 && !$('#subjects_json').val()) {
        $('#subjects_json').val(JSON.stringify(examData.selected_subjects));
    }
    
    // 6. سایر فیلدها
    const hiddenFields = ['branch_id', 'field_id', 'subject_type_id'];
    hiddenFields.forEach(field => {
        const examDataVal = examData[field];
        const hiddenInput = $(`#${field}`);
        if (examDataVal && !hiddenInput.val()) {
            hiddenInput.val(examDataVal);
        }
    });
}

// 🔥 FIX: دریافت فیلدهای خالی
function getMissingFields() {
    const problems = [];
    
    if (!examData.exam_type) problems.push('نوع آزمون');
    
    if (examData.exam_type === 'public') {
        if (!examData.grade_id) problems.push('پایه');
        if (!examData.branch_id) problems.push('شاخه');
        if (!examData.field_id) problems.push('زمینه');
        if (!examData.subfield_id) problems.push('زیررشته');
        if (!examData.subject_type_id) problems.push('نوع درس');
        if (examData.selected_subjects.length === 0) problems.push('درس‌ها');
    } else if (examData.exam_type === 'class') {
        if (!examData.classroom_id) problems.push('کلاس');
        if (!examData.classroom_type) problems.push('نوع کلاس');
    }
    
    if (!$('#title').val().trim()) problems.push('عنوان آزمون');
    if (!$('#duration_minutes').val()) problems.push('مدت زمان آزمون');
    
    return problems;
}

function updateNavigationButtons() {
    const currentStep = examData.current_step;
    
    // دکمه قبلی
    if (currentStep === 1) {
        $('#prevBtn').hide();
    } else {
        $('#prevBtn').show();
    }
    
    // دکمه بعدی/ارسال
    if (currentStep === 5) {
        $('#nextBtn').hide();
        $('#submitBtn').removeClass('d-none');
    } else {
        $('#nextBtn').show();
        $('#submitBtn').addClass('d-none');
    }
}

// ============================================
// توابع کمکی
// ============================================

// بارگذاری درس‌ها
function loadSubjects() {
    if (!examData.subject_type_id || !window.examWizardData.subjects) {
        return;
    }
    
    const container = $('#subjectsContainer');
    container.html('');
    
    // فیلتر کردن درس‌ها بر اساس نوع
    const subjects = window.examWizardData.subjects.filter(subject => {
        return subject.subject_type_id == examData.subject_type_id &&
               subject.grade_id == examData.grade_id &&
               subject.branch_id == examData.branch_id &&
               subject.field_id == examData.field_id &&
               subject.subfield_id == examData.subfield_id;
    });
    
    if (subjects.length === 0) {
        container.html(`
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    هیچ درسی برای این دسته‌بندی پیدا نشد.
                </div>
            </div>
        `);
        return;
    }
    
    // نمایش درس‌ها
    subjects.forEach(subject => {
        const isSelected = examData.selected_subjects.some(s => s.id === subject.id);
        
        const subjectHtml = `
            <div class="col-md-4 col-sm-6">
                <div class="subject-card ${isSelected ? 'selected' : ''}" 
                     data-id="${subject.id}" 
                     data-uuid="${subject.uuid}">
                    <div class="subject-icon">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="subject-title">${subject.title_fa}</div>
                    <div class="subject-code">${subject.code || 'بدون کد'}</div>
                </div>
            </div>
        `;
        
        container.append(subjectHtml);
    });
    
    // رویداد کلیک روی درس‌ها
    $('.subject-card').click(function() {
        const subjectId = $(this).data('id');
        const subjectUuid = $(this).data('uuid');
        const subjectTitle = $(this).find('.subject-title').text();
        
        // اگر آزمون تک‌درس است، فقط یک درس انتخاب شود
        if (examData.classroom_type === 'single' && examData.selected_subjects.length >= 1) {
            examData.selected_subjects = []; // پاک کردن همه
            $('.subject-card').removeClass('selected');
        }
        
        // بررسی آیا درس قبلاً انتخاب شده
        const existingIndex = examData.selected_subjects.findIndex(s => s.id === subjectId);
        
        if (existingIndex > -1) {
            // حذف درس
            examData.selected_subjects.splice(existingIndex, 1);
            $(this).removeClass('selected');
        } else {
            // اضافه کردن درس
            examData.selected_subjects.push({
                id: subjectId,
                uuid: subjectUuid,
                title: subjectTitle
            });
            $(this).addClass('selected');
        }
        
        // به‌روزرسانی JSON
        $('#subjects_json').val(JSON.stringify(examData.selected_subjects));
        
        // به‌روزرسانی راهنما
        updateSubjectHint();
        validateStep3();
    });
}

function updateSubjectHint() {
    const hint = $('#subjectSelectionHint');
    if (examData.classroom_type === 'single') {
        hint.text('برای آزمون تک‌درس، حداکثر یک درس انتخاب کنید');
    } else {
        hint.text(`شما ${examData.selected_subjects.length} درس انتخاب کرده‌اید`);
    }
}

// پیشنهاد هوشمند
function suggestTitle() {
    const titles = [
        'آزمون جامع پایه ' + getGradeName(),
        'آزمون تکمیلی ' + getSubjectTypeName(),
        'آزمون تشریحی و تستی ' + getBranchName(),
        'آزمون فصل ۲ ' + getSubjectTypeName(),
        'آزمون میان ترم ' + getGradeName()
    ];
    
    const randomTitle = titles[Math.floor(Math.random() * titles.length)];
    $('#title').val(randomTitle);
    validateStep4();
    updatePreview();
}

function suggestDescription() {
    const descriptions = [
        `این آزمون برای سنجش میزان یادگیری دانش‌آموزان پایه ${getGradeName()} طراحی شده است.`,
        `آزمون ${getSubjectTypeName()} شامل سوالات مفهومی و کاربردی می‌باشد.`,
        `این آزمون با هدف ارزیابی پیشرفت تحصیلی دانش‌آموزان در ${getBranchName()} برگزار می‌شود.`,
        `آزمون طراحی شده شامل سوالات استاندارد و همسو با اهداف آموزشی می‌باشد.`
    ];
    
    const randomDesc = descriptions[Math.floor(Math.random() * descriptions.length)];
    $('#description').val(randomDesc);
    updatePreview();
}

// توابع کمکی برای نمایش نام‌ها
function getGradeName() {
    const gradeId = examData.grade_id;
    if (!gradeId) return '';
    const grade = $('#gradeSelect option[value="' + gradeId + '"]').text();
    return grade || '';
}

function getBranchName() {
    const branchId = examData.branch_id;
    if (!branchId) return '';
    const branch = $('#branchSelect option[value="' + branchId + '"]').text();
    return branch || '';
}

function getSubjectTypeName() {
    const typeId = examData.subject_type_id;
    if (!typeId) return '';
    const type = $('#subjectTypeSelect option[value="' + typeId + '"]').text();
    return type || '';
}

// به‌روزرسانی پیش‌نمایش
function updatePreview() {
    // نوع آزمون
    $('#preview_exam_type').text(examData.exam_type === 'public' ? 'آزمون آزاد' : 'آزمون کلاسی')
        .removeClass().addClass('badge ' + (examData.exam_type === 'public' ? 'bg-primary' : 'bg-success'));
    
    // نوع کلاس
    if (examData.classroom_type) {
        $('#preview_classroom_type').text(examData.classroom_type === 'single' ? 'تک‌درس' : 'جامع')
            .removeClass().addClass('badge ' + (examData.classroom_type === 'single' ? 'bg-info' : 'bg-warning'));
    }
    
    // کلاس
    if (examData.classroom_id) {
        const classroom = window.examWizardData.classrooms.find(c => c.id == examData.classroom_id);
        $('#preview_classroom').text(classroom ? classroom.title : '--');
    } else {
        $('#preview_classroom').text('--');
    }
    
    // اطلاعات آموزشی
    $('#preview_grade').text(getGradeName());
    $('#preview_branch').text(getBranchName());
    $('#preview_field').text($('#fieldSelect option:selected').text());
    $('#preview_subfield').text($('#subfieldSelect option:selected').text());
    $('#preview_subject_type').text(getSubjectTypeName());
    $('#preview_subjects_count').text(examData.selected_subjects.length + ' درس');
    
    // اطلاعات آزمون
    $('#preview_title').text($('#title').val() || '--');
    $('#preview_description').text($('#description').val() || '--');
    $('#preview_duration').text($('#duration_minutes').val() || '--');
    $('#preview_passing_score').text($('#passing_score').val() || '--');
    $('#preview_start').text($('#start_at').val() || '--');
    $('#preview_end').text($('#end_at').val() || '--');
}

// نمایش خطا
function showError(message) {
    alert(message);
}

// ============================================
// FIXES اضافی برای رفع مشکلات
// ============================================

// فیکس: مطمئن شدن از پر شدن section_id و subfield_id
$(document).ready(function() {
    // رویداد تغییر subfield با تأخیر (برای اطمینان)
    $(document).on('change', '#subfieldSelect', function() {
        setTimeout(function() {
            const val = $('#subfieldSelect').val();
            if (val) {
                $('#subfield_id').val(val);
                examData.subfield_id = val;
                console.log('🔥 subfield_id synced with delay:', val);
            }
        }, 50);
    });
    
    // دیباگ فرم در هنگام ارسال
    $('#examForm').on('submit', function(e) {
        console.log('=== FINAL FORM DATA DEBUG ===');
        console.log('exam_type:', examData.exam_type);
        console.log('grade_id:', examData.grade_id);
        console.log('section_id:', $('#section_id').val());
        console.log('branch_id:', examData.branch_id);
        console.log('field_id:', examData.field_id);
        console.log('subfield_id:', examData.subfield_id);
        console.log('subfield hidden input:', $('#subfield_id').val());
        console.log('subject_type_id:', examData.subject_type_id);
        console.log('selected_subjects:', examData.selected_subjects);
        console.log('subjects_json:', $('#subjects_json').val());
        console.log('title:', $('#title').val());
        
        // بررسی نهایی subfield_id
        if (examData.exam_type === 'public' && !$('#subfield_id').val()) {
            alert('خطا: فیلد زیررشته پر نشده است. لطفاً دوباره تلاش کنید.');
            e.preventDefault();
            return false;
        }
    });
});
</script>