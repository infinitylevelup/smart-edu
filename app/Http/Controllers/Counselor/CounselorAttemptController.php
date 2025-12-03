<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\Attempt;

class CounselorAttemptController extends Controller
{
    public function index($studentId)
    {
        $attempts = Attempt::where('student_id', $studentId)
            ->with('exam')
            ->latest()
            ->paginate(10);

        return view('dashboard.counselor.attempts.index', compact('attempts', 'studentId'));
    }

    public function show(Attempt $attempt)
    {
        $attempt->load(['exam', 'answers.question']);
        return view('dashboard.counselor.attempts.show', compact('attempt'));
    }

    public function analyze(Attempt $attempt)
    {
        $attempt->load(['exam', 'answers.question']);
        return view('dashboard.counselor.attempts.analyze', compact('attempt'));
    }

    public function storeAnalysis(Attempt $attempt)
    {
        // بعداً منطق ذخیره تحلیل رو می‌نویسیم
        return redirect()->route('counselor.attempts.show', $attempt)->with('success', 'Analysis saved.');
    }
}
