@extends('layouts.app')

@section('content')
<div class="container parent-dashboard" style="max-width: 420px; margin: 0 auto; padding: 16px;">

    {{-- Header --}}
    <header style="margin-bottom: 24px;">
        <div style="font-size: 17px; font-weight: 500;">
            Smart-Edu
        </div>
        <div style="font-size: 13px; color: #666; margin-top: 4px;">
            نمای کلی والد
        </div>
    </header>

    {{-- Overall Presence --}}
    <section style="background:#f6f7f8; border-radius:8px; padding:16px; margin-bottom:24px;">
        <div style="font-size:14px; font-weight:500; margin-bottom:8px;">
            وضعیت کلی
        </div>
        <div style="font-size:14px; color:#333;">
            {{ $overallPresence ?? 'در حال حاضر اطلاعاتی برای نمایش وجود ندارد.' }}
        </div>
    </section>

    {{-- Recent Activity --}}
    <section style="margin-bottom: 24px;">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 12px;">
            آخرین تعامل‌ها
        </div>

        <ul style="padding-right: 16px; margin: 0; color: #333;">
            @forelse (($recentActivities ?? []) as $activity)
                <li style="margin-bottom: 8px; font-size: 14px;">
                    {{ $activity }}
                </li>
            @empty
                <li style="font-size: 14px; color: #777;">
                    تعامل خاصی ثبت نشده است.
                </li>
            @endforelse
        </ul>
    </section>

    {{-- Learning Rhythm --}}
    <section style="background:#f6f7f8; border-radius:8px; padding:16px; margin-bottom:24px;">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 8px;">
            ریتم یادگیری
        </div>

        <div style="font-size: 14px; color: #333; margin-bottom: 6px;">
            {{ $learningRhythm ?? 'نامشخص (طبیعی است)' }}
        </div>

        <div style="font-size: 13px; color: #777;">
            نوسان در ریتم یادگیری طبیعی است.
        </div>
    </section>

    {{-- Transparency --}}
    <section style="margin-bottom: 32px;">
        <div style="font-size: 14px; font-weight: 500; margin-bottom: 12px;">
            چیزهایی که عمداً نشان داده نمی‌شوند
        </div>

        <ul style="padding-right: 16px; margin: 0; color: #555;">
            <li style="margin-bottom: 6px;">جزئیات پاسخ‌ها</li>
            <li style="margin-bottom: 6px;">مقایسه با دیگران</li>
            <li style="margin-bottom: 6px;">زمان دقیق استفاده</li>
            <li style="margin-bottom: 6px;">لحظات مکث یا خروج</li>
        </ul>
    </section>

    {{-- Footer --}}
    <footer style="border-top:1px solid #eee; padding-top:16px; display:flex; justify-content:space-between; font-size:13px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0">
                خروج
            </button>
        </form>
        <a href="{{ route('parent.help') }}">سؤالی دارید؟</a>
    </footer>

</div>
@endsection
