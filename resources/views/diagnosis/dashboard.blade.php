@extends('diagnosis.layout')

@section('title', 'داشبورد تشخیص')

@section('breadcrumb', 'داشبورد')

@section('content')
<div class="card">
    <h2 style="margin-top: 0;">📊 اطلاعات کلی پروژه</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <!-- کارت اطلاعات سیستم -->
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
            <h3 style="margin-top: 0; color: #4f46e5;">🖥️ اطلاعات سیستم</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">نسخه PHP</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        <strong>{{ $phpVersion }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">نسخه Laravel</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        <strong>{{ $laravelVersion }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">محیط اجرا</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        {{ $environment }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">حالت Debug</td>
                    <td style="padding: 8px 0; text-align: left;">
                        {{ $debugMode }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- کارت آمار پروژه -->
        <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px;">
            <h3 style="margin-top: 0; color: #4f46e5;">📁 آمار پروژه</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">تعداد فایل‌ها</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        {{ number_format($stats['totalFiles'] ?? 0) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">حجم کل</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        @php
                            $size = $stats['totalSize'] ?? 0;
                            if ($size > 0) {
                                $units = ['B', 'KB', 'MB', 'GB'];
                                $pow = floor(($size ? log($size) : 0) / log(1024));
                                $pow = min($pow, count($units) - 1);
                                $size /= pow(1024, $pow);
                                echo round($size, 2) . ' ' . $units[$pow];
                            } else {
                                echo '0 B';
                            }
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6;">پوشه ریشه</td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #f3f4f6; text-align: left;">
                        <small>{{ base_path() }}</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;">آدرس پروژه</td>
                    <td style="padding: 8px 0; text-align: left;">
                        <small>{{ url('/') }}</small>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- کارت دسترسی سریع -->
<div class="card">
    <h3>🚀 دسترسی سریع</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px;">
        <a href="{{ route('diagnosis.structure') }}" class="btn" style="background: #4f46e5; color: white; padding: 12px 20px;">
            📁 ساختار پروژه
        </a>
        <a href="{{ route('diagnosis.structure.appTree') }}" class="btn" style="background: #10b981; color: white; padding: 12px 20px;">
            🌳 درخت app/
        </a>
        <a href="{{ route('diagnosis.merge') }}" class="btn" style="background: #8b5cf6; color: white; padding: 12px 20px;">
            🧠 ادغام هوشمند
        </a>
        <a href="{{ route('diagnosis.analysis') }}" class="btn" style="background: #f59e0b; color: white; padding: 12px 20px;">
            📈 تحلیل پروژه
        </a>
    </div>
</div>

<!-- کارت MergeMaster -->
<div class="card">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h3 style="margin: 0;">🧠 ابزار ادغام فایل‌ها</h3>
            <p style="margin: 10px 0 0 0; color: #6b7280;">
                انتخاب سریع فایل‌های مشکل‌دار و ادغام برای بازبینی AI
            </p>
        </div>
        <a href="{{ route('diagnosis.merge') }}" class="btn" style="background: #8b5cf6; color: white; padding: 12px 24px;">
            شروع کنید →
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="text-align: center;">
            <div style="font-size: 40px; margin-bottom: 10px;">🔍</div>
            <h4 style="margin: 0 0 10px 0;">جستجوی زنده</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">پیدا کردن سریع فایل‌ها</p>
        </div>

        <div style="text-align: center;">
            <div style="font-size: 40px; margin-bottom: 10px;">⚡</div>
            <h4 style="margin: 0 0 10px 0;">پیش‌نمایش لحظه‌ای</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">مشاهده محتوای ادغام شده</p>
        </div>

        <div style="text-align: center;">
            <div style="font-size: 40px; margin-bottom: 10px;">📋</div>
            <h4 style="margin: 0 0 10px 0;">کپی یک‌کلیکی</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">آماده برای ارسال به AI</p>
        </div>

        <div style="text-align: center;">
            <div style="font-size: 40px; margin-bottom: 10px;">💾</div>
            <h4 style="margin: 0 0 10px 0;">دانلود فایل</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">ذخیره محتوای ادغام شده</p>
        </div>
    </div>
</div>
@endsection
