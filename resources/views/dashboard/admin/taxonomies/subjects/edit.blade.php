@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const section  = document.getElementById('section_id');
    const grade    = document.getElementById('grade_id');
    const branch   = document.getElementById('branch_id');
    const field    = document.getElementById('field_id');
    const subfield = document.getElementById('subfield_id');

    // ✅ old() اگر فرم خطا خورد، وگرنه مقدار فعلی مدل
    const oldGrade    = "{{ old('grade_id', $item->grade_id) }}";
    const oldBranch   = "{{ old('branch_id', $item->branch_id) }}";
    const oldField    = "{{ old('field_id', $item->field_id) }}";
    const oldSubfield = "{{ old('subfield_id', $item->subfield_id) }}";

    function reset(sel, placeholder="انتخاب کنید...", disable=true) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        sel.disabled = disable;
    }

    function fill(sel, items, oldValue=null, placeholder="انتخاب کنید...") {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(i => {
            const opt = document.createElement('option');
            opt.value = i.id;
            opt.textContent = i.name_fa;
            if (oldValue && oldValue === i.id) opt.selected = true;
            sel.appendChild(opt);
        });
        sel.disabled = false;
    }

    async function get(url) {
        const r = await fetch(url, { headers: {Accept: 'application/json'} });
        if(!r.ok) return [];
        return await r.json();
    }

    async function loadGradesAndBranches(sectionId){
        reset(grade,  'در حال بارگذاری...', true);
        reset(branch, 'در حال بارگذاری...', true);
        reset(field,  'ابتدا شاخه را انتخاب کنید', true);
        reset(subfield,'---', true);

        const gradesUrl   = section.dataset.gradesUrl.replace(':id', sectionId);
        const branchesUrl = section.dataset.branchesUrl.replace(':id', sectionId);

        const [gradesData, branchesData] = await Promise.all([
            get(gradesUrl),
            get(branchesUrl),
        ]);

        fill(grade, gradesData, oldGrade, 'انتخاب پایه...');
        fill(branch, branchesData, oldBranch, 'انتخاب شاخه...');

        if(oldBranch){
            await loadFields(oldBranch);
        }
    }

    async function loadFields(branchId){
        reset(field, 'در حال بارگذاری...', true);
        reset(subfield,'---', true);

        const fieldsUrl = branch.dataset.fieldsUrl.replace(':id', branchId);
        const fieldsData = await get(fieldsUrl);

        fill(field, fieldsData, oldField, 'انتخاب زمینه...');

        if(oldField){
            await loadSubfields(oldField);
        }
    }

    async function loadSubfields(fieldId){
        reset(subfield,'در حال بارگذاری...', true);

        const subfieldsUrl = field.dataset.subfieldsUrl.replace(':id', fieldId);
        const subfieldsData = await get(subfieldsUrl);

        if(subfieldsData.length){
            fill(subfield, subfieldsData, oldSubfield, 'انتخاب زیررشته (اختیاری)...');
        } else {
            reset(subfield,'---', false);
        }
    }

    section.addEventListener('change', async () => {
        if(!section.value){
            reset(grade,  'ابتدا مقطع را انتخاب کنید');
            reset(branch, 'ابتدا مقطع را انتخاب کنید');
            reset(field,  'ابتدا شاخه را انتخاب کنید');
            reset(subfield,'---');
            return;
        }

        // وقتی کاربر تغییر دستی داد old ها دیگر ملاک نیست
        const sectionId = section.value;
        reset(grade,  'در حال بارگذاری...');
        reset(branch, 'در حال بارگذاری...');
        await loadGradesAndBranches(sectionId);
    });

    branch.addEventListener('change', async () => {
        if(!branch.value){
            reset(field, 'ابتدا شاخه را انتخاب کنید');
            reset(subfield,'---');
            return;
        }
        await loadFields(branch.value);
    });

    field.addEventListener('change', async () => {
        if(!field.value){
            reset(subfield,'---');
            return;
        }
        await loadSubfields(field.value);
    });

    // ✅ لود اولیه روی مقادیر فعلی درس
    if(section.value){
        loadGradesAndBranches(section.value);
    }
});
</script>
@endpush
