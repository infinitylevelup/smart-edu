@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">جزئیات کلاس</h5>
        <a href="{{ route('admin.classrooms.index') }}" class="btn btn-sm btn-secondary">
            برگشت
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">عنوان</div>
                    <div class="fw-semibold">{{ $classroom->title ?? '---' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">کد کلاس</div>
                    <div class="fw-semibold">{{ $classroom->code ?? '---' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">معلّم</div>
                    <div class="fw-semibold">
                        {{ $classroom->teacher_name ?? ($classroom->teacher->name ?? '---') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">وضعیت</div>
                    <div class="fw-semibold">
                        @if(($classroom->is_active ?? true))
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غیرفعال</span>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            <form method="POST" action="{{ route('admin.classrooms.update', $classroom->id) }}">
                @csrf
                @method('PUT')

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ ($classroom->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">کلاس فعال باشد</label>
                </div>

                <button class="btn btn-sm btn-primary mt-3">ذخیره وضعیت</button>
            </form>
        </div>
    </div>

</div>
@endsection
