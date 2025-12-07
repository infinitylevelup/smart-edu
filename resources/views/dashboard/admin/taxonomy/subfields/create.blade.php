{{-- resources/views/dashboard/admin/taxonomy/subfields/create.blade.php --}}
@extends('layouts.app')

@section('title', ($title ?? 'زیررشته‌ها') . ' | ایجاد')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">ایجاد {{ $title ?? 'زیررشته' }}</h5>
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

                {{-- field_id --}}
                <div class="mb-3">
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

                {{-- name_fa --}}
                <div class="mb-3">
                    <label class="form-label">نام زیررشته</label>
                    <input type="text" name="name_fa" class="form-control"
                           required value="{{ old('name_fa') }}">
                </div>

                {{-- slug --}}
                <div class="mb-3">
                    <label class="form-label">اسلاگ</label>
                    <input type="text" name="slug" class="form-control"
                           required value="{{ old('slug') }}">
                </div>

                {{-- sort_order --}}
                <div class="mb-3">
                    <label class="form-label">ترتیب نمایش</label>
                    <input type="number" name="sort_order" class="form-control"
                           min="0" value="{{ old('sort_order', 0) }}">
                </div>

                {{-- is_active --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           value="1" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        فعال باشد
                    </label>
                </div>

                <button class="btn btn-primary">ذخیره</button>
            </form>
        </div>
    </div>
</div>
@endsection
