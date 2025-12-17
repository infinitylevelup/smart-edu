<script>
$(document).ready(function() {
    console.log('🔧 Debug Panel Loaded');
    
    // چک اولیه برای examData
    console.log('🔍 Initial Check:');
    console.log('- window.examData:', typeof window.examData);
    console.log('- window.examWizardData:', typeof window.examWizardData);
    console.log('- jQuery version:', $.fn.jquery);
    
    // ایجاد پنل دیباگ ساده‌تر
    const debugPanel = `
        <div id="examDebugPanel" style="position:fixed; bottom:20px; left:20px; background:#333; color:white; padding:15px; border-radius:10px; z-index:9999; font-family:monospace; font-size:12px; max-width:400px; max-height:300px; overflow:auto; box-shadow:0 0 20px rgba(0,0,0,0.5);">
            <div style="display:flex; justify-content:space-between; margin-bottom:10px; align-items:center;">
                <strong style="color:#ffcc00;">🔧 Exam Debug Panel</strong>
                <span style="font-size:10px; color:#aaa;">v1.0</span>
            </div>
            <div id="debugContent" style="margin-bottom:10px;"></div>
            <div style="display:flex; flex-wrap:wrap; gap:5px;">
                <button onclick="checkJavascriptErrors()" style="background:#FF5722; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-size:11px; flex:1;">🚨 خطاهای JS</button>
                <button onclick="checkDependencies()" style="background:#9C27B0; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-size:11px; flex:1;">📦 وابستگی‌ها</button>
                <button onclick="checkFormStatus()" style="background:#4CAF50; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-size:11px; flex:1;">📋 وضعیت</button>
                <button onclick="forceFixExamData()" style="background:#2196F3; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-size:11px; flex:1;">🔧 رفع مشکل</button>
            </div>
        </div>
    `;
    
    $('body').append(debugPanel);
    
    // چک اولیه
    checkDependencies();
});

// 1. بررسی وابستگی‌ها
function checkDependencies() {
    let html = '<div style="color:#fff;">';
    html += '<strong style="color:#9C27B0;">📦 بررسی وابستگی‌ها</strong><br>';
    
    const checks = [
        { name: 'jQuery', check: () => typeof jQuery !== 'undefined', fix: null },
        { name: 'examData', check: () => typeof window.examData !== 'undefined', fix: fixExamData },
        { name: 'examWizardData', check: () => typeof window.examWizardData !== 'undefined', fix: null },
        { name: 'PersianDatepicker', check: () => typeof $.fn.persianDatepicker !== 'undefined', fix: null }
    ];
    
    checks.forEach(item => {
        const isOk = item.check();
        html += `<div style="margin:3px 0;">`;
        html += `<span style="color:${isOk ? '#4CAF50' : '#f44336'};">${isOk ? '✅' : '❌'}</span> `;
        html += `${item.name}: ${isOk ? 'OK' : 'MISSING'}`;
        
        if (!isOk && item.fix) {
            html += ` <button onclick="${item.fix.name}()" style="background:#666; color:white; border:none; padding:2px 6px; border-radius:2px; cursor:pointer; font-size:10px; margin-left:5px;">رفع</button>`;
        }
        
        html += `</div>`;
    });
    
    html += '</div>';
    $('#debugContent').html(html);
}

