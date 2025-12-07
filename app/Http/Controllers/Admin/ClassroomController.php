<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $q = Classroom::query();

        // جستجوی ساده (عنوان/کد کلاس)
        if ($search = $request->get('q')) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        }

        $items = $q->latest()->paginate(20)->withQueryString();

        return view('dashboard.admin.classrooms.index', [
            'items' => $items,
        ]);
    }

    public function show(string $id)
    {
        $classroom = Classroom::findOrFail($id);

        return view('dashboard.admin.classrooms.show', [
            'classroom' => $classroom,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $classroom = Classroom::findOrFail($id);

        $data = $request->validate([
            'is_active' => 'nullable|boolean',
        ]);

        $classroom->update([
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'وضعیت کلاس بروزرسانی شد.');
    }

    public function destroy(string $id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'کلاس حذف شد.');
    }
}
