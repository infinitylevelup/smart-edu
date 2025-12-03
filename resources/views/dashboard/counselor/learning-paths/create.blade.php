@extends('layouts.app')
@section('title', 'Create Learning Path')

@section('content')
    <div class="container-fluid">
        <h1 class="h5 mb-3">ایجاد مسیر پیشنهادی برای {{ $student->name }}</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body text-muted small">
                فرم ساخت مسیر در فاز بعدی اضافه می‌شود.
                فعلاً فقط اسکلت این صفحه آماده است.
            </div>
        </div>
    </div>
@endsection