// 2. رفع مشکل examData
function fixExamData() {
    console.log('🔧 Attempting to fix examData...');
    
    if (typeof window.examData === 'undefined') {
        // تعریف examData اگر وجود ندارد
        window.examData = {
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
        console.log('✅ Created window.examData');
    }
    
    // همگام‌سازی با hidden inputs
    syncExamDataWithForm();
    
    checkDependencies();
}

// 3. بررسی وضعیت فرم
function checkFormStatus() {
    let html = '<div style="color:#fff;">';
    html += '<strong style="color:#4CAF50;">📋 وضعیت فرم</strong><br>';
    
    // ابتدا examData را چک کن
    if (typeof window.examData === 'undefined') {
        html += '<div style="background:#f44336; padding:10px; border-radius:4px; margin:10px 0;">';
        html += '<strong>❌ CRITICAL ERROR:</strong> examData تعریف نشده است!<br>';
        html += '<button onclick="forceFixExamData()" style="background:white; color:#f44336; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; width:100%; margin-top:5px;">';
        html += '🔧 رفع فوری مشکل';
        html += '</button>';
        html += '</div>';
        
        $('#debugContent').html(html);
        return;
    }
    
    // بررسی hidden inputs
    const hiddenInputs = [
        'exam_type', 'grade_id', 'section_id', 'branch_id', 
        'field_id', 'subfield_id', 'subject_type_id', 'subjects_json'
    ];
    
    let allGood = true;
    
    hiddenInputs.forEach(input => {
        const value = $(`#${input}`).val();
        const hasValue = value && value !== '';
        const status = hasValue ? '✅' : '❌';
        const color = hasValue ? '#4CAF50' : '#f44336';
        
        html += `<div style="margin:2px 0;"><span style="color:${color};">${status}</span> ${input}: <strong>${value || '(خالی)'}</strong></div>`;
        
        if (!hasValue) allGood = false;
    });
    
    // بررسی examData
    html += '<div style="margin-top:10px; border-top:1px solid #666; padding-top:5px;">';
    html += '<strong style="color:#ffcc00;">📊 Exam Data Values:</strong><br>';
    
    Object.keys(examData).forEach(key => {
        const value = examData[key];
        const hasValue = value !== null && value !== undefined && value !== '';
        const status = hasValue ? '✅' : '❌';
        const color = hasValue ? '#4CAF50' : '#f44336';
        
        html += `<div style="margin:2px 0;"><span style="color:${color};">${status}</span> ${key}: <strong>${JSON.stringify(value)}</strong></div>`;
    });
    
    // دکمه همگام‌سازی
    html += '<button onclick="syncExamDataWithForm()" style="background:#2196F3; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; width:100%; margin-top:10px; font-size:12px;">';
    html += '🔄 همگام‌سازی examData با فرم';
    html += '</button>';
    
    // نتیجه کلی
    html += `<div style="margin-top:10px; padding:8px; background:${allGood ? '#2e7d32' : '#c62828'}; border-radius:4px;">`;
    html += `<strong>${allGood ? '✅ همه چیز خوب است!' : '❌ مشکل وجود دارد!'}</strong>`;
    html += '</div></div>';
    
    $('#debugContent').html(html);
}

// 4. همگام‌سازی examData با فرم
function syncExamDataWithForm() {
    console.log('🔄 Syncing examData with form...');
    
    if (typeof window.examData === 'undefined') {
        fixExamData();
        return;
    }
    
    // از hidden inputs به examData
    const fields = ['exam_type', 'grade_id', 'section_id', 'branch_id', 'field_id', 'subfield_id', 'subject_type_id'];
    
    fields.forEach(field => {
        const value = $(`#${field}`).val();
        if (value) {
            examData[field] = value;
            console.log(`🔄 ${field} set to: ${value}`);
        }
    });
    
    // subjects_json
    const subjectsJson = $('#subjects_json').val();
    if (subjectsJson) {
        try {
            examData.selected_subjects = JSON.parse(subjectsJson);
            console.log(`🔄 selected_subjects updated: ${examData.selected_subjects.length} subjects`);
        } catch (e) {
            console.error('❌ Error parsing subjects_json:', e);
        }
    }
    
    // از select‌ها به hidden inputs (اگر hidden خالی است)
    syncSelectsToHidden();
    
    checkFormStatus();
    showDebugAlert('✅ examData با فرم همگام شد', 'success');
}

// 5. همگام‌سازی select‌ها با hidden inputs
function syncSelectsToHidden() {
    console.log('🔄 Syncing selects to hidden inputs...');
    
    // subfield
    const subfieldSelectVal = $('#subfieldSelect').val();
    if (subfieldSelectVal && !$('#subfield_id').val()) {
        $('#subfield_id').val(subfieldSelectVal);
        examData.subfield_id = subfieldSelectVal;
        console.log(`✅ subfield_id set from select: ${subfieldSelectVal}`);
    }
    
    // grade و section
    const gradeOption = $('#gradeSelect option:selected');
    if (gradeOption.length) {
        const gradeId = gradeOption.val();
        const sectionId = gradeOption.data('section');
        
        if (gradeId && !$('#grade_id').val()) {
            $('#grade_id').val(gradeId);
            examData.grade_id = gradeId;
            console.log(`✅ grade_id set from select: ${gradeId}`);
        }
        
        if (sectionId && !$('#section_id').val()) {
            $('#section_id').val(sectionId);
            examData.section_id = sectionId;
            console.log(`✅ section_id set from grade: ${sectionId}`);
        }
    }
    
    // سایر select‌ها
    const selectMappings = [
        { select: '#branchSelect', hidden: '#branch_id' },
        { select: '#fieldSelect', hidden: '#field_id' },
        { select: '#subjectTypeSelect', hidden: '#subject_type_id' }
    ];
    
    selectMappings.forEach(mapping => {
        const selectVal = $(mapping.select).val();
        if (selectVal && !$(mapping.hidden).val()) {
            $(mapping.hidden).val(selectVal);
            console.log(`✅ ${mapping.hidden} set from select: ${selectVal}`);
        }
    });
}

// 6. بررسی خطاهای JavaScript
function checkJavascriptErrors() {
    // این فقط یک شبیه‌سازی است
    let html = '<div style="color:#fff;">';
    html += '<strong style="color:#FF5722;">🚨 بررسی خطاها</strong><br>';
    
    // تست ارورهای رایج
    html += '<div style="background:#222; padding:10px; border-radius:4px; margin:10px 0; font-size:11px;">';
    
    // چک jQuery
    if (typeof jQuery === 'undefined') {
        html += '<div style="color:#f44336;">❌ jQuery لود نشده است!</div>';
        html += '<div style="color:#ffcc00; margin-top:5px;">راه‌حل: مطمئن شوید این خط در head است:</div>';
        html += '<code style="background:#000; padding:5px; display:block; margin:5px 0;">&lt;script src="https://code.jquery.com/jquery-3.6.0.min.js"&gt;&lt;/script&gt;</code>';
    } else {
        html += '<div style="color:#4CAF50;">✅ jQuery لود شده (v' + $.fn.jquery + ')</div>';
    }
    
    // چک examData
    if (typeof window.examData === 'undefined') {
        html += '<div style="color:#f44336; margin-top:5px;">❌ window.examData تعریف نشده</div>';
        html += '<div style="color:#ffcc00;">علت احتمالی: خطا در create-script.blade.php</div>';
    } else {
        html += '<div style="color:#4CAF50; margin-top:5px;">✅ window.examData تعریف شده</div>';
    }
    
    // چک examWizardData
    if (typeof window.examWizardData === 'undefined') {
        html += '<div style="color:#f44336; margin-top:5px;">❌ window.examWizardData تعریف نشده</div>';
        html += '<div style="color:#ffcc00;">علت: خطا در PHP بخش @php</div>';
    } else {
        html += '<div style="color:#4CAF50; margin-top:5px;">✅ window.examWizardData تعریف شده</div>';
    }
    
    html += '</div>';
    
    // دکمه بازرسی عمیق
    html += '<button onclick="runDeepInspection()" style="background:#FF9800; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; width:100%; font-size:12px;">';
    html += '🔍 بازرسی عمیق صفحه';
    html += '</button>';
    
    html += '</div>';
    $('#debugContent').html(html);
}

// 7. رفع فوری مشکل
function forceFixExamData() {
    console.log('🔧 Force fixing all issues...');
    
    // 1. مطمئن شو jQuery هست
    if (typeof jQuery === 'undefined') {
        console.error('❌ jQuery not found!');
        showDebugAlert('خطا: jQuery یافت نشد!', 'error');
        return;
    }
    
    // 2. تعریف examData اگر وجود ندارد
    if (typeof window.examData === 'undefined') {
        window.examData = {
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
        console.log('✅ Created examData');
    }
    
    // 3. تعریف examWizardData اگر وجود ندارد
    if (typeof window.examWizardData === 'undefined') {
        window.examWizardData = {
            branches: [],
            fields: [],
            subfields: [],
            subjects: [],
            classrooms: []
        };
        console.log('✅ Created examWizardData (empty)');
    }
    
    // 4. همگام‌سازی همه چیز
    syncExamDataWithForm();
    
    // 5. نمایش موفقیت
    showDebugAlert('✅ مشکلات فیکس شدند! فرم را دوباره چک کنید.', 'success');
    
    // 6. بررسی مجدد
    setTimeout(() => {
        checkFormStatus();
    }, 500);
}

// 8. بازرسی عمیق
function runDeepInspection() {
    console.log('🔍 Running deep inspection...');
    
    let html = '<div style="color:#fff;">';
    html += '<strong style="color:#FF9800;">🔍 بازرسی عمیق</strong><br>';
    html += '<div style="background:#222; padding:10px; border-radius:4px; margin:10px 0; font-size:10px; max-height:200px; overflow:auto;">';
    
    // شمارش اسکریپت‌ها
    const scripts = $('script[src]');
    html += `<div>📜 تعداد اسکریپت‌های لود شده: ${scripts.length}</div>`;
    
    scripts.each(function(index) {
        html += `<div style="margin:2px 0; color:#aaa;">${index + 1}. ${$(this).attr('src')}</div>`;
    });
    
    // بررسی create-script
    const hasCreateScript = $('script:contains("Exam Wizard Initialized")').length > 0;
    html += `<div style="margin-top:10px; color:${hasCreateScript ? '#4CAF50' : '#f44336'}">`;
    html += `${hasCreateScript ? '✅' : '❌'} create-script.blade.php لود شده: ${hasCreateScript ? 'بله' : 'خیر'}`;
    html += `</div>`;
    
    // بررسی create-style
    const hasCreateStyle = $('link[href*="persian-datepicker"], style:contains("exam-container")').length > 0;
    html += `<div style="margin-top:5px; color:${hasCreateStyle ? '#4CAF50' : '#f44336'}">`;
    html += `${hasCreateStyle ? '✅' : '❌'} create-style.blade.php لود شده: ${hasCreateStyle ? 'بله' : 'خیر'}`;
    html += `</div>`;
    
    html += '</div>';
    
    // دکمه‌های اکشن
    html += '<div style="display:flex; gap:5px; margin-top:10px;">';
    html += '<button onclick="reloadPage()" style="background:#2196F3; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; flex:1; font-size:12px;">🔄 رفرش صفحه</button>';
    html += '<button onclick="loadCreateScriptManually()" style="background:#9C27B0; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer; flex:1; font-size:12px;">📜 لود دستی اسکریپت</button>';
    html += '</div>';
    
    html += '</div>';
    $('#debugContent').html(html);
}

// 9. راه‌حل‌های کمکی
function reloadPage() {
    location.reload(true); // رفرش با کش پاک
}

function loadCreateScriptManually() {
    console.log('📜 Attempting to manually load create-script logic...');
    
    // بارگذاری منطق اصلی به صورت دستی
    const manualScript = `
        console.log('📜 Manually loading exam wizard logic...');
        
        // تعریف examData
        window.examData = window.examData || {
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
        
        // تعریف examWizardData اگر وجود ندارد
        window.examWizardData = window.examWizardData || {
            branches: [],
            fields: [],
            subfields: [],
            subjects: [],
            classrooms: []
        };
        
        console.log('✅ Manual setup complete');
        
        // رویدادهای پایه
        $(document).ready(function() {
            console.log('✅ Manual script ready');
            
            // رویدادهای ساده
            $('.exam-type-card').click(function() {
                const type = $(this).data('type');
                examData.exam_type = type;
                $('#exam_type').val(type);
                console.log('✅ exam_type set to:', type);
            });
            
            // رویداد subfield
            $('#subfieldSelect').on('change', function() {
                const val = $(this).val();
                examData.subfield_id = val;
                $('#subfield_id').val(val);
                console.log('✅ subfield_id set to:', val);
            });
            
            console.log('✅ Manual event listeners attached');
        });
    `;
    
    // اجرای اسکریپت
    eval(manualScript);
    showDebugAlert('✅ منطق دستی لود شد', 'success');
    forceFixExamData();
}

// تابع نمایش اعلان
function showDebugAlert(message, type = 'info') {
    const colors = {
        'info': '#2196F3',
        'success': '#4CAF50',
        'warning': '#FF9800',
        'error': '#f44336'
    };
    
    const alertDiv = $(`
        <div style="position:fixed; top:20px; right:20px; background:${colors[type]}; color:white; padding:15px 20px; border-radius:5px; z-index:10000; max-width:300px; box-shadow:0 2px 15px rgba(0,0,0,0.3); animation:fadeIn 0.3s;">
            <strong>${type === 'info' ? 'ℹ️' : type === 'success' ? '✅' : type === 'warning' ? '⚠️' : '❌'}</strong>
            ${message}
        </div>
    `);
    
    $('body').append(alertDiv);
    
    setTimeout(() => {
        alertDiv.fadeOut(300, function() {
            $(this).remove();
        });
    }, 4000);
}

// اضافه کردن استایل انیمیشن
if (!$('#debug-styles').length) {
    $('head').append(`
        <style id="debug-styles">
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    `);
}
</script>