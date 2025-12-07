<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * ⚠️ چون هنوز اسم مدل آزمونت قطعی نیست،
     * این کنترلر رو طوری می‌نویسیم که اگر مدل Exam وجود نداشت،
     * خطا نده و صفحه خالی بالا بیاد.
     */

    private function examModel()
    {
        return class_exists(\App\Models\Exam::class) ? \App\Models\Exam::class : null;
    }

    public function index(Request $request)
    {
        $model = $this->examModel();

        $items = $model
            ? $model::latest()->paginate(20)
            : collect(); // اگر مدل نبود

        return view('dashboard.admin.exams.index', [
            'items' => $items,
        ]);
    }

    public function show(string $id)
    {
        $model = $this->examModel();
        abort_if(!$model, 404);

        $exam = $model::findOrFail($id);

        return view('dashboard.admin.exams.show', [
            'exam' => $exam,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $model = $this->examModel();
        abort_if(!$model, 404);

        $exam = $model::findOrFail($id);

        $data = $request->validate([
            'is_active' => 'nullable|boolean',
        ]);

        $exam->update([
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'وضعیت آزمون بروزرسانی شد.');
    }

    public function destroy(string $id)
    {
        $model = $this->examModel();
        abort_if(!$model, 404);

        $exam = $model::findOrFail($id);
        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'آزمون حذف شد.');
    }
}
