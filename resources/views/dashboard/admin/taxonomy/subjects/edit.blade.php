{{-- resources/views/dashboard/admin/taxonomy/subjects/edit.blade.php --}}
@extends('layouts.app')

@section('title', ($title ?? 'دروس') . ' | ویرایش')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">ویرایش {{ $title ?? 'درس' }}</h5>
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
            <form action="{{ route($routeName.'.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">نام درس</label>
                        <input type="text" name="title_fa" class="form-control"
                               required value="{{ old('title_fa', $item->title_fa) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">اسلاگ</label>
                        <input type="text" name="slug" class="form-control"
                               required value="{{ old('slug', $item->slug) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">کد درس</label>
                        <input type="text" name="code" class="form-control"
                               value="{{ old('code', $item->code) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ساعت</label>
                        <input type="number" name="hours" min="0" class="form-control"
                               value="{{ old('hours', $item->hours ?? 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">ترتیب نمایش</label>
                        <input type="number" name="sort_order" min="0" class="form-control"
                               value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">مقطع</label>
                        <select name="section_id" class="form-select" required>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('section_id', $item->section_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">پایه</label>
                        <select name="grade_id" class="form-select" required>
                            @foreach($grades as $g)
                                <option value="{{ $g->id }}"
                                    {{ old('grade_id', $item->grade_id) == $g->id ? 'selected' : '' }}>
                                    {{ $g->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">شاخه</label>
                        <select name="branch_id" class="form-select" required>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}"
                                    {{ old('branch_id', $item->branch_id) == $b->id ? 'selected' : '' }}>
                                    {{ $b->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">زمینه</label>
                        <select name="field_id" class="form-select" required>
                            @foreach($fields as $f)
                                <option value="{{ $f->id }}"
                                    {{ old('field_id', $item->field_id) == $f->id ? 'selected' : '' }}>
                                    {{ $f->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">زیررشته (اختیاری)</label>
                        <select name="subfield_id" class="form-select">
                            <option value="">---</option>
                            @foreach($subfields as $sf)
                                <option value="{{ $sf->id }}"
                                    {{ old('subfield_id', $item->subfield_id) == $sf->id ? 'selected' : '' }}>
                                    {{ $sf->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">نوع درس (اختیاری)</label>
                        <select name="subject_type_id" class="form-select">
                            <option value="">---</option>
                            @foreach($subjectTypes as $st)
                                <option value="{{ $st->id }}"
                                    {{ old('subject_type_id', $item->subject_type_id) == $st->id ? 'selected' : '' }}>
                                    {{ $st->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   value="1" id="is_active"
                                   {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">فعال باشد</label>
                        </div>
                    </div>

                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">ذخیره تغییرات</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
