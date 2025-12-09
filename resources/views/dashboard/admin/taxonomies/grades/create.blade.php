{{-- resources/views/dashboard/admin/taxonomy/grades/create.blade.php --}}
@extends('layouts.app')

@section('title', ($title ?? 'پایه‌ها') . ' | ایجاد')

@section('content')
<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">
            ایجاد {{ $title ?? 'پایه' }}
        </h5>
        <a href="{{ route($routeName.'.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
            برگشت
        </a>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route($routeName.'.store') }}" method="POST">
                @csrf

                {{-- Parent: Section --}}
                @if(isset($sections))
                    <div class="mb-3">
                        <label class="form-label">مقطع</label>
                        <select name="section_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($sections as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('section_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name_fa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- value (required in DB) --}}
                <div class="mb-3">
                    <label class="form-label">عدد پایه (value)</label>
                    <input type="number"
                           name="value"
                           class="form-control"
                           min="1"
                           required
                           value="{{ old('value') }}">
                </div>

                {{-- name_fa --}}
                <div class="mb-3">
                    <label class="form-label">نام پایه</label>
                    <input type="text"
                           name="name_fa"
                           class="form-control"
                           required
                           value="{{ old('name_fa') }}">
                </div>

                {{-- slug --}}
                <div class="mb-3">
                    <label class="form-label">اسلاگ</label>
                    <input type="text"
                           name="slug"
                           class="form-control"
                           required
                           value="{{ old('slug') }}">
                    <div class="form-text">مثال: paye-aval</div>
                </div>

                {{-- sort_order --}}
                <div class="mb-3">
                    <label class="form-label">ترتیب نمایش</label>
                    <input type="number"
                           name="sort_order"
                           class="form-control"
                           min="0"
                           value="{{ old('sort_order', 0) }}">
                </div>

                {{-- is_active --}}
                <div class="form-check mb-3">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           value="1"
                           id="is_active"
                           {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        فعال باشد
                    </label>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        ذخیره
                    </button>
                    <a href="{{ route($routeName.'.index') }}" class="btn btn-light">
                        انصراف
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
