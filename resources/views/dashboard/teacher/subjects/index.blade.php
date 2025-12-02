@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h4 class="mb-3">مدیریت درس‌ها (Subjects)</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('teacher.subjects.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">نام درس</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="مثلاً ریاضی">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-primary">ثبت درس</button>
                </form>
            </div>
        </div>
    </div>
@endsection
