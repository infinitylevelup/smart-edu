@extends('layouts.app')

@section('title', 'داشبورد ادمین')

@section('content')
<div class="container-fluid py-3">

    {{-- عنوان --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">داشبورد ادمین</h4>
        <span class="text-muted small">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</span>
    </div>

    {{-- کارت‌های آمار --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">ادمین‌ها</div>
                    <div class="fs-3 fw-bold">{{ $counts['admins'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">معلم‌ها</div>
                    <div class="fs-3 fw-bold">{{ $counts['teachers'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">دانش‌آموزها</div>
                    <div class="fs-3 fw-bold">{{ $counts['students'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">کلاس‌ها</div>
                    <div class="fs-3 fw-bold">{{ $counts['classrooms'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">دروس</div>
                    <div class="fs-3 fw-bold">{{ $counts['subjects'] }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-2">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="fw-bold text-muted mb-1">آزمون‌ها</div>
                    <div class="fs-3 fw-bold">{{ $counts['exams'] }}</div>
                </div>
            </div>
        </div>

    </div>


    <div class="row g-3">

        {{-- آخرین کاربران --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    آخرین کاربران
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>نام</th>
                                    <th>شماره</th>
                                    <th>نقش</th>
                                    <th class="text-nowrap">تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestUsers as $u)
                                    <tr>
                                        <td>{{ $u->name ?? '---' }}</td>
                                        <td>{{ $u->phone }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $u->role ?? 'guest' }}
                                            </span>
                                        </td>
                                        <td class="small text-muted text-nowrap">
                                            {{ $u->created_at?->format('Y/m/d') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            کاربری ثبت نشده است.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                        مدیریت کاربران
                    </a>
                </div>
            </div>
        </div>


        {{-- آخرین کلاس‌ها --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    آخرین کلاس‌ها
                </div>

                <div class="card-body p-0">
                    @if($latestClassrooms->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>عنوان</th>
                                        <th>معلم</th>
                                        <th class="text-nowrap">تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestClassrooms as $c)
                                        <tr>
                                            <td>{{ $c->title ?? $c->name ?? '---' }}</td>
                                            <td>{{ $c->teacher?->name ?? '---' }}</td>
                                            <td class="small text-muted text-nowrap">
                                                {{ $c->created_at?->format('Y/m/d') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            کلاسی ثبت نشده است.
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-white text-end">
                    <a href="{{ route('admin.classrooms.index') }}" class="btn btn-sm btn-outline-primary">
                        مدیریت کلاس‌ها
                    </a>
                </div>
            </div>
        </div>


        {{-- آخرین آزمون‌ها --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    آخرین آزمون‌ها
                </div>

                <div class="card-body p-0">
                    @if($latestExams->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>عنوان</th>
                                        <th>معلم</th>
                                        <th class="text-nowrap">تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestExams as $e)
                                        <tr>
                                            <td>{{ $e->title ?? '---' }}</td>
                                            <td>{{ $e->teacher?->name ?? '---' }}</td>
                                            <td class="small text-muted text-nowrap">
                                                {{ $e->created_at?->format('Y/m/d') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            آزمونی ثبت نشده است.
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-white text-end">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-sm btn-outline-primary">
                        مدیریت آزمون‌ها
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
