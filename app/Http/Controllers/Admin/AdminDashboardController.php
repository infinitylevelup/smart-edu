<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;

// Taxonomy models (اگر در پروژه‌ات هستند)
use App\Models\Section;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'admins'     => User::where('status', 'admin')->count(),
            'teachers'   => User::where('status', 'teacher')->count(),
            'students'   => User::where('status', 'student')->count(),
            'counselors' => User::where('status', 'counselor')->count(),
            'all'        => User::count(),

            'classrooms' => \App\Models\Classroom::count(),
            'subjects'   => \App\Models\Subject::count(),

            'sections'      => class_exists(\App\Models\Section::class) ? \App\Models\Section::count() : 0,
            'grades'        => class_exists(\App\Models\Grade::class) ? \App\Models\Grade::count() : 0,
            'branches'      => class_exists(\App\Models\Branch::class) ? \App\Models\Branch::count() : 0,
            'fields'        => class_exists(\App\Models\Field::class) ? \App\Models\Field::count() : 0,
            'subfields'     => class_exists(\App\Models\Subfield::class) ? \App\Models\Subfield::count() : 0,
            'subject_types' => class_exists(\App\Models\SubjectType::class) ? \App\Models\SubjectType::count() : 0,

            'exams' => class_exists(\App\Models\Exam::class)
                ? \App\Models\Exam::count()
                : 0,
        ];

            // ✅ آخرین کاربران
        $latestUsers = User::latest()->take(8)->get();

        // ✅ آخرین کلاس‌ها
        $latestClassrooms = class_exists(\App\Models\Classroom::class)
            ? \App\Models\Classroom::latest()->take(8)->get()
            : collect();

        // ✅ آخرین آزمون‌ها
        $latestExams = class_exists(\App\Models\Exam::class)
            ? \App\Models\Exam::latest()->take(8)->get()
            : collect();

        return view('dashboard.admin.index', compact(
            'counts',
            'latestUsers',
            'latestClassrooms',
            'latestExams'
        ));   
     }

}
