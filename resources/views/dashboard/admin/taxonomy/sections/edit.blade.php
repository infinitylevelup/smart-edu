@extends('layouts.app')
@section('title', 'ویرایش ' . $title)

@section('content')
    <div class="container py-4">
        <h3 class="fw-bold mb-3">ویرایش {{ $title }}</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route($routeName . '.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('dashboard.admin.taxonomy.partials.form', ['item' => $item])

                    <button class="btn btn-primary">بروزرسانی</button>
                    <a href="{{ route($routeName . '.index') }}" class="btn btn-secondary">بازگشت</a>
                </form>
            </div>
        </div>
    </div>
@endsection
