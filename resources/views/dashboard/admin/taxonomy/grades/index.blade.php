{{-- resources/views/dashboard/admin/taxonomy/grades/index.blade.php --}}
@extends('layouts.app')

@section('title', 'مدیریت ' . ($title ?? 'پایه‌ها'))

@php
    // ساخت لینک سورت برای هر ستون
    function sortLink($key, $label, $currentSort, $currentDir) {
        $dir = ($currentSort === $key && $currentDir === 'asc') ? 'desc' : 'asc';

        $arrow = '';
        if ($currentSort === $key) {
            $arrow = $currentDir === 'asc' ? '▲' : '▼';
        }

        $url = request()->fullUrlWithQuery(['sort' => $key, 'dir' => $dir]);

        return '<a href="'.$url.'" class="text-decoration-none text-dark fw-bold">'.$label.' '.$arrow.'</a>';
    }

    $currentSort = $sort ?? '';
    $currentDir  = $dir ?? '';
@endphp

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">مدیریت {{ $title ?? 'پایه‌ها' }}</h3>

        <a href="{{ route($routeName . '.create') }}" class="btn btn-success">
            + ایجاد جدید
        </a>
    </div>

    {{-- Success --}}
    @if (session('success'))
        <div class="alert alert-success small">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">#</th>

                        {{-- نام پایه --}}
                        <th class="text-nowrap">
                            {!! sortLink('name_fa', 'نام پایه', $currentSort, $currentDir) !!}
                        </th>

                        {{-- اسلاگ --}}
                        <th class="text-nowrap">
                            {!! sortLink('slug', 'اسلاگ', $currentSort, $currentDir) !!}
                        </th>

                        {{-- مقدار پایه --}}
                        <th class="text-nowrap">
                            {!! sortLink('value', 'مقدار پایه', $currentSort, $currentDir) !!}
                        </th>

                        {{-- ترتیب نمایش --}}
                        <th class="text-nowrap">
                            {!! sortLink('sort_order', 'ترتیب نمایش', $currentSort, $currentDir) !!}
                        </th>

                        {{-- نام مقطع (فعلاً بدون سورت) --}}
                        <th class="text-nowrap">نام مقطع</th>

                        {{-- وضعیت --}}
                        <th class="text-nowrap">
                            {!! sortLink('is_active', 'وضعیت', $currentSort, $currentDir) !!}
                        </th>

                        <th style="width:150px">عملیات</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>

                            {{-- نام پایه --}}
                            <td>{{ $item->name_fa }}</td>

                            {{-- اسلاگ --}}
                            <td dir="ltr">{{ $item->slug }}</td>

                            {{-- مقدار پایه --}}
                            <td>{{ $item->value }}</td>

                            {{-- ترتیب نمایش --}}
                            <td>{{ $item->sort_order ?? 0 }}</td>

                            {{-- نام مقطع --}}
                            <td>{{ $item->section?->name_fa ?? '---' }}</td>

                            {{-- وضعیت --}}
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </td>

                            {{-- عملیات --}}
                            <td class="text-nowrap">
                                <a href="{{ route($routeName . '.edit', $item->id) }}"
                                   class="btn btn-sm btn-primary">ویرایش</a>

                                <form action="{{ route($routeName . '.destroy', $item->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('حذف شود؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">دیتایی وجود ندارد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        {{-- Pagination --}}
        <div class="card-footer bg-white">
            {{ $items->links() }}
        </div>
    </div>

</div>
@endsection
