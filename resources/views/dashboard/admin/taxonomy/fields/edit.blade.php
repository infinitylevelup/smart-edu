{{-- resources/views/dashboard/admin/taxonomy/fields/edit.blade.php --}}
@extends('layouts.app')

@section('title', ($title ?? 'زمینه‌ها') . ' | ویرایش')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">ویرایش {{ $title ?? 'زمینه' }}</h5>
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

                {{-- branch_id --}}
                @if(isset($branches))
                    <div class="mb-3">
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
                @endif

                {{-- name_fa --}}
                <div class="mb-3">
                    <label class="form-label">نام زمینه</label>
                    <input type="text" name="name_fa" class="form-control"
                           required value="{{ old('name_fa', $item->name_fa) }}">
                </div>

                {{-- slug --}}
                <div class="mb-3">
                    <label class="form-label">اسلاگ</label>
                    <input type="text" name="slug" class="form-control"
                           required value="{{ old('slug', $item->slug) }}">
                </div>

                {{-- sort_order --}}
                <div class="mb-3">
                    <label class="form-label">ترتیب نمایش</label>
                    <input type="number" name="sort_order" class="form-control"
                           min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                </div>

                {{-- is_active --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           value="1" id="is_active"
                           {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        فعال باشد
                    </label>
                </div>

                <button class="btn btn-primary">ذخیره تغییرات</button>
            </form>
        </div>
    </div>
</div>
@endsection
