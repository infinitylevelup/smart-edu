@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Page Header --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-1">دانش‌آموزان من</h2>
                <div class="text-muted">لیست همه‌ی دانش‌آموزان عضو کلاس‌های شما</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-secondary">
                    مدیریت کلاس‌ها
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                    راهنما
                </button>
            </div>
        </div>

        {{-- KPI cards --}}
        @php
            $totalStudents = isset($students)
                ? (method_exists($students, 'total')
                    ? $students->total()
                    : $students->count())
                : 0;

            $totalClasses = isset($classrooms) ? $classrooms->count() : 0;

            $studentsCollection = isset($students)
                ? (method_exists($students, 'items')
                    ? collect($students->items())
                    : collect($students))
                : collect();

            $activeCount = $studentsCollection->filter(fn($s) => !empty($s->email_verified_at))->count();
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small">تعداد دانش‌آموزان</div>
                        <div class="fs-3 fw-bold">{{ $totalStudents }}</div>
                        <div class="small text-muted mt-1">
                            اعضای تمامی کلاس‌های شما
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small">تعداد کلاس‌ها</div>
                        <div class="fs-3 fw-bold">{{ $totalClasses }}</div>
                        <div class="small text-muted mt-1">
                            کلاس‌های فعال شما
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="text-muted small">کاربران فعال (تقریبی)</div>
                        <div class="fs-3 fw-bold">{{ $activeCount }}</div>
                        <div class="small text-muted mt-1">
                            بر اساس تایید ایمیل/فعالیت
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters / Search --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" class="row g-2 g-md-3 align-items-end">

                    <div class="col-md-5">
                        <label class="form-label small text-muted mb-1">جستجو</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                                placeholder="نام، ایمیل یا بخشی از مشخصات...">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">فیلتر بر اساس کلاس</label>
                        <select name="classroom_id" class="form-select">
                            <option value="">همه کلاس‌ها</option>
                            @if (isset($classrooms))
                                @foreach ($classrooms as $c)
                                    <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>
                                        {{ $c->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary w-100">
                            اعمال
                        </button>
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary w-100">
                            ریست
                        </a>
                    </div>

                </form>
            </div>
        </div>

        {{-- Students List --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div
                class="card-header bg-white border-0 pt-4 px-3 px-md-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="fw-bold mb-0">لیست دانش‌آموزان</h5>

                <div class="small text-muted">
                    {{ $totalStudents }} نفر
                </div>
            </div>

            <div class="card-body px-3 px-md-4 pb-4">

                @if (!isset($students) || $totalStudents == 0)
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-people fs-1 text-muted"></i>
                        </div>
                        <h6 class="fw-bold">هنوز دانش‌آموزی در کلاس‌های شما عضو نشده است</h6>
                        <p class="text-muted small mb-3">
                            از بخش کلاس‌ها می‌توانید دانش‌آموز اضافه کنید.
                        </p>
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-primary">
                            رفتن به کلاس‌ها
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px;">#</th>
                                    <th>دانش‌آموز</th>
                                    <th>ایمیل</th>
                                    <th>کلاس‌ها</th>
                                    <th>وضعیت</th>
                                    <th class="text-end" style="width:220px;">عملیات</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($students as $i => $student)
                                    @php
                                        $index = method_exists($students, 'firstItem')
                                            ? $students->firstItem() + $i
                                            : $i + 1;
                                        $studentClassrooms = $student->classrooms ?? collect();
                                    @endphp

                                    <tr>
                                        <td class="text-muted">{{ $index }}</td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width:42px;height:42px;font-size:16px;">
                                                    {{ mb_substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $student->name }}</div>
                                                    <div class="small text-muted">
                                                        ID: {{ $student->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="small">{{ $student->email }}</td>

                                        <td>
                                            @if ($studentClassrooms->count())
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach ($studentClassrooms->take(3) as $c)
                                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                                            {{ $c->title }}
                                                        </span>
                                                    @endforeach
                                                    @if ($studentClassrooms->count() > 3)
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                                                            +{{ $studentClassrooms->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($student->email_verified_at))
                                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                    فعال
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                                    نیمه‌فعال
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('teacher.students.show', $student) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    پروفایل
                                                </a>

                                                {{-- Quick jump to classrooms shared --}}
                                                @if ($studentClassrooms->count())
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">کلاس‌ها</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @foreach ($studentClassrooms as $c)
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('teacher.classes.show', $c) }}">
                                                                    {{ $c->title }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    {{-- Pagination (اگر paginate باشد) --}}
                    @if (method_exists($students, 'links'))
                        <div class="mt-3">
                            {{ $students->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>

    {{-- Help Modal --}}
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">راهنمای صفحه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <ul class="small text-muted mb-0">
                        <li>با جستجو می‌توانید دانش‌آموزان را بر اساس نام یا ایمیل پیدا کنید.</li>
                        <li>فیلتر کلاس، فقط دانش‌آموزان همان کلاس را نشان می‌دهد.</li>
                        <li>روی «پروفایل» کلیک کنید تا نتایج آزمون‌های دانش‌آموز را ببینید.</li>
                        <li>از منوی کلاس‌ها می‌توانید سریع به کلاس مرتبط بروید.</li>
                    </ul>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">باشه</button>
                </div>
            </div>
        </div>
    </div>
@endsection
