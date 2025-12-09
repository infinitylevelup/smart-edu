@php $item = $item ?? null; @endphp

<div class="row g-3">

    <div class="col-md-4">
        <label class="form-label">مقطع (section)</label>
        <select name="section_id" class="form-select">
            <option value="">انتخاب</option>
            @foreach ($sections as $s)
                <option value="{{ $s->id }}" @selected(old('section_id', $item->section_id ?? '') == $s->id)>
                    {{ $s->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">پایه (grade)</label>
        <select name="grade_id" class="form-select">
            <option value="">انتخاب</option>
            @foreach ($grades as $g)
                <option value="{{ $g->id }}" @selected(old('grade_id', $item->grade_id ?? '') == $g->id)>
                    {{ $g->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">شاخه (branch)</label>
        <select name="branch_id" class="form-select">
            <option value="">انتخاب</option>
            @foreach ($branches as $b)
                <option value="{{ $b->id }}" @selected(old('branch_id', $item->branch_id ?? '') == $b->id)>
                    {{ $b->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">زمینه (field)</label>
        <select name="field_id" class="form-select">
            <option value="">انتخاب</option>
            @foreach ($fields as $f)
                <option value="{{ $f->id }}" @selected(old('field_id', $item->field_id ?? '') == $f->id)>
                    {{ $f->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">زیررشته (subfield)</label>
        <select name="subfield_id" class="form-select">
            <option value="">اختیاری</option>
            @foreach ($subfields as $sf)
                <option value="{{ $sf->id }}" @selected(old('subfield_id', $item->subfield_id ?? '') == $sf->id)>
                    {{ $sf->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">دسته درسی (subject type)</label>
        <select name="subject_type_id" class="form-select">
            <option value="">انتخاب</option>
            @foreach ($subjectTypes as $st)
                <option value="{{ $st->id }}" @selected(old('subject_type_id', $item->subject_type_id ?? '') == $st->id)>
                    {{ $st->name_fa }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">عنوان درس</label>
        <input name="title_fa" class="form-control" value="{{ old('title_fa', $item->title_fa ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">slug</label>
        <input name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">کد درس</label>
        <input name="code" class="form-control" value="{{ old('code', $item->code ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">ساعت</label>
        <input type="number" name="hours" class="form-control" value="{{ old('hours', $item->hours ?? 0) }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">ترتیب</label>
        <input type="number" name="sort_order" class="form-control"
            value="{{ old('sort_order', $item->sort_order ?? 0) }}">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                @checked(old('is_active', $item->is_active ?? true))>
            <label class="form-check-label">فعال باشد</label>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
