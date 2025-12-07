@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">مدیریت کلاس‌ها</h5>

        <form class="d-flex" method="GET">
            <input name="q" value="{{ request('q') }}"
                   class="form-control form-control-sm me-2"
                   placeholder="جستجو عنوان/کد کلاس">
            <button class="btn btn-sm btn-primary">جستجو</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>عنوان</th>
                    <th>کد</th>
                    <th>معلّم</th>
                    <th>وضعیت</th>
                    <th class="text-end">عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $i => $c)
                    <tr>
                        <td>{{ $items->firstItem() + $i }}</td>
                        <td class="fw-semibold">{{ $c->title ?? '---' }}</td>
                        <td>{{ $c->code ?? '---' }}</td>
                        <td>{{ $c->teacher_name ?? ($c->teacher->name ?? '---') }}</td>
                        <td>
                            @if(($c->is_active ?? true))
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-secondary">غیرفعال</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.classrooms.show', $c->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                مشاهده
                            </a>

                            <form action="{{ route('admin.classrooms.destroy', $c->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('کلاس حذف شود؟');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">کلاسی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white">
            {{ $items->links() }}
        </div>
    </div>

</div>
@endsection
