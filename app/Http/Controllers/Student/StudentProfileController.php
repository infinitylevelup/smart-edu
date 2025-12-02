<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('dashboard.student.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['nullable','email','max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable','string','max:30', Rule::unique('users')->ignore($user->id)],
            // اگر فیلدهای دیگه داری اینجا اضافه کن:
            'national_code' => ['nullable','string','max:20'],
            'bio' => ['nullable','string','max:1000'],
        ]);

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'اطلاعات پروفایل با موفقیت ذخیره شد ✅');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        // حذف عکس قبلی (اگر وجود دارد)
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->avatar = $path;
        $user->save();

        return back()->with('success', 'عکس پروفایل تغییر کرد 📸');
    }
}
