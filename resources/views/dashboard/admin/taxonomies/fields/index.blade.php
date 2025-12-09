{{-- resources/views/dashboard/admin/taxonomy/fields/index.blade.php --}}
@extends('layouts.app')

@section('title', 'مدیریت ' . ($title ?? 'زمینه‌ها'))

@php
    function sortLink($key, $label, $currentSort, $currentDir) {
        $dir = ($currentSort === $key && $currentDir === 'asc') ? 'desc' : 'asc';

        $arrow = '';
        if ($currentSort === $key) {
            $arrow = $currentDir === 'asc' ? '▲' : '▼';
        }

        $url = request()->fullUrlWithQuery(['sort' => $key, 'dir' => $dir]);

        return '<a href="'.$url.'" class="text-decoration-none text-dark fw-bold">'.$label.' '.$arrow.'</a>';
    }

    $currentSort = $sort ?? request('sort', 'sort_order');
    $currentDir  = $dir ?? request('dir', 'asc');
@endphp

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">مدیریت {{ $title ?? 'زمینه‌ها' }}</h3>

        <a href="{{ route($routeName . '.create') }}" class="btn btn-success">
            + ایجاد جدید
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success small">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">#</th>

                        <th class="text-nowrap">
                            {!! sortLink('name_fa', 'نام زمینه', $currentSort, $currentDir) !!}
                        </th>

                        <th class="text-nowrap">
                            {!! sortLink('slug', 'اسلاگ', $currentSort, $currentDir) !!}
                        </th>

                        <th class="text-nowrap">
                            {!! sortLink('sort_order', 'ترتیب نمایش', $currentSort, $currentDir) !!}
                        </th>

                        <th class="text-nowrap">نام شاخه</th>

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

                            <td>{{ $item->name_fa }}</td>
                            <td dir="ltr">{{ $item->slug }}</td>
                            <td>{{ $item->sort_order ?? 0 }}</td>

                            <td>{{ $item->branch?->name_fa ?? '---' }}</td>

                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </td>

                            <td class="text-nowrap">
                                <a href="{{ route($routeName.'.edit', $item->id) }}"
                                   class="btn btn-sm btn-primary">ویرایش</a>

                                <form action="{{ route($routeName.'.destroy', $item->id) }}"
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
                            <td colspan="7" class="text-muted py-4">دیتایی وجود ندارد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
