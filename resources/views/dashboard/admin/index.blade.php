@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="fw-bold">پنل سوپرادمین</h1>
        <p class="text-muted">خوش آمدی {{ auth()->user()->phone }}</p>

        <div class="alert alert-danger mt-3">
            اینجا داشبورد سوپرادمین است. بعداً بخش‌های مدیریتی را اضافه می‌کنیم.
        </div>
    </div>
@endsection
