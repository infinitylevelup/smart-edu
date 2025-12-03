@extends('layouts.app')
@section('title', 'Students')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h5 mb-0">دانش‌آموزها</h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                @if ($students->count() == 0)
                    <div class="alert alert-warning mb-0">
                        فعلاً دانش‌آموزی یافت نشد.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نام</th>
                                    <th>ایمیل</th>
                                    <th>تاریخ عضویت</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $i => $student)
                                    <tr>
                                        <td>{{ $students->firstItem() + $i }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ optional($student->created_at)->format('Y/m/d') }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="{{ route('counselor.students.show', $student->id) }}">
                                                مشاهده
                                            </a>
                                            <a class="btn btn-sm btn-outline-secondary"
                                                href="{{ route('counselor.attempts.index', $student->id) }}">
                                                Attemptها
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $students->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
