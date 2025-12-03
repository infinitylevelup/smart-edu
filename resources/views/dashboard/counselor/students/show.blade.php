@extends('layouts.app')
@section('title', 'Student Profile')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h5 mb-1">{{ $student->name }}</h1>
                <div class="text-muted small">{{ $student->email }}</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('counselor.attempts.index', $student->id) }}" class="btn btn-primary">
                    Attemptها
                </a>
                <a href="{{ route('counselor.learning-paths.create', $student->id) }}" class="btn btn-outline-primary">
                    ساخت مسیر پیشنهادی
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h2 class="h6 mb-0">خلاصه وضعیت</h2>
                    </div>
                    <div class="card-body text-muted small">
                        این بخش در فاز بعدی با آمار آزمون‌ها و پیشرفت پر می‌شود.
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h2 class="h6 mb-0">آخرین تحلیل‌ها</h2>
                    </div>
                    <div class="card-body text-muted small">
                        این بخش بعداً از counselor_analyses پر می‌شود.
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
