<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;  // ✅ مدل درست
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        $classrooms = $student->classrooms()
            ->with('teacher')
            ->withCount(['students','exams'])
            ->latest()
            ->get();

        return view('dashboard.student.classrooms.index', compact('classrooms'));
    }

    public function show(ClassRoom $classroom)
    {
        $student = Auth::user();

        $isMember = $student->classrooms()
            ->where('classroom_id', $classroom->id)
            ->exists();

        abort_unless($isMember, 403, 'شما عضو این کلاس نیستید.');

        $classroom->load([
            'teacher',
            'exams' => fn($q) => $q->latest(),
        ]);

        return view('dashboard.student.classrooms.show', compact('classroom'));
    }

    public function showJoinForm()
    {
        return view('dashboard.student.classrooms.join-class');
    }

    public function join(Request $request)
    {
        $request->validate([
            'join_code' => ['required', 'string', 'exists:classrooms,join_code'],
        ]);

        $student = Auth::user();

        // ✅ اینجا باید ClassRoom باشد نه Classroom
        $classroom = ClassRoom::where('join_code', $request->join_code)->firstOrFail();

        if ($student->classrooms()->where('classroom_id', $classroom->id)->exists()) {
            return back()->withErrors([
                'join_code' => 'شما قبلاً عضو این کلاس هستید.'
            ]);
        }

        $student->classrooms()->attach($classroom->id);

        return redirect()
            ->route('student.classrooms.index')
            ->with('success', 'با موفقیت به کلاس اضافه شدید.');
    }
}
