@extends('layouts.app')

@section('title', 'جزئیات کاربر')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">جزئیات کاربر</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            بازگشت
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <div class="fw-bold text-muted mb-1">نام</div>
                    <div>{{ $user->name ?? '---' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="fw-bold text-muted mb-1">شماره موبایل</div>
                    <div>{{ $user->phone }}</div>
                </div>

                <div class="col-md-6">
                    <div class="fw-bold text-muted mb-1">نقش</div>
                    <div>{{ $user->role ?? 'guest' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="fw-bold text-muted mb-1">وضعیت</div>
                    <div>
                        @if($user->is_active ?? true)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-danger">غیرفعال</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fw-bold text-muted mb-1">تاریخ ثبت</div>
                    <div>{{ $user->created_at?->format('Y/m/d H:i') }}</div>
                </div>

            </div>

            <hr class="my-4">

            {{-- فرم ویرایش سریع --}}
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label">نام</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">نقش</label>
                    <select name="role" class="form-select">
                        <option value="admin" @selected($user->role=='admin')>ادمین</option>
                        <option value="teacher" @selected($user->role=='teacher')>معلم</option>
                        <option value="student" @selected($user->role=='student')>دانش‌آموز</option>
                        <option value="counselor" @selected($user->role=='counselor')>مشاور</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               id="activeCheck" @checked($user->is_active ?? true)>
                        <label class="form-check-label" for="activeCheck">
                            فعال باشد
                        </label>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary">ذخیره تغییرات</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
