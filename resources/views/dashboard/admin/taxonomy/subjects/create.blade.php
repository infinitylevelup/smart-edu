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

                    {{-- Taxonomy FK --}}
                    <div class="col-md-4">
                        <label class="form-label">مقطع</label>
                        <select name="section_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}" {{ old('section_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">پایه</label>
                        <select name="grade_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($grades as $g)
                                <option value="{{ $g->id }}" {{ old('grade_id') == $g->id ? 'selected' : '' }}>
                                    {{ $g->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">شاخه</label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">زمینه</label>
                        <select name="field_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($fields as $f)
                                <option value="{{ $f->id }}" {{ old('field_id') == $f->id ? 'selected' : '' }}>
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
                                <option value="{{ $sf->id }}" {{ old('subfield_id') == $sf->id ? 'selected' : '' }}>
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
