@extends('layouts.app')
@section('title', 'مدیریت ' . $title)

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">مدیریت {{ $title }}</h3>
            <a href="{{ route($routeName . '.create') }}" class="btn btn-success">
                + ایجاد جدید
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            @foreach ($listColumns as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i => $item)
                            <tr>
                                <td>{{ $items->firstItem() + $i }}</td>

                                @foreach ($listColumns as $col)
                                    <td>
                                        @if ($col === 'is_active')
                                            {!! $item->$col
                                                ? '<span class="badge bg-success">فعال</span>'
                                                : '<span class="badge bg-secondary">غیرفعال</span>' !!}
                                        @else
                                            {{ $item->$col ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach

                                <td class="text-nowrap">
                                    <a href="{{ route($routeName . '.edit', $item->id) }}"
                                        class="btn btn-sm btn-primary">ویرایش</a>

                                    <form action="{{ route($routeName . '.destroy', $item->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('حذف شود؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="99">دیتایی وجود ندارد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
