@extends('layouts.app')
@section('title', 'ایجاد ' . $title)

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">ایجاد {{ $title }}</h3>
        <a href="{{ route($routeName . '.index') }}" class="btn btn-secondary">
            بازگشت به لیست
        </a>
    </div>

    {{-- نمایش خطاها --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route($routeName . '.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">نام مقطع</label>
                    <input type="text" name="name_fa" class="form-control"
                           value="{{ old('name_fa') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">اسلاگ</label>
                    <input type="text" name="slug" class="form-control"
                           value="{{ old('slug') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ترتیب</label>
                    <input type="number" name="sort_order" class="form-control"
                           value="{{ old('sort_order', 0) }}">
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label">فعال</label>
                </div>

                <button type="submit" class="btn btn-success">ایجاد</button>
            </form>
        </div>
    </div>
</div>
@endsection
