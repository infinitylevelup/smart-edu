<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CounselorProfileController extends Controller
{
    public function index()
    {
        $counselor = Auth::user();
        return view('dashboard.counselor.profile', compact('counselor'));
    }

    public function update()
    {
        // بعداً منطق آپدیت پروفایل
        return back()->with('success', 'Profile updated.');
    }
}
