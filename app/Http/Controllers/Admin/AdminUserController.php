<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // فیلتر نقش
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // سرچ (نام یا شماره)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('dashboard.admin.users.index', [
            'users' => $users,
            'role'  => $request->role ?? 'all',
            'q'     => $request->q ?? '',
        ]);
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('dashboard.admin.users.show', compact('user'));
    }

    /**
     * آپدیت سریع (فعال/غیرفعال یا تغییر نقش)
     * در فرم update، فقط فیلدهایی که بفرستی تغییر می‌کنن
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'is_active' => 'nullable|boolean',
            'role'      => 'nullable|in:admin,teacher,student,counselor',
            'name'      => 'nullable|string|max:255',
        ]);

        // checkbox اگر نیاد، دست نزن
        if (!$request->has('is_active')) {
            unset($data['is_active']);
        }

        $user->update($data);

        return back()->with('success', 'کاربر بروزرسانی شد.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // جلوگیری از حذف خودِ ادمین
        if (auth()->id() === $user->id) {
            return back()->with('error', 'نمی‌توانید حساب خودتان را حذف کنید.');
        }

        $user->delete();

        return back()->with('success', 'کاربر حذف شد.');
    }
}
