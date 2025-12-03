@extends('layouts.app')
@section('title', 'Student Attempts')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h5 mb-1">Attemptهای {{ $student->name }}</h1>
                <div class="text-muted small">لیست آزمون‌های انجام شده و قابل تحلیل</div>
            </div>
            <a href="{{ route('counselor.students.show', $student->id) }}" class="btn btn-outline-secondary">
                بازگشت به پروفایل
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                @if ($attempts->count() == 0)
                    <div class="alert alert-warning mb-0">
                        هنوز Attemptی ثبت نشده است.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>آزمون</th>
                                    <th>درس</th>
                                    <th>درصد</th>
                                    <th>تاریخ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attempts as $i => $attempt)
                                    <tr>
                                        <td>{{ $attempts->firstItem() + $i }}</td>
                                        <td>{{ $attempt->exam->title ?? '—' }}</td>
                                        <td>{{ $attempt->exam->subject->title ?? ($attempt->exam->subject->name ?? '—') }}
                                        </td>
                                        <td>{{ $attempt->percent ?? 0 }}%</td>
                                        <td>{{ optional($attempt->submitted_at ?? $attempt->created_at)->format('Y/m/d') }}
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="{{ route('counselor.attempts.show', $attempt->id) }}">
                                                نتیجه
                                            </a>
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('counselor.attempts.analyze.edit', $attempt->id) }}">
                                                تحلیل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $attempts->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
