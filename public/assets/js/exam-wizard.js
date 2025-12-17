/**
 * SmartEdu — Exam Wizard (FIXED & SYNCED VERSION)
 * Compatible with Exam Model changes (type_fa, type_icon, type_class)
 */

document.addEventListener("DOMContentLoaded", function() {
    console.log("🚀 Wizard JS Loaded - Synced with PHP Model");
    
    /*---------------------------------------------------------
    * 📊 DEBUG - Checking data consistency...
    *---------------------------------------------------------*/
    console.log("📊 DEBUG - Checking subjects data...");
    console.log("Total subjects from PHP:", ALL_SUBJECTS?.length || 0);
    
    if (ALL_SUBJECTS && ALL_SUBJECTS.length > 0) {
        console.log("First subject details:", {
            id: ALL_SUBJECTS[0].id,
            uuid: ALL_SUBJECTS[0].uuid,
            title: ALL_SUBJECTS[0].title_fa
        });
    }
    
    /*---------------------------------------------------------
    * TYPE MAPPING (Sync with App\Models\Exam.php)
    *---------------------------------------------------------*/
    const TYPE_FA_MAP = {
        'public': 'آزمون آزاد',
        'class_single': 'کلاسی تک‌درس',
        'class_comprehensive': 'کلاسی جامع'
    };
    
    const TYPE_ICON_MAP = {
        'public': 'fa-globe',
        'class_single': 'fa-book',
        'class_comprehensive': 'fa-graduation-cap'
    };
    
    const TYPE_CLASS_MAP = {
        'public': 'type-public',
        'class_single': 'type-class-single',
        'class_comprehensive': 'type-class-comprehensive'
    };
    
    /*---------------------------------------------------------
    * STEP ELEMENTS
    *---------------------------------------------------------*/
    const steps = {
        1: document.getElementById("step1"),
        2: document.getElementById("step2"),
        3: document.getElementById("step3"),
        4: document.getElementById("step4"),
        5: document.getElementById("step5"),
    };
    
    const indicators = {
        1: document.getElementById("stepIndicator1"),
        2: document.getElementById("stepIndicator2"),
        3: document.getElementById("stepIndicator3"),
        4: document.getElementById("stepIndicator4"),
        5: document.getElementById("stepIndicator5"),
    };
    
    let current = 1;
    
    /*---------------------------------------------------------
    * INPUTS
    *---------------------------------------------------------*/
    const examTypeInput = document.getElementById("exam_type");
    const classroomIdInput = document.getElementById("classroom_id");
    
    const sectionInput = document.getElementById("section_id");
    const gradeInput = document.getElementById("grade_id");
    const branchInput = document.getElementById("branch_id");
    const fieldInput = document.getElementById("field_id");
    const subfieldInput = document.getElementById("subfield_id");
    
    const subjectTypeInput = document.getElementById("subject_type_id");
    const subjectsJsonInput = document.getElementById("subjects_json");
    
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const submitBtn = document.getElementById("submitBtn");
    
    /*Step 4 — Exam Settings Inputs*/
    const titleInput = document.getElementById("title");
    const descriptionInput = document.getElementById("description");
    const startAtInput = document.getElementById("start_at");
    const endAtInput = document.getElementById("end_at");
    const durationInput = document.getElementById("duration_minutes");
    const passingScoreInput = document.getElementById("passing_score");
    
    /*---------------------------------------------------------
    * EXAM TYPE SELECTION CARDS
    *---------------------------------------------------------*/
    const publicCard = document.getElementById("examTypePublic");
    const classCard = document.getElementById("examTypeClass");
    const classBox = document.getElementById("classExamBox");
    
    const singleSelect = document.getElementById("singleClassSelect");
    const compSelect = document.getElementById("compClassSelect");
    const singleBtn = document.getElementById("chooseSingleClassBtn");
    const compBtn = document.getElementById("chooseCompClassBtn");
    
    /*---------------------------------------------------------
    * TAXONOMY ELEMENTS
    *---------------------------------------------------------*/
    const gradeSelect = document.getElementById("gradeSelect");
    const branchSelect = document.getElementById("branchSelect");
    const fieldSelect = document.getElementById("fieldSelect");
    const subfieldSelect = document.getElementById("subfieldSelect");
    
    /*SUBJECT TYPE & SUBJECTS*/
    const subjectTypeSelect = document.getElementById("subjectTypeSelect");
    const subjectsContainer = document.getElementById("subjectsContainer");
    
    /*---------------------------------------------------------
    * PREVIEW ELEMENTS
    *---------------------------------------------------------*/
    const previewExamType = document.getElementById("preview_exam_type");
    const previewClassroom = document.getElementById("preview_classroom");
    const previewGrade = document.getElementById("preview_grade");
    const previewBranch = document.getElementById("preview_branch");
    const previewField = document.getElementById("preview_field");
    const previewSubfield = document.getElementById("preview_subfield");
    const previewSubjectType = document.getElementById("preview_subject_type");
    const previewSubjectsCount = document.getElementById("preview_subjects_count");
    const previewDuration = document.getElementById("preview_duration");
    const previewPassingScore = document.getElementById("preview_passing_score");
    const previewStart = document.getElementById("preview_start");
    const previewEnd = document.getElementById("preview_end");
    
    /*---------------------------------------------------------
    * AI BUTTONS
    *---------------------------------------------------------*/
    const aiTitleBtn = document.getElementById("aiTitleBtn");
    const aiDescBtn = document.getElementById("aiDescBtn");
    
    /*---------------------------------------------------------
    * FORM
    *---------------------------------------------------------*/
    const form = document.getElementById("examForm");
    
    /*---------------------------------------------------------
    * DATA FROM BLADE
    *---------------------------------------------------------*/
    const DATA = window.examWizardData || {};
    const ALL_GRADES = DATA.grades || [];
    const ALL_BRANCHES = DATA.branches || [];
    const ALL_FIELDS = DATA.fields || [];
    const ALL_SUBFIELDS = DATA.subfields || [];
    const ALL_SUBJECT_TYPES = DATA.subjectTypes || [];
    const ALL_SUBJECTS = DATA.subjects || [];
    
    // 🔴 دیباگ اضافه
    console.log('🟡 ALL_SUBJECTS data from PHP:', ALL_SUBJECTS);
    console.log('🟡 Number of subjects:', ALL_SUBJECTS.length);
    
    let selectedClassroomTitle = "";
    
    /*---------------------------------------------------------
    * Helper Functions
    *---------------------------------------------------------*/
    window.showStep = function(n) {
        console.log('🔄 showStep called with:', n);
        
        Object.values(steps).forEach((el) => el.classList.remove("active"));
        if (steps[n]) steps[n].classList.add("active");
        
        Object.values(indicators).forEach((el) => el.classList.remove("active"));
        if (indicators[n]) indicators[n].classList.add("active");
        
        current = n;
        updateButtons();
        
        console.log('✅ Now on step:', n);
    };
    
    function updateButtons() {
        prevBtn.disabled = current === 1;
        
        if (current === 5) {
            nextBtn.classList.add("d-none");
            submitBtn.classList.remove("d-none");
        } else {
            nextBtn.classList.remove("d-none");
            submitBtn.classList.add("d-none");
        }
    }
    
    function nextStep() {
        console.log('🔄 nextStep called, current:', current, 'exam_type:', examTypeInput.value);
        
        if (current === 1) {
            if (!examTypeInput.value) {
                alert("لطفاً نوع آزمون را انتخاب کنید");
                return;
            }
            
            // 🔴 اصلاح: فقط دو نوع کلاسی معتبر داریم
            const isClassExam = examTypeInput.value === 'class_single' || examTypeInput.value === 'class_comprehensive';
            
            if (isClassExam && !classroomIdInput.value) {
                alert("لطفاً ابتدا یک کلاس انتخاب کنید");
                return;
            }
        }
        
        if (current === 2 && examTypeInput.value === "public") {
            if (!gradeInput.value || !branchInput.value || !fieldInput.value || !subfieldInput.value) {
                alert("لطفاً دسته‌بندی آموزشی را کامل کنید");
                return;
            }
        }
        
        if (current === 3 && examTypeInput.value === "public") {
            if (!subjectsJsonInput.value || subjectsJsonInput.value === '[]' || subjectsJsonInput.value === '""') {
                alert("لطفاً حداقل یک درس انتخاب کنید");
                return;
            }
        }
        
        let next = current + 1;
        
        // 🔴 اصلاح: فقط دو نوع کلاسی داریم
        const isClassExam = examTypeInput.value === "class_single" || examTypeInput.value === "class_comprehensive";
        
        // Class exam skips step 2 & 3
        if (current === 1 && isClassExam) {
            next = 4;
            console.log('⏭️ Skipping to step 4 for class exam:', examTypeInput.value);
        }
        
        buildPreview();
        showStep(next);
    }
    
    function prevStep() {
        let prev = current - 1;
        
        // 🔴 اصلاح: اگر آزمون کلاسی هستیم و در مرحله ۴ یا ۵ هستیم
        const isClassExam = examTypeInput.value === "class_single" || examTypeInput.value === "class_comprehensive";
        
        if ((current === 4 || current === 5) && isClassExam) {
            prev = 1; // برگشت مستقیم به مرحله ۱
        }
        
        showStep(prev);
    }
    
    function getSelectedText(select) {
        if (!select) return "";
        const idx = select.selectedIndex;
        return idx >= 0 ? select.options[idx].text : "";
    }
    
    /*---------------------------------------------------------
    * Reset dropdown helper
    *---------------------------------------------------------*/
    function resetSelect(select, placeholder) {
        if (!select) return;
        select.innerHTML = "";
        const opt = document.createElement("option");
        opt.value = "";
        opt.textContent = placeholder;
        select.appendChild(opt);
        select.disabled = true;
    }
    
    /*---------------------------------------------------------
    * Populate Branches
    *---------------------------------------------------------*/
    function populateBranches(sectionId) {
        resetSelect(branchSelect, "انتخاب شاخه...");
        resetSelect(fieldSelect, "انتخاب زمینه...");
        resetSelect(subfieldSelect, "انتخاب زیررشته...");
        
        if (!sectionId) return;
        
        const list = ALL_BRANCHES.filter(b => String(b.section_id) === String(sectionId));
        if (list.length) {
            branchSelect.disabled = false;
            list.forEach(b => {
                const opt = document.createElement("option");
                opt.value = b.id;
                opt.textContent = b.name_fa || b.name || b.slug;
                branchSelect.appendChild(opt);
            });
        }
    }
    
    /*---------------------------------------------------------
    * Populate Fields
    *---------------------------------------------------------*/
    function populateFields(branchId) {
        resetSelect(fieldSelect, "انتخاب زمینه...");
        resetSelect(subfieldSelect, "انتخاب زیررشته...");
        
        if (!branchId) return;
        
        const list = ALL_FIELDS.filter(f => String(f.branch_id) === String(branchId));
        if (list.length) {
            fieldSelect.disabled = false;
            list.forEach(f => {
                const opt = document.createElement("option");
                opt.value = f.id;
                opt.textContent = f.name_fa || f.name || f.slug;
                fieldSelect.appendChild(opt);
            });
        }
    }
    
    /*---------------------------------------------------------
    * Populate Subfields
    *---------------------------------------------------------*/
    function populateSubfields(fieldId) {
        resetSelect(subfieldSelect, "انتخاب زیررشته...");
        
        if (!fieldId) return;
        
        const list = ALL_SUBFIELDS.filter(sf => String(sf.field_id) === String(fieldId));
        if (list.length) {
            subfieldSelect.disabled = false;
            list.forEach(sf => {
                const opt = document.createElement("option");
                opt.value = sf.id;
                opt.textContent = sf.name_fa || sf.name || sf.slug;
                subfieldSelect.appendChild(opt);
            });
        }
    }
    
    /*---------------------------------------------------------
    * PUBLIC EXAM MODE
    *---------------------------------------------------------*/
    if (publicCard) {
        publicCard.addEventListener("click", function() {
            console.log('🎯 Public exam selected');
            examTypeInput.value = "public";
            classroomIdInput.value = "";
            
            selectedClassroomTitle = "";
            
            classBox.style.display = "none";
            
            publicCard.classList.add("selected");
            if (classCard) classCard.classList.remove("selected");
            
            // Reset taxonomy selects
            if (gradeSelect) gradeSelect.value = "";
            if (branchSelect) {
                resetSelect(branchSelect, "انتخاب شاخه...");
            }
            if (fieldSelect) {
                resetSelect(fieldSelect, "انتخاب زمینه...");
            }
            if (subfieldSelect) {
                resetSelect(subfieldSelect, "انتخاب زیررشته...");
            }
            if (subjectTypeSelect) subjectTypeSelect.value = "";
            
            // Reset inputs
            gradeInput.value = "";
            sectionInput.value = "";
            branchInput.value = "";
            fieldInput.value = "";
            subfieldInput.value = "";
            subjectTypeInput.value = "";
            subjectsJsonInput.value = "";
            
            // Clear subjects container
            if (subjectsContainer) subjectsContainer.innerHTML = "";
            
            showStep(2);
        });
    }
    
    /*---------------------------------------------------------
    * CLASS EXAM MODE - اصلاح شده
    *---------------------------------------------------------*/
    if (classCard) {
        classCard.addEventListener("click", function() {
            console.log('🎯 Class exam category selected - please choose specific type');
            
            // مقدار را خالی نگه می‌داریم تا کاربر مجبور شود نوع دقیق کلاس را انتخاب کند
            examTypeInput.value = "";
            
            classBox.style.display = "block";
            
            classCard.classList.add("selected");
            if (publicCard) publicCard.classList.remove("selected");
            
            // نمایش پیام راهنما
            console.log("کاربر باید نوع دقیق کلاس (تک‌درس یا جامع) را انتخاب کند");
        });
    }
    
    /*CLASS: single subject - با مقدار صحیح*/
    if (singleBtn) {
        singleBtn.addEventListener("click", function() {
            if (!singleSelect.value) {
                alert("لطفاً یک کلاس تک‌درس انتخاب کنید");
                return;
            }
            
            // 🔴 استفاده از مقدار صحیح مطابق با مدل Exam
            examTypeInput.value = "class_single";
            classroomIdInput.value = singleSelect.value;
            
            selectedClassroomTitle = getSelectedText(singleSelect);
            
            console.log('✅ Single class selected:', {
                exam_type: examTypeInput.value,
                classroom_id: singleSelect.value,
                title: selectedClassroomTitle
            });
            
            showStep(4);
        });
    }
    
    /*CLASS: comprehensive - با مقدار صحیح*/
    if (compBtn) {
        compBtn.addEventListener("click", function() {
            if (!compSelect.value) {
                alert("لطفاً یک کلاس جامع انتخاب کنید");
                return;
            }
            
            // 🔴 استفاده از مقدار صحیح مطابق با مدل Exam
            examTypeInput.value = "class_comprehensive";
            classroomIdInput.value = compSelect.value;
            
            selectedClassroomTitle = getSelectedText(compSelect);
            
            console.log('✅ Comprehensive class selected:', {
                exam_type: examTypeInput.value,
                classroom_id: compSelect.value,
                title: selectedClassroomTitle
            });
            
            showStep(4);
        });
    }
    
    /*============================================================
    * EVENTS — TAXONOMY SELECTS
    *============================================================*/
    
    if (gradeSelect) {
        gradeSelect.addEventListener("change", function() {
            const option = gradeSelect.options[gradeSelect.selectedIndex];
            const gradeId = option.value;
            const sectionId = option.getAttribute("data-section");
            
            console.log("Grade selected:", gradeId, "Section:", sectionId);
            
            gradeInput.value = gradeId;
            sectionInput.value = sectionId;
            
            populateBranches(sectionId);
            
            branchInput.value = "";
            fieldInput.value = "";
            subfieldInput.value = "";
            
            renderSubjects();
        });
    }
    
    if (branchSelect) {
        branchSelect.addEventListener("change", function() {
            const branchId = branchSelect.value;
            branchInput.value = branchId;
            
            populateFields(branchId);
            
            fieldInput.value = "";
            subfieldInput.value = "";
            
            renderSubjects();
        });
    }
    
    if (fieldSelect) {
        fieldSelect.addEventListener("change", function() {
            const fieldId = fieldSelect.value;
            fieldInput.value = fieldId;
            
            populateSubfields(fieldId);
            
            subfieldInput.value = "";
            
            renderSubjects();
        });
    }
    
    if (subfieldSelect) {
        subfieldSelect.addEventListener("change", function() {
            const subId = subfieldSelect.value;
            subfieldInput.value = subId;
            
            renderSubjects();
        });
    }
    
    /*============================================================
    * SUBJECT TYPE CHANGE → REFRESH SUBJECT LIST
    *============================================================*/
    if (subjectTypeSelect) {
        subjectTypeSelect.addEventListener("change", function() {
            subjectTypeInput.value = subjectTypeSelect.value;
            renderSubjects();
        });
    }
    
    /*============================================================
    * FILTER SUBJECTS BASED ON TAXONOMY SELECTION
    *============================================================*/
    function filterSubjects() {
        const gradeId = gradeInput.value;
        const branchId = branchInput.value;
        const fieldId = fieldInput.value;
        const subId = subfieldInput.value;
        const typeId = subjectTypeInput.value;
        
        console.log('🔍 Filtering subjects with criteria:', {
            gradeId, branchId, fieldId, subId, typeId,
            allSubjectsCount: ALL_SUBJECTS.length
        });
        
        const filtered = ALL_SUBJECTS.filter(s => {
            if (gradeId && s.grade_id != gradeId) return false;
            if (branchId && s.branch_id != branchId) return false;
            if (fieldId && s.field_id != fieldId) return false;
            if (subId && s.subfield_id != subId) return false;
            if (typeId && String(s.subject_type_id) !== String(typeId)) return false;
            return true;
        });
        
        console.log('🔍 Filtered subjects details:', filtered);
        return filtered;
    }
    
    /*============================================================
    * RENDER SUBJECTS - با اصلاحات مهم
    *============================================================*/
    function renderSubjects() {
        if (!subjectsContainer) {
            console.error('❌ subjectsContainer is null');
            return;
        }
        
        console.log('🔍 renderSubjects called with filters:', {
            grade: gradeInput.value,
            branch: branchInput.value,
            field: fieldInput.value,
            subfield: subfieldInput.value,
            subjectType: subjectTypeInput.value
        });
        
        // Public exam only
        if (examTypeInput.value !== "public") {
            subjectsContainer.innerHTML = "";
            subjectsJsonInput.value = "";
            return;
        }
        
        // Require subject type
        if (!subjectTypeInput.value) {
            subjectsContainer.innerHTML =
                '<div class="col-12 text-muted text-center py-2"> ابتدا نوع درس را انتخاب کنید. </div>';
            subjectsJsonInput.value = "";
            return;
        }
        
        const list = filterSubjects();
        
        subjectsContainer.innerHTML = "";
        
        console.log('🔍 Filtered subjects list:', list);
        console.log('🔍 Number of subjects:', list.length);
        
        if (!list.length) {
            subjectsContainer.innerHTML =
                '<div class="col-12 text-muted text-center py-2"> هیچ درس مطابق فیلترها یافت نشد. </div>';
            subjectsJsonInput.value = "";
            return;
        }
        
        list.forEach(s => {
            const col = document.createElement("div");
            col.className = "col-md-3 mb-3";
            
            const card = document.createElement("div");
            card.className = "subject-card";
            
            // 🔴 استفاده از ID عددی
            const subjectId = s.id;
            
            console.log('🔵 Creating card with data:', {
                title: s.title_fa,
                uuid: s.uuid,
                id: s.id,
                subjectId: subjectId
            });
            
            // بررسی که id معتبر باشد
            if (!subjectId || subjectId === 0 || subjectId === "0") {
                console.error('❌ Invalid subject ID:', s);
                // اگر UUID معتبر دارد، از آن استفاده کن
                if (s.uuid && isValidUUID(s.uuid)) {
                    card.setAttribute("data-id", s.uuid);
                    console.log('⚠️ Using UUID instead of invalid ID:', s.uuid);
                } else {
                    console.error('❌ No valid ID or UUID - skipping card');
                    return; // این کارت را ایجاد نکن
                }
            } else {
                // 🔴 اطمینان حاصل کنید مقدار عددی به string تبدیل شود
                card.setAttribute("data-id", String(subjectId));
            }
            
            card.textContent = s.title_fa || s.title || "درس";
            
            card.addEventListener("click", function() {
                card.classList.toggle("selected");
                syncSelectedSubjects();
            });
            
            col.appendChild(card);
            subjectsContainer.appendChild(col);
        });
        
        syncSelectedSubjects();
    }
    
    // 🔴 اضافه کردن تابع کمکی isValidUUID
    function isValidUUID(str) {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
        return uuidRegex.test(str);
    }
    
    /*---------------------------------------------------------
    * Store selected subject IDs into hidden input - با اصلاحات
    *---------------------------------------------------------*/
    function syncSelectedSubjects() {
        if (!subjectsContainer) {
            console.error('❌ subjectsContainer not found');
            subjectsJsonInput.value = '';
            return;
        }
        
        const selectedCards = subjectsContainer.querySelectorAll(".subject-card.selected");
        console.log('🔄 Found selected cards:', selectedCards.length);
        
        // جمع‌آوری IDها
        const selected = [];
        selectedCards.forEach(card => {
            const id = card.getAttribute("data-id");
            console.log('🔄 Card ID:', id);
            
            // 🔴 فیلتر کردن IDهای نامعتبر
            if (!id || id === "0" || id === "error-no-id" || id.trim() === "") {
                console.warn('⚠️ Skipping invalid ID:', id);
                return;
            }
            
            // اطمینان از معتبر بودن ID
            if (!isNaN(id) && id > 0) {
                selected.push(id);
            } else if (isValidUUID(id)) {
                selected.push(id);
            } else {
                console.warn('⚠️ Invalid subject ID format:', id);
            }
        });
        
        console.log('🔄 Filtered selected subjects:', selected);
        
        // همیشه JSON.stringify کنید
        subjectsJsonInput.value = JSON.stringify(selected);
        
        // دیباگ بیشتر
        console.log('✅ Final subjects_json value:', subjectsJsonInput.value);
        console.log('✅ Final subjects_json type:', typeof subjectsJsonInput.value);
    }
    
    /*============================================================
    * PREVIEW VISIBILITY FOR CLASS EXAM
    *============================================================*/
    const taxonomyPreviewIds = [
        "preview_grade",
        "preview_branch",
        "preview_field",
        "preview_subfield",
        "preview_subject_type",
        "preview_subjects_count",
    ];
    
    function toggleTaxonomyPreview(examType) {
        const hide = (examType === "class_single" || examType === "class_comprehensive");
        
        taxonomyPreviewIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            
            const wrapper = el.closest(".col-md-6");
            if (!wrapper) return;
            
            wrapper.style.display = hide ? "none" : "";
        });
    }
    
    /*============================================================
    * BUILD PREVIEW - اصلاح شده با نقشه فارسی
    *============================================================*/
    function buildPreview() {
        const type = examTypeInput.value;
        
        // نوع آزمون - با استفاده از نقشه فارسی
        if (previewExamType) {
            previewExamType.textContent = TYPE_FA_MAP[type] || "--";
        }
        
        // کلاس
        if (previewClassroom) {
            previewClassroom.textContent =
                (type === "public") ? "--" : (selectedClassroomTitle || "--");
        }
        
        toggleTaxonomyPreview(type);
        
        // PUBLIC EXAM → taxonomy
        if (type === "public") {
            if (previewGrade) previewGrade.textContent = getSelectedText(gradeSelect) || "--";
            if (previewBranch) previewBranch.textContent = getSelectedText(branchSelect) || "--";
            if (previewField) previewField.textContent = getSelectedText(fieldSelect) || "--";
            if (previewSubfield) previewSubfield.textContent = getSelectedText(subfieldSelect) || "--";
            if (previewSubjectType) previewSubjectType.textContent = getSelectedText(subjectTypeSelect) || "--";
            
            // تعداد درس
            if (previewSubjectsCount) {
                try {
                    const arr = subjectsJsonInput.value ? JSON.parse(subjectsJsonInput.value) : [];
                    previewSubjectsCount.textContent = arr.length || "--";
                } catch {
                    previewSubjectsCount.textContent = "--";
                }
            }
        }
        
        // SETTINGS PREVIEW
        if (previewDuration) {
            previewDuration.textContent =
                durationInput.value ? durationInput.value + " دقیقه" : "--";
        }
        
        if (previewPassingScore) {
            previewPassingScore.textContent =
                passingScoreInput.value || "--";
        }
        
        if (previewStart) {
            previewStart.textContent =
                startAtInput.value || "--";
        }
        
        if (previewEnd) {
            previewEnd.textContent =
                endAtInput.value || "--";
        }
    }
    
    /*============================================================
    * NAVIGATION BUTTONS
    *============================================================*/
    if (nextBtn) nextBtn.addEventListener("click", nextStep);
    if (prevBtn) prevBtn.addEventListener("click", prevStep);
    
    /*============================================================
    * FORM VALIDATION BEFORE SUBMIT - اصلاح شده
    *============================================================*/
    if (form) {
        form.addEventListener("submit", function(e) {
            console.log('📝 Form is submitting...');
            console.log('All form data:');
            console.log('exam_type:', examTypeInput.value);
            console.log('classroom_id:', classroomIdInput.value);
            console.log('title:', titleInput.value);
            console.log('duration:', durationInput.value);
            console.log('subjects:', subjectsJsonInput.value);
            console.log('grade:', gradeInput.value);
            console.log('branch:', branchInput.value);
            
            if (!examTypeInput.value) {
                e.preventDefault();
                alert("نوع آزمون مشخص نشده است.");
                return;
            }
            
            // 🔴 اعتبارسنجی مقادیر معتبر برای exam_type
            const validExamTypes = ['public', 'class_single', 'class_comprehensive'];
            if (!validExamTypes.includes(examTypeInput.value)) {
                e.preventDefault();
                alert("نوع آزمون انتخاب شده معتبر نیست.");
                return;
            }
            
            // اعتبارسنجی آزمون کلاسی
            const isClassExam = examTypeInput.value === 'class_single' || examTypeInput.value === 'class_comprehensive';
            if (isClassExam) {
                if (!classroomIdInput.value) {
                    e.preventDefault();
                    alert("برای آزمون کلاسی، کلاس باید انتخاب شود.");
                    return;
                }
            }
            
            // اعتبارسنجی آزمون عمومی
            if (examTypeInput.value === "public") {
                if (!subjectsJsonInput.value || subjectsJsonInput.value === '[]' || subjectsJsonInput.value === '""') {
                    e.preventDefault();
                    alert("لطفاً حداقل یک درس انتخاب کنید.");
                    return;
                }
                
                // اعتبارسنجی دسته‌بندی آموزشی
                if (!gradeInput.value || !branchInput.value || !fieldInput.value || !subfieldInput.value) {
                    e.preventDefault();
                    alert("لطفاً دسته‌بندی آموزشی را کامل کنید.");
                    return;
                }
            }
            
            console.log('✅ All validations passed, allowing form submission');
        });
    }
    
    /*============================================================
    * PERSIAN DATEPICKER — شمسی
    *============================================================*/
    function initPersianDatepicker() {
        if (typeof $ === "undefined" || !$.fn.persianDatepicker) {
            console.warn("⚠ Persian Datepicker not loaded!");
            return;
        }
        
        if (startAtInput) {
            $(startAtInput).persianDatepicker({
                format: "YYYY-MM-DD HH:mm",
                timePicker: { enabled: true },
                initialValue: false,
            });
        }
        
        if (endAtInput) {
            $(endAtInput).persianDatepicker({
                format: "YYYY-MM-DD HH:mm",
                timePicker: { enabled: true },
                initialValue: false,
            });
        }
    }
    
    // Load after DOM
    setTimeout(initPersianDatepicker, 300);
    
    /*============================================================
    * AI SUGGESTION FOR TITLE & DESCRIPTION - با نوع فارسی
    *============================================================*/
    async function fetchAI() {
        const payload = {
            grade: getSelectedText(gradeSelect),
            branch: getSelectedText(branchSelect),
            field: getSelectedText(fieldSelect),
            subfield: getSelectedText(subfieldSelect),
            subject: getSelectedText(subjectTypeSelect),
            exam_type: examTypeInput.value,
            // 🔴 اضافه کردن نوع فارسی برای AI
            exam_type_fa: TYPE_FA_MAP[examTypeInput.value] || ""
        };
        
        console.log('🤖 AI Request payload:', payload);
        
        const response = await fetch("/ai/exam/suggest", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload),
        });
        
        if (!response.ok) {
            console.error("❌ AI Server error:", response.status);
            return { title: "", description: "" };
        }
        
        let jsonText = await response.text();
        
        try {
            return JSON.parse(jsonText);
        } catch (e) {
            console.error("❌ Invalid AI JSON:", jsonText);
            return { title: "", description: "" };
        }
    }
    
    if (aiTitleBtn) {
        aiTitleBtn.addEventListener("click", async () => {
            aiTitleBtn.disabled = true;
            aiTitleBtn.textContent = "در حال تولید...";
            
            const result = await fetchAI();
            if (titleInput) titleInput.value = result.title || "";
            
            aiTitleBtn.textContent = "پیشنهاد عنوان هوشمند";
            aiTitleBtn.disabled = false;
        });
    }
    
    if (aiDescBtn) {
        aiDescBtn.addEventListener("click", async () => {
            aiDescBtn.disabled = true;
            aiDescBtn.textContent = "در حال تولید...";
            
            const result = await fetchAI();
            if (descriptionInput) descriptionInput.value = result.description || "";
            
            aiDescBtn.textContent = "پیشنهاد توضیح هوشمند";
            aiDescBtn.disabled = false;
        });
    }
    
    /*============================================================
    * INITIAL STEP
    *============================================================*/
    showStep(1);
    
    /*---------------------------------------------------------
    * OPTIONAL — SCROLL TO TOP ON STEP CHANGE
    *---------------------------------------------------------*/
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
    
    const originalShowStep = showStep;
    showStep = function(n) {
        originalShowStep(n);
        scrollToTop();
    };
    
    /*---------------------------------------------------------
    * FIX: Remove auto-next from subject type
    *---------------------------------------------------------*/
    if (subjectTypeSelect) {
        subjectTypeSelect.addEventListener("click", () => {
            // Prevent jump by mistake
            if (!subjectTypeSelect.value) {
                subjectsJsonInput.value = "";
            }
        });
    }
    
    /*---------------------------------------------------------
    * DEBUG LOGGING (optional)
    *---------------------------------------------------------*/
    function debugState() {
        console.log("📊 Exam Type:", examTypeInput.value, "FA:", TYPE_FA_MAP[examTypeInput.value] || "N/A");
        console.log("📊 Classroom ID:", classroomIdInput.value);
        console.log("📊 Taxonomy:", {
            section: sectionInput.value,
            grade: gradeInput.value,
            branch: branchInput.value,
            field: fieldInput.value,
            subfield: subfieldInput.value,
        });
        console.log("📊 SubjectType:", subjectTypeInput.value);
        console.log("📊 Subjects:", subjectsJsonInput.value);
    }
    
    /*Use F12 → Console → wizardDebug()*/
    window.wizardDebug = debugState;
    
    /*---------------------------------------------------------
    * SAFETY: Prevent leaving page with unsaved exam
    *---------------------------------------------------------*/
    let formChanged = false;
    
    document.querySelectorAll("#examForm input, #examForm textarea, #examForm select")
        .forEach(el => {
            el.addEventListener("change", () => formChanged = true);
            el.addEventListener("keyup", () => formChanged = true);
        });
    
    window.addEventListener("beforeunload", function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = "";
        }
    });
    
    /*---------------------------------------------------------
    * FINAL READY MESSAGE
    *---------------------------------------------------------*/
    console.log("✅ SmartEdu Exam Wizard - SYNCED with PHP Model - Loaded Successfully");
});