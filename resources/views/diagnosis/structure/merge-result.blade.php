@extends('diagnosis.layout')

@section('title', 'نتیجه ادغام')

@section('content')
    <h1>نتیجه ادغام فایل‌ها</h1>

    <p class="hint">
        این متن ترکیب‌شدهٔ فایل‌هایی است که انتخاب کردی.
        می‌توانی آن را کامل کپی کرده و به هوش مصنوعی بدهی.
    </p>

    @if(!empty($files))
        <p class="hint">
            فایل‌های ادغام‌شده:
        </p>
        <ul class="hint">
            @foreach($files as $path)
                <li><code>{{ $path }}</code></li>
            @endforeach
        </ul>
    @endif

    <textarea readonly style="width:100%; height:400px; font-size:12px; font-family:Consolas,monospace; direction:ltr;">
{{ $merged }}
    </textarea>
@endsection
