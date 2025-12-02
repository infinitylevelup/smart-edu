<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MyTeachersController extends Controller
{
    public function index()
    {
        $student = Auth::user();

        /**
         * سناریوی رایج:
         * - student به classrooms وصله (pivot classroom_student)
         * - classroom به teacher (یا teachers) وصله
         *
         * ما هر دو حالت رو ساپورت می‌کنیم.
         */

        $teachers = collect();

        // حالت 1: classroom -> teacher_id (یک معلم برای هر کلاس)
        if (method_exists($student, 'classrooms')) {
            $classrooms = $student->classrooms()->with('teacher')->get();

            $teachers = $classrooms
                ->pluck('teacher')
                ->filter()
                ->unique('id')
                ->values();
        }

        // حالت 2: classroom -> teachers()  (چند معلم برای یک کلاس)
        if ($teachers->isEmpty() && method_exists($student, 'classrooms')) {
            $classrooms = $student->classrooms()->with('teachers')->get();

            $teachers = $classrooms
                ->flatMap(fn($c) => $c->teachers ?? [])
                ->filter()
                ->unique('id')
                ->values();
        }

        // حالت 3 (Fallback): اگر رابطه کلاس نداری و مستقیم teacher_student داری
        if ($teachers->isEmpty() && method_exists($student, 'teachers')) {
            $teachers = $student->teachers()->get();
        }

        return view('dashboard.student.my-teachers', compact('teachers'));
    }
}
