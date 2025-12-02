<?php

namespace App\Http\Controllers\Teacher;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

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

        return view('dashboard.teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('dashboard.teacher.classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'subject' => ['nullable','string','max:255'],
            'grade' => ['nullable','string','max:50'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ]);

        $data['teacher_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);

        Classroom::create($data);

        return redirect()->route('teacher.classes.index')
            ->with('success', 'کلاس با موفقیت ساخته شد.');
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
