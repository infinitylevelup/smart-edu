<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classroom;

use App\Models\Section;
use App\Models\Grade;
use App\Models\Branch;
use App\Models\Field;
use App\Models\Subfield;
use App\Models\SubjectType;
use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherClassController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        $q = $request->q;
        $grade = $request->grade;
        $status = $request->status;
        $sort = $request->sort ?? 'latest';

        $classes = Classroom::query()
            ->where('teacher_id', $teacherId)
            ->when($q, function($query) use ($q){
                $query->where(function($qq) use ($q){
                    $qq->where('title', 'like', "%$q%")
                       ->orWhere('join_code', 'like', "%$q%");
                });
            })
            ->when($grade && $grade !== 'all', fn($query)=>$query->where('grade_id', $grade))
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

        if ($request->ajax() || $request->filled('ajax')) {
            return response()->json([
                'success' => true,
                'classrooms' => $classes->map(function($class) {
                    return [
                        'id' => $class->id,
                        'uuid' => $class->uuid,
                        'title' => $class->title,
                        'students_count' => $class->students_count,
                        'exams_count' => $class->exams_count,
                        'is_active' => $class->is_active
                    ];
                })
            ]);
        }

        return view('dashboard.teacher.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('dashboard.teacher.classes.create');
    }

    // ذخیره کلاس جدید مطابق migration جدید
    public function store(Request $request)
    {
        $teacherId = Auth::id();

        $data = $request->validate([
            "title" => ["required","string","max:200"],
            "description" => ["nullable","string"],

            "section_id" => ["required","integer","exists:\"sections\",\"id\""],
            "grade_id"   => ["required","integer","exists:\"grades\",\"id\""],
            "branch_id"  => ["required","integer","exists:\"branches\",\"id\""],
            "field_id"   => ["required","integer","exists:\"fields\",\"id\""],

            "subfield_id"      => ["nullable","integer","exists:\"subfields\",\"id\""],
            "subject_type_id"  => ["nullable","integer","exists:\"subject_types\",\"id\""],
            "subject_id"       => ["nullable","integer","exists:\"subjects\",\"id\""],

            "classroom_type" => ["required","in:single,comprehensive"],

            "is_active" => ["nullable","boolean"],
            "metadata"  => ["nullable","array"],
        ]);

        $classroom = Classroom::create([
            "teacher_id" => $teacherId,
            "title" => $data["title"],
            "description" => $data["description"] ?? null,

            "section_id" => $data["section_id"],
            "grade_id"   => $data["grade_id"],
            "branch_id"  => $data["branch_id"],
            "field_id"   => $data["field_id"],
            "subfield_id"     => $data["subfield_id"] ?? null,
            "subject_type_id" => $data["subject_type_id"] ?? null,
            "subject_id"      => $data["subject_id"] ?? null,

            "classroom_type" => $data["classroom_type"],

            "join_code" => $this->generateJoinCode(),
            "is_active" => (bool)($data["is_active"] ?? true),
            "metadata"  => $data["metadata"] ?? null,
        ]);

        if ($request->ajax()) {
            return response()->json([
                "success" => true,
                "classroom" => [
                    "id" => $classroom->id,
                    "uuid" => $classroom->uuid,
                    "title" => $classroom->title,
                    "join_code" => $classroom->join_code,
                ],
            ]);
        }

        return redirect()
            ->route("teacher.classes.index")
            ->with("success", "کلاس با موفقیت ایجاد شد.");
    }

    private function generateJoinCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (Classroom::where("join_code", $code)->exists());

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
            "title" => ["required","string","max:200"],
            "description" => ["nullable","string"],

            "section_id" => ["required","integer","exists:\"sections\",\"id\""],
            "grade_id"   => ["required","integer","exists:\"grades\",\"id\""],
            "branch_id"  => ["required","integer","exists:\"branches\",\"id\""],
            "field_id"   => ["required","integer","exists:\"fields\",\"id\""],

            "subfield_id"      => ["nullable","integer","exists:\"subfields\",\"id\""],
            "subject_type_id"  => ["nullable","integer","exists:\"subject_types\",\"id\""],
            "subject_id"       => ["nullable","integer","exists:\"subjects\",\"id\""],

            "classroom_type" => ["required","in:single,comprehensive"],

            "is_active" => ["nullable","boolean"],
            "metadata"  => ["nullable","array"],
        ]);

        $data["is_active"] = $request->boolean("is_active", true);

        $class->update($data);

        if ($request->ajax()) {
            return response()->json(["success" => true]);
        }

        return redirect()
            ->route("teacher.classes.index")
            ->with("success", "کلاس آپدیت شد.");
    }

    public function destroy(Classroom $class)
    {
        $this->authorizeTeacher($class);
        $class->delete();

        return back()->with("success", "کلاس حذف شد.");
    }

    public function students(Classroom $class)
    {
        $this->authorizeTeacher($class);
        $class->load("students");

        return view("dashboard.teacher.classes.students", compact("class"));
    }

    public function addStudent(Request $request, Classroom $class)
    {
        $this->authorizeTeacher($class);

        $request->validate([
            "student" => ["required","string"]
        ]);

        $key = $request->student;

        $student = User::query()
            ->where("email", $key)
            ->orWhere("username", $key)
            ->orWhere("phone", $key)
            ->first();

        if(!$student){
            return back()->with("error", "دانش‌آموز پیدا نشد.");
        }

        $class->students()->syncWithoutDetaching([$student->id]);

        return back()->with("success", "دانش‌آموز به کلاس اضافه شد.");
    }

    public function removeStudent(Classroom $class, User $student)
    {
        $this->authorizeTeacher($class);
        $class->students()->detach($student->id);

        return back()->with("success", "دانش‌آموز از کلاس حذف شد.");
    }

    private function authorizeTeacher(Classroom $class)
    {
        abort_unless($class->teacher_id === Auth::id(), 403);
    }

    // ==========================================================
    // AJAX Taxonomy endpoints (FIXED for new DB)
    // ==========================================================

    public function sections()
    {
        return response()->json(
            Section::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa'])
        );
    }

    public function grades(Section $section)
    {
        return response()->json(
            Grade::where('section_id', $section->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa','value'])
        );
    }

    public function branches(Grade $grade)
    {
        // اگر branches به section وصل است و grade_id ندارد، این متد را اصلاح کن
        return response()->json(
            Branch::where('section_id', $grade->section_id)   // ✅ سازگار با ساختار جدید تو
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa'])
        );
    }

    public function fields(Branch $branch)
    {
        return response()->json(
            Field::where('branch_id', $branch->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa'])
        );
    }

    public function subfields(Field $field)
    {
        return response()->json(
            Subfield::where('field_id', $field->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa'])
        );
    }

    public function subjectTypes(Field $field)
    {
        // چون subject_types FK به field ندارد، همه فعال‌ها را بده
        return response()->json(
            SubjectType::where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','name_fa'])
        );
    }

    public function subjects(SubjectType $subjectType)
    {
        return response()->json(
            Subject::where('subject_type_id', $subjectType->id)
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->get(['id','title_fa'])
        );
    }
}
