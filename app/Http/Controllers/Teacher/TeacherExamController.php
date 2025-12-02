<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TeacherExamController (Final - scope + duration safe)
 * ----------------------------------------------------------------
 * ✅ پشتیبانی از آزمون free و classroom
 * ✅ duration در DB nullable نیست:
 *    - در store اگر خالی بود، پیشفرض می‌گذاریم
 *    - در update اگر duration خالی/ارسال نشد، مقدار قبلی حفظ می‌شود
 */
class TeacherExamController extends Controller
{
    /**
     * لیست آزمون‌های معلم
     * - همه آزمون‌های خود معلم (free + classroom)
     * - فیلتر اختیاری کلاس فقط برای scope=classroom
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        $classrooms = Classroom::where('teacher_id', $teacherId)->latest()->get();

        $query = Exam::query()
            ->where('teacher_id', $teacherId)
            ->with('classroom')
            ->latest();

        if ($request->filled('classroom_id')) {
            $query->where('scope', 'classroom')
                  ->where('classroom_id', $request->classroom_id);
        }

        $exams = $query->get();

        return view('dashboard.teacher.exams.index', compact('exams', 'classrooms'));
    }

    /**
     * فرم ساخت آزمون
     * - اگر از کلاس آمده باشد classroom_id را پیشفرض می‌گیریم
     */
    public function create(Request $request)
    {
        $teacherId = Auth::id();

        $classrooms = Classroom::where('teacher_id', $teacherId)->latest()->get();
        $selectedClassroomId = $request->get('classroom_id');

        if (
            $selectedClassroomId &&
            !Classroom::where('teacher_id', $teacherId)
                ->where('id', $selectedClassroomId)
                ->exists()
        ) {
            $selectedClassroomId = null;
        }

        return view('dashboard.teacher.exams.create', compact('classrooms', 'selectedClassroomId'));
    }

    /**
     * ذخیره آزمون جدید
     */
    public function store(Request $request)
    {
        $teacherId = Auth::id();

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'scope'        => 'required|in:free,classroom',
            'classroom_id' => 'nullable|required_if:scope,classroom|exists:classrooms,id',

            // duration اختیاری ولی اگر بود باید عدد >=1 باشد
            'duration'     => 'nullable|integer|min:1',

            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',
        ]);

        // اگر کلاسی است، مالکیت کلاس چک شود
        if ($data['scope'] === 'classroom') {
            abort_unless(
                Classroom::where('teacher_id', $teacherId)
                    ->where('id', $data['classroom_id'])
                    ->exists(),
                403
            );
        }

        $exam = Exam::create([
            'title'        => $data['title'],
            'scope'        => $data['scope'],
            'classroom_id' => $data['scope'] === 'free' ? null : $data['classroom_id'],

            // چون duration nullable نیست -> اگر خالی بود پیشفرض بده
            'duration'     => $data['duration'] ?? 15,

            'description'  => $data['description'] ?? null,
            'is_active'    => $data['is_active'] ?? 1,
            'teacher_id'   => $teacherId,
        ]);

        return redirect()
            ->route('teacher.exams.show', $exam)
            ->with('success', 'آزمون با موفقیت ساخته شد.');
    }

    /**
     * نمایش آزمون
     */
    public function show(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $exam->load(['classroom', 'questions']);

        return view('dashboard.teacher.exams.show', compact('exam'));
    }

    /**
     * فرم ویرایش آزمون
     */
    public function edit(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $teacherId = Auth::id();
        $classrooms = Classroom::where('teacher_id', $teacherId)->latest()->get();

        return view('dashboard.teacher.exams.edit', compact('exam', 'classrooms'));
    }

    /**
     * بروزرسانی آزمون
     */
    public function update(Request $request, Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'scope'        => 'required|in:free,classroom',
            'classroom_id' => 'nullable|required_if:scope,classroom|exists:classrooms,id',

            'duration'     => 'nullable|integer|min:1',

            'description'  => 'nullable|string',
            'is_active'    => 'nullable|boolean',

            // فیلدهای فرم edit شما:
            'subject'      => 'nullable|string|max:255',
            'level'        => 'nullable|string|max:50',
            'start_at'     => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        // اگر کلاسی است، چک مالکیت
        if ($data['scope'] === 'classroom') {
            abort_unless(
                Classroom::where('teacher_id', Auth::id())
                    ->where('id', $data['classroom_id'])
                    ->exists(),
                403
            );
        }

        // اگر free شد، classroom_id باید null شود
        if ($data['scope'] === 'free') {
            $data['classroom_id'] = null;
        }

        /**
         * ✅ FIX دقیق:
         * فقط اگر duration در درخواست نبود یا null بود، آپدیتش نکن
         */
        if (!array_key_exists('duration', $data) || is_null($data['duration'])) {
            unset($data['duration']);
        }

        $exam->update($data);

        return redirect()
            ->route('teacher.exams.edit', $exam)
            ->with('success', 'آزمون بروزرسانی شد.');
    }

    /**
     * حذف آزمون
     */
    public function destroy(Exam $exam)
    {
        $this->authorizeTeacherExam($exam);

        $exam->delete();

        return redirect()
            ->route('teacher.exams.index')
            ->with('success', 'آزمون حذف شد.');
    }

    // ==========================================================
    // Helper: امنیت آزمون
    // ==========================================================
    protected function authorizeTeacherExam(Exam $exam)
    {
        // free: فقط سازنده خودش
        if ($exam->scope === 'free') {
            abort_unless($exam->teacher_id === Auth::id(), 403);
            return;
        }

        // classroom: باید کلاس متعلق به همین معلم باشد
        abort_unless(
            $exam->classroom && $exam->classroom->teacher_id === Auth::id(),
            403
        );
    }
}
