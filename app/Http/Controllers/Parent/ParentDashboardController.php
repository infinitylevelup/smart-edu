<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParentDashboardController extends Controller
{
    public function index(Request $request)
    {
        /**
         * مهم: طبق Mapping ما، UI عدد/زمان دقیق نمی‌گیرد.
         * پس اینجا باید داده‌ها را به متن‌های آماده تبدیل کنیم.
         *
         * فعلاً نمونه‌ی امن (placeholder) گذاشته شده تا صفحه بالا بیاید.
         * بعداً می‌توانیم این‌ها را از جدول interaction/activity واقعی تغذیه کنیم.
         */
        $overallPresence = 'فرزند شما به‌طور منظم با محیط آموزشی در ارتباط است.';
        $recentActivities = [
            'مشاهده‌ی یک درس',
            'ادامه‌ی یک فعالیت قبلی',
            'مرور محیط',
        ];
        $learningRhythm = 'پیوسته و آرام';

        return view('dashboard.parent.index', compact(
            'overallPresence',
            'recentActivities',
            'learningRhythm'
        ));
    }

    public function help()
    {
        // اگر صفحه help جدا نداری، فعلاً یک view ساده بساز یا همین را بعداً تغییر بده.
        return view('dashboard.parent.help');
    }
}
