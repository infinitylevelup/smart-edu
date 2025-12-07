@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h5 class="fw-bold mb-3">مدیریت آزمون‌ها</h5>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>وضعیت</th>
                        <th class="text-end">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $i => $e)
                    <tr>
                        <td>{{ method_exists($items,'firstItem') ? $items->firstItem()+$i : $i+1 }}</td>
                        <td>{{ $e->title ?? $e->name ?? '---' }}</td>
                        <td>
                            @if(($e->is_active ?? true))
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-secondary">غیرفعال</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.exams.show', $e->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                مشاهده
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">آزمونی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($items,'links'))
        <div class="card-footer bg-white">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
