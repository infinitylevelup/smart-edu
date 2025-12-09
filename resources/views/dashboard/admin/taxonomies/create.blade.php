@extends('layouts.app')

@php
    $title = $title ?? 'آیتم';
    $routeName = $routeName ?? 'admin.sections';
@endphp

@section('title', 'ایجاد ' . $title)

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">ایجاد {{ $title }}</h3>
            <a href="{{ route($routeName . '.index') }}" class="btn btn-outline-secondary btn-sm">
                بازگشت به لیست
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @include('dashboard.admin.taxonomies._form', [
                    'mode' => 'create',
                    'title' => $title,
                    'routeName' => $routeName,
                    'formFields' => $formFields ?? null,
                ])
            </div>
        </div>

    </div>
@endsection
