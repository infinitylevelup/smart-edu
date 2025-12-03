<?php

namespace App\Http\Controllers\Counselor;

use App\Http\Controllers\Controller;
use App\Models\User;

class CounselorStudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->latest()->paginate(10);
        return view('dashboard.counselor.students.index', compact('students'));
    }

    public function show(User $student)
    {
        abort_unless($student->role === 'student', 404);
        return view('dashboard.counselor.students.show', compact('student'));
    }
}
