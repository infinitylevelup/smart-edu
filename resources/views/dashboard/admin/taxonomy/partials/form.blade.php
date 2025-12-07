@php $item = $item ?? null; @endphp

{{-- اگر FK لازم داشت، هر کنترلر دیتا میده و اینجا می‌خونیم --}}
@if (isset($sections))
    <div class="mb-3">
        <label class="form-label">section_id</label>
        <select name="section_id" class="form-select">
            <option value="">انتخاب مقطع</option>
            @foreach ($sections as $s)
                <option value="{{ $s->id }}" @selected(old('section_id', $item->section_id ?? '') == $s->id)>
                    {{ $s->name_fa }}
                </option>
            @endforeach
        </select>
    </div>
@endif

@if (isset($branches))
    <div class="mb-3">
        <label class="form-label">branch_id</label>
        <select name="branch_id" class="form-select">
            <option value="">انتخاب شاخه</option>
            @foreach ($branches as $b)
                <option value="{{ $b->id }}" @selected(old('branch_id', $item->branch_id ?? '') == $b->id)>
                    {{ $b->name_fa }}
                </option>
            @endforeach
        </select>
    </div>
@endif

@if (isset($fields))
    <div class="mb-3">
        <label class="form-label">field_id</label>
        <select name="field_id" class="form-select">
            <option value="">انتخاب زمینه</option>
            @foreach ($fields as $f)
                <option value="{{ $f->id }}" @selected(old('field_id', $item->field_id ?? '') == $f->id)>
                    {{ $f->name_fa }}
                </option>
            @endforeach
        </select>
    </div>
@endif

{{-- فیلدهای عمومی --}}
<div class="mb-3">
    <label class="form-label">name_fa</label>
    <input name="name_fa" class="form-control" value="{{ old('name_fa', $item->name_fa ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">slug</label>
    <input name="slug" class="form-control" value="{{ old('slug', $item->slug ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">sort_order</label>
    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
    <label class="form-check-label">فعال باشد</label>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
