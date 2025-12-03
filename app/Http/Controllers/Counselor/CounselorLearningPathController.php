<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;

class CounselorLearningPathController extends Controller
{
    public function index()
    {
        return view('dashboard.counselor.learning-paths.index');
    }

    public function create($studentId)
    {
        return view('dashboard.counselor.learning-paths.create', compact('studentId'));
    }

    public function store($studentId)
    {
        // بعداً منطق ساخت مسیر رو می‌نویسیم
        return redirect()->route('counselor.learning-paths.index')->with('success', 'Learning path created.');
    }
}
