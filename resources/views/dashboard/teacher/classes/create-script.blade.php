  <script>
    document.addEventListener('DOMContentLoaded', () => {

    // ---------------- Fields (✅ اول تعریف شوند) ----------------
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

    // ✅ validate step1 before next
    // ✅ validate step1 before next (نسخه‌ی جدید)
    nextBtn.addEventListener('click', () => {
        if (current === 1) {
            // تمام فیلدهای required در step1
            const step1Required = document.querySelectorAll('.step[data-step="1"] [required]');

            // اگر حتی یکی نامعتبر بود، پیام خود مرورگر را نشان بده
            for (const el of step1Required) {
            if (!el.checkValidity()) {
                el.reportValidity(); // ✅ دقیقاً میگه کدوم فیلد خالیه
                return;
            }
            }
        }
        setStep(Math.min(3, current + 1));
    });


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
        const text =
        it.name_fa ||
        it.title_fa ||
        it.name ||
        it.title ||
        it.slug ||
        '—';

        sel.innerHTML += `<option value="${it.id}">${text}</option>`;
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

    (async () => {
        const sections = await getJson("{{ route('teacher.classes.data.sections') }}");
        fillSelect(section, sections, 'انتخاب مقطع');
    })();

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

    grade.addEventListener('change', async () => {
        resetSelect(branch, 'انتخاب شاخه');
        resetSelect(field, 'انتخاب رشته');
        resetSelect(subfield, 'انتخاب زیررشته');
        resetSelect(subjectType, 'انتخاب نوع درس');
        resetSelect(subject, 'انتخاب درس');

        if(!grade.value) return;

        const branches = await getJson(
        "{{ url('/dashboard/teacher/classes/data/branches') }}/" + section.value
        );
        fillSelect(branch, branches, 'انتخاب شاخه');
    });

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

    field.addEventListener('change', async () => {
        resetSelect(subfield, 'انتخاب زیررشته');
        resetSelect(subjectType, 'انتخاب نوع درس');
        resetSelect(subject, 'انتخاب درس');

        if(!field.value) return;

        const [subfields, subjectTypes] = await Promise.all([
        getJson("{{ url('/dashboard/teacher/classes/data/subfields') }}/" + field.value),
        getJson("{{ route('teacher.classes.data.subject-types') }}"),
        ]);

        if(subfields.length) fillSelect(subfield, subfields, 'انتخاب زیررشته');
        else { subfield.disabled=false; subfield.innerHTML=`<option value="">بدون زیررشته</option>`; }

        if(subjectTypes.length) fillSelect(subjectType, subjectTypes, 'انتخاب نوع درس');
        else { subjectType.disabled=false; subjectType.innerHTML=`<option value="">بدون نوع درس</option>`; }
    });

    subjectType.addEventListener('change', async () => {
        resetSelect(subject, 'انتخاب درس');
        if(!subjectType.value) return;

        const params = new URLSearchParams({
        grade_id: grade.value,
        branch_id: branch.value,
        field_id: field.value,
        subfield_id: subfield.value || "",
        subject_type_id: subjectType.value || ""
        });

        const subjects = await getJson(
        "{{ route('teacher.classes.data.subjects') }}?" + params.toString()
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
