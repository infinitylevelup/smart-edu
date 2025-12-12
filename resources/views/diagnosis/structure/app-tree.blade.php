@extends('diagnosis.layout')

@section('title', 'App Folder Tree')

@section('content')
    <h1>درخت پوشه‌ها و فایل‌های فولدر <code>app/</code></h1>

    <p class="hint">
        در این نما، پوشه‌ها و فایل‌های کدی (php/js/css) داخل فولدر <code>app/</code> نمایش داده می‌شوند.
        روی فایل‌ها کلیک کن تا در File Viewer باز شوند.
    </p>

    @if(empty($tree))
        <p class="hint">
            فولدر <code>app/</code> پیدا نشد یا خالی است.
        </p>
    @else
        @include('diagnosis.structure._tree', ['nodes' => $tree])
    @endif
@endsection
