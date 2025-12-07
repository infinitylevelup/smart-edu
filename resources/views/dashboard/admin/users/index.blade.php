@extends('layouts.app')

@section('title', 'مدیریت کاربران')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">مدیریت کاربران</h4>
    </div>

    {{-- پیام‌ها --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- فیلتر و سرچ --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-12 col-md-4">
            <input type="text" name="q" value="{{ $q }}" class="form-control"
                   placeholder="جستجو نام یا شماره">
        </div>

        <div class="col-12 col-md-3">
            <select name="role" class="form-select">
                <option value="all" @selected($role=='all')>همه نقش‌ها</option>
                <option value="admin" @selected($role=='admin')>ادمین</option>
                <option value="teacher" @selected($role=='teacher')>معلم</option>
                <option value="student" @selected($role=='student')>دانش‌آموز</option>
                <option value="counselor" @selected($role=='counselor')>مشاور</option>
            </select>
        </div>

        <div class="col-12 col-md-2">
            <button class="btn btn-primary w-100">اعمال</button>
        </div>

        <div class="col-12 col-md-3 text-md-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                پاک کردن فیلترها
            </a>
        </div>
    </form>

    {{-- جدول کاربران --}}
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>شماره</th>
                        <th>نقش</th>
                        <th>وضعیت</th>
                        <th class="text-nowrap">تاریخ ثبت</th>
                        <th class="text-end">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>
                            <td>{{ $u->name ?? '---' }}</td>
                            <td>{{ $u->phone }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $u->role ?? 'guest' }}</span>
                            </td>
                            <td>
                                @if($u->is_active ?? true)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td class="small text-muted text-nowrap">
                                {{ $u->created_at?->format('Y/m/d') }}
                            </td>

                            <td class="text-end">
                                {{-- مشاهده --}}
                                <a href="{{ route('admin.users.show', $u->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    مشاهده
                                </a>

                                {{-- فعال/غیرفعال --}}
                                <form action="{{ route('admin.users.update', $u->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ ($u->is_active ?? true) ? 0 : 1 }}">
                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="return confirm('وضعیت کاربر تغییر کند؟')">
                                        {{ ($u->is_active ?? true) ? 'غیرفعال' : 'فعال' }}
                                    </button>
                                </form>

                                {{-- حذف --}}
                                <form action="{{ route('admin.users.destroy', $u->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('حذف کاربر؟')">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                کاربری یافت نشد.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- صفحه‌بندی --}}
    <div class="mt-3">
        {{ $users->links() }}
    </div>

</div>
@endsection
