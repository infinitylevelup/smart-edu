<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * مدیریت درس‌ها (Subjects) توسط معلم
 */
class SubjectController extends Controller
{
    /**
     * لیست درس‌ها
     * view: dashboard.teacher.subjects.index
     */
    public function index()
    {
        return view('dashboard.teacher.subjects.index');
    }

    /**
     * ساخت درس جدید
     * (فعلاً مینیمال؛ بعداً به مدل Subject وصل می‌کنیم)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        // TODO: ذخیره در مدل Subject
        // Subject::create([...])

        return back()->with('success', 'درس با موفقیت ثبت شد. (فعلاً آزمایشی)');
    }
}
