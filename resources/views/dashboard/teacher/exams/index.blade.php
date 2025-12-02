@extends('layouts.app')
@section('title', 'آزمون‌ها')

@section('content')
    <div class="container py-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1">آزمون‌های من</h4>
                <div class="text-muted small">همه آزمون‌های کلاس‌های شما</div>
            </div>

            <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> ساخت آزمون جدید
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- فیلتر کلاس --}}
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
                <label class="form-label small text-muted">فیلتر بر اساس کلاس</label>
                <select name="classroom_id" class="form-select">
                    <option value="">همه کلاس‌ها</option>
                    @foreach ($classrooms as $c)
                        <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>
                            {{ $c->title ?? $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100">
                    اعمال فیلتر
                </button>
            </div>
        </form>

        @if ($exams->count() === 0)
            <div class="alert alert-warning">
                هنوز آزمونی نساخته‌اید.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان آزمون</th>
                            <th>کلاس</th>
                            <th>مدت</th>
                            <th>وضعیت</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $exam->title }}</td>
                                <td class="text-muted small">
                                    {{ $exam->classroom->title ?? ($exam->classroom->name ?? '—') }}
                                </td>
                                <td class="text-muted small">
                                    {{ $exam->duration ?? ($exam->duration_minutes ?? '—') }} دقیقه
                                </td>
                                <td>
                                    @if ($exam->is_active)
                                        <span class="badge bg-success">فعال</span>
                                    @else
                                        <span class="badge bg-secondary">غیرفعال</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('teacher.exams.show', $exam) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        جزئیات
                                    </a>
                                    <a href="{{ route('teacher.exams.edit', $exam) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        ویرایش
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
