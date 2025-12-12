@extends('diagnosis.layout')

@section('title', 'انتخاب فایل‌ها')

@section('content')
    <h1>انتخاب فایل‌ها برای ادغام</h1>

    @if(session('error'))
        <p class="hint" style="color:red;">{{ session('error') }}</p>
    @endif

    <p class="hint">
        از لیست زیر فایل‌هایی را که می‌خواهی محتوایشان یکی شود انتخاب کن.
        بعد می‌توانی خروجی را برای هوش مصنوعی کپی کنی یا به صورت فایل دانلود کنی.
        <br>
        (فقط فایل‌های php / js / css و blade.php نمایش داده می‌شوند.)
    </p>

    @if(empty($files))
        <p class="hint">هیچ فایلی پیدا نشد.</p>
    @else
        <form method="post" action="{{ route('diagnosis.structure.merge') }}">
            @csrf

            <table border="1" cellpadding="6" cellspacing="0"
                   style="border-collapse: collapse; font-size: 13px; background:#fff; width:100%;">
                <tr>
                    <th style="width:30px; text-align:center;">انتخاب</th>
                    <th>مسیر فایل</th>
                    <th style="width:120px;">حجم (کیلوبایت)</th>
                </tr>
                @foreach($files as $file)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="files[]" value="{{ $file['path'] }}">
                        </td>
                        <td><code>{{ $file['path'] }}</code></td>
                        <td style="text-align:center;">
                            {{ number_format($file['size'] / 1024, 2) }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <div style="margin-top:10px;">
                <button type="submit" name="action" value="view"
                        style="padding:6px 10px; font-size:12px;">
                    ادغام و نمایش برای کپی
                </button>

                <button type="submit" name="action" value="download"
                        style="padding:6px 10px; font-size:12px; margin-right:5px;">
                    ادغام و دانلود به صورت فایل
                </button>
            </div>
        </form>
    @endif
@endsection
