<?php

namespace App\Http\Controllers\Teacher;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherClassController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = auth()->id();

        $q = $request->q;
        $grade = $request->grade;
        $status = $request->status;
        $sort = $request->sort ?? 'latest';

        $classes = Classroom::query()
            ->where('teacher_id', $teacherId)
            ->when($q, function($query) use ($q){
                $query->where(function($qq) use ($q){
                    $qq->where('title', 'like', "%$q%")
                       ->orWhere('subject', 'like', "%$q%")
                       ->orWhere('join_code', 'like', "%$q%");
                });
            })
            ->when($grade && $grade !== 'all', fn($query)=>$query->where('grade', $grade))
            ->when($status && $status !== 'all', function($query) use ($status){
                $query->where('is_active', $status === 'active');
            })
            ->withCount('students')
            ->withCount('exams')
            ->when($sort === 'oldest', fn($query)=>$query->oldest())
            ->when($sort === 'students', fn($query)=>$query->orderByDesc('students_count'))
            ->when($sort === 'title_asc', fn($query)=>$query->orderBy('title'))
            ->when($sort === 'title_desc', fn($query)=>$query->orderByDesc('title'))
            ->when($sort === 'latest', fn($query)=>$query->latest())
            ->paginate(9)
            ->withQueryString();
 // اگر درخواست AJAX است
    if ($request->ajax() || $request->filled('ajax')) {
        return response()->json([
            'success' => true,
            'classrooms' => $classes->map(function($class) {
                return [
                    'id' => $class->id,
                    'title' => $class->title,
                    'subject' => $class->subject,
                    'grade' => $class->grade,
                    'students_count' => $class->students_count,
                    'exams_count' => $class->exams_count,
                    'is_active' => $class->is_active
                ];
            })
        ]);
    }
// کد قبلی شما برای نمایش view...
        return view('dashboard.teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('dashboard.teacher.classes.create');
    }

public function store(Request $request)
{
    $teacherId = Auth::id();

    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'section_id'  => 'required|uuid|exists:sections,id',
        'grade_id'    => 'required|uuid|exists:grades,id',
        'branch_id'   => 'required|uuid|exists:branches,id',
        'field_id'    => 'required|uuid|exists:fields,id',
        'subfield_id' => 'required|uuid|exists:subfields,id',
        'subject_id'  => 'required|uuid|exists:subjects,id',
        'is_active'   => 'nullable|boolean',
        'metadata'    => 'nullable|string',
    ]);

    $classroom = Classroom::create([
        'id'          => (string) Str::uuid(),
        'teacher_id'  => $teacherId,
        'title'       => $data['title'],
        'section_id'  => $data['section_id'],
        'grade_id'    => $data['grade_id'],
        'branch_id'   => $data['branch_id'],
        'field_id'    => $data['field_id'],
        'subfield_id' => $data['subfield_id'],
        'subject_id'  => $data['subject_id'],
        'is_active'   => (bool)($data['is_active'] ?? true),
        'metadata'    => $data['metadata'] ?? null,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'classroom' => [
                'id' => $classroom->id,
                'title' => $classroom->title,
            ]
        ]);
    }

    return redirect()
        ->route('teacher.classes.index')
        ->with('success', 'کلاس با موفقیت ایجاد شد.');
}

    // 🔥 تابع تولید کد عضویت
    private function generateJoinCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (Classroom::where('join_code', $code)->exists());

        return $code;
    }

    public function show(Classroom $class)
    {
        $this->authorizeTeacher($class);

        $class->load('students');

        return view('dashboard.teacher.classes.show', compact('class'));
    }

    public function edit(Classroom $class)
    {
        $this->authorizeTeacher($class);

        return view('dashboard.teacher.classes.edit', compact('class'));
    }

    public function update(Request $request, Classroom $class)
    {
        $this->authorizeTeacher($class);

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subject' => ['nullable','string','max:255'],
            'grade' => ['nullable','string','max:50'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $class->update($data);

        return redirect()->route('teacher.classes.index')
            ->with('success', 'کلاس آپدیت شد.');
    }
    // حذف کلاس
    public function destroy(Classroom $class)
    {
        $this->authorizeTeacher($class);

        $class->delete();

        return back()->with('success', 'کلاس حذف شد.');
    }
    // نمایش دانش‌آموزان کلاس
    public function students(Classroom $class)
    {
        $this->authorizeTeacher($class);
        $class->load('students');

        return view('dashboard.teacher.classes.students', compact('class'));
    }
    // بررسی اینکه معلم مالک کلاس است
    public function addStudent(Request $request, Classroom $class)
    {
        $this->authorizeTeacher($class);

        $request->validate([
            'student' => ['required','string']
        ]);

        $key = $request->student;

        $student = User::query()
            ->where('email', $key)
            ->orWhere('username', $key)
            ->orWhere('phone', $key)
            ->first();

        if(!$student){
            return back()->with('error', 'دانش‌آموز پیدا نشد.');
        }

        $class->students()->syncWithoutDetaching([$student->id]);

        return back()->with('success', 'دانش‌آموز به کلاس اضافه شد.');
    }
    // حذف دانش‌آموز از کلاس
    public function removeStudent(Classroom $class, User $student)
    {
        $this->authorizeTeacher($class);

        $class->students()->detach($student->id);

        return back()->with('success', 'دانش‌آموز از کلاس حذف شد.');
    }
    // بررسی اینکه معلم مالک کلاس است
    private function authorizeTeacher(Classroom $class)
    {
        abort_unless($class->teacher_id === auth()->id(), 403);
    }
}
