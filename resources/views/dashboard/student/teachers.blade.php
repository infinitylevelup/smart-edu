@extends('layouts.app')
@section('title', 'معلمان من')

@section('content')
    <div class="container py-4">
        <h4 class="fw-bold mb-3">معلمان من</h4>

        @php
            // کلاس‌های دانش‌آموز + معلم هر کلاس
            $classrooms = auth()->user()->classrooms()->with('teacher.teacherProfile')->get();

            $teachers = $classrooms->pluck('teacher')->filter()->unique('id');

            @dd(auth()->user()->classrooms()->pluck('classrooms.id'));
            @dd(auth()->user()->classrooms()->select('id', 'name', 'teacher_id')->get());

        @endphp

        @if ($teachers->count())
            <div class="row g-3">
                @foreach ($teachers as $teacher)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-1">
                                    {{ $teacher->teacherProfile?->first_name }}
                                    {{ $teacher->teacherProfile?->last_name }}
                                </h6>
                                <div class="text-muted small mb-2">
                                    {{ $teacher->phone }}
                                </div>

                                {{-- کلاس‌های مشترک با این معلم --}}
                                @php
                                    $shared = $classrooms->where('teacher_id', $teacher->id);
                                @endphp

                                <div class="small mb-2">
                                    کلاس‌های شما با این معلم:
                                    <ul class="mb-0">
                                        @foreach ($shared as $c)
                                            <li>
                                                {{ $c->name }}
                                                <a class="ms-2"
                                                    href="{{ route('student.exams.index', ['classroom_id' => $c->id]) }}">
                                                    آزمون‌ها
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-muted">معلمی برای شما ثبت نشده است.</div>
        @endif
    </div>
@endsection
