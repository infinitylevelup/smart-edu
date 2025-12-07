@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">جزئیات آزمون</h5>
        <a href="{{ route('admin.exams.index') }}" class="btn btn-sm btn-secondary">برگشت</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="text-muted small">عنوان</div>
            <div class="fw-semibold mb-3">{{ $exam->title ?? $exam->name ?? '---' }}</div>

            <div class="text-muted small">وضعیت</div>
            @if(($exam->is_active ?? true))
                <span class="badge bg-success">فعال</span>
            @else
                <span class="badge bg-secondary">غیرفعال</span>
            @endif
        </div>
    </div>
</div>
@endsection
