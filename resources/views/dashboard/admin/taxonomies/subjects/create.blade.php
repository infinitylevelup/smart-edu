{{-- resources/views/dashboard/admin/taxonomy/subjects/create.blade.php --}}
@extends('layouts.app')

@section('title', ($title ?? 'دروس') . ' | ایجاد')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">ایجاد {{ $title ?? 'درس' }}</h5>
        <a href="{{ route($routeName.'.index') }}" class="btn btn-sm btn-outline-secondary">برگشت</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route($routeName.'.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">نام درس</label>
                        <input type="text" name="title_fa" class="form-control"
                               required value="{{ old('title_fa') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اسلاگ</label>
                        <input type="text" name="slug" class="form-control"
                               required value="{{ old('slug') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">کد درس</label>
                        <input type="text" name="code" class="form-control"
                               value="{{ old('code') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ساعت</label>
                        <input type="number" name="hours" min="0" class="form-control"
                               value="{{ old('hours', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ترتیب نمایش</label>
                        <input type="number" name="sort_order" min="0" class="form-control"
                               value="{{ old('sort_order', 0) }}">
                    </div>

                    {{-- =========================
                         Taxonomy FK (Smart)
                       ========================= --}}

                    {{-- مقطع --}}
                    <div class="col-md-4">
                        <label class="form-label">مقطع</label>
                        <select id="section_id" name="section_id" class="form-select" required
                                data-grades-url="{{ route('admin.api.grades.by-section', ':id') }}"
                                data-branches-url="{{ route('admin.api.branches.by-section', ':id') }}">
                            <option value="">انتخاب کنید...</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}" {{ old('section_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- پایه (وابسته به مقطع) --}}
                    <div class="col-md-4">
                        <label class="form-label">پایه</label>
                        <select id="grade_id" name="grade_id" class="form-select" required disabled>
                            <option value="">ابتدا مقطع را انتخاب کنید</option>
                        </select>
                    </div>

                    {{-- شاخه (وابسته به مقطع) --}}
                    <div class="col-md-4">
                        <label class="form-label">شاخه</label>
                        <select id="branch_id" name="branch_id" class="form-select" required disabled
                                data-fields-url="{{ route('admin.api.fields.by-branch', ':id') }}">
                            <option value="">ابتدا مقطع را انتخاب کنید</option>
                        </select>
                    </div>

                    {{-- زمینه (وابسته به شاخه) --}}
                    <div class="col-md-4">
                        <label class="form-label">زمینه</label>
                        <select id="field_id" name="field_id" class="form-select" required disabled
                                data-subfields-url="{{ route('admin.api.subfields.by-field', ':id') }}">
                            <option value="">ابتدا شاخه را انتخاب کنید</option>
                        </select>
                    </div>

                    {{-- زیررشته (وابسته به زمینه / اختیاری) --}}
                    <div class="col-md-4">
                        <label class="form-label">زیررشته (اختیاری)</label>
                        <select id="subfield_id" name="subfield_id" class="form-select" disabled>
                            <option value="">---</option>
                        </select>
                    </div>

                    {{-- نوع درس (اختیاری / مستقل) --}}
                    <div class="col-md-4">
                        <label class="form-label">نوع درس (اختیاری)</label>
                        <select id="subject_type_id" name="subject_type_id" class="form-select">
                            <option value="">---</option>
                            @foreach($subjectTypes as $st)
                                <option value="{{ $st->id }}" {{ old('subject_type_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Active --}}
                    <div class="col-12">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                فعال باشد
                            </label>
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">ذخیره</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const section  = document.getElementById('section_id');
    const grade    = document.getElementById('grade_id');
    const branch   = document.getElementById('branch_id');
    const field    = document.getElementById('field_id');
    const subfield = document.getElementById('subfield_id');

    const oldGrade    = "{{ old('grade_id') }}";
    const oldBranch   = "{{ old('branch_id') }}";
    const oldField    = "{{ old('field_id') }}";
    const oldSubfield = "{{ old('subfield_id') }}";

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

    async function loadGradesAndBranches(sectionId, useOld=true){
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

        fill(grade, gradesData, useOld ? oldGrade : null, 'انتخاب پایه...');
        fill(branch, branchesData, useOld ? oldBranch : null, 'انتخاب شاخه...');

        if(useOld && oldBranch){
            await loadFields(oldBranch, true);
        }
    }

    async function loadFields(branchId, useOld=true){
        reset(field, 'در حال بارگذاری...', true);
        reset(subfield,'---', true);

        const fieldsUrl = branch.dataset.fieldsUrl.replace(':id', branchId);
        const fieldsData = await get(fieldsUrl);

        fill(field, fieldsData, useOld ? oldField : null, 'انتخاب زمینه...');

        if(useOld && oldField){
            await loadSubfields(oldField, true);
        }
    }

    async function loadSubfields(fieldId, useOld=true){
        reset(subfield,'در حال بارگذاری...', true);

        const subfieldsUrl = field.dataset.subfieldsUrl.replace(':id', fieldId);
        const subfieldsData = await get(subfieldsUrl);

        if(subfieldsData.length){
            fill(subfield, subfieldsData, useOld ? oldSubfield : null, 'انتخاب زیررشته (اختیاری)...');
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
        await loadGradesAndBranches(section.value, false);
    });

    branch.addEventListener('change', async () => {
        if(!branch.value){
            reset(field, 'ابتدا شاخه را انتخاب کنید');
            reset(subfield,'---');
            return;
        }
        await loadFields(branch.value, false);
    });

    field.addEventListener('change', async () => {
        if(!field.value){
            reset(subfield,'---');
            return;
        }
        await loadSubfields(field.value, false);
    });

    // لود اولیه اگر old section داشت
    if(section.value){
        loadGradesAndBranches(section.value, true);
    } else {
        reset(grade,  'ابتدا مقطع را انتخاب کنید');
        reset(branch, 'ابتدا مقطع را انتخاب کنید');
        reset(field,  'ابتدا شاخه را انتخاب کنید');
        reset(subfield,'---');
    }
});
</script>
@endpush
