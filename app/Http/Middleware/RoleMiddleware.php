<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $currentRole = $user->selected_role
            ?? $user->primaryRole()
            ?? $user->status;

        // نرمال‌سازی رشته
        $currentRole = is_string($currentRole)
            ? strtolower(trim($currentRole))
            : $currentRole;

        // مپ برای حالت‌های رایج name/فارسی/جمع
        $map = [
            'admin' => 'admin',
            'administrator' => 'admin',
            'ادمین' => 'admin',

            'teacher' => 'teacher',
            'teachers' => 'teacher',
            'معلم' => 'teacher',

            'student' => 'student',
            'students' => 'student',
            'دانش‌آموز' => 'student',

            'counselor' => 'counselor',
            'مشاور' => 'counselor',
        ];

        $currentRole = $map[$currentRole] ?? $currentRole;

        if (!$currentRole || !in_array($currentRole, $roles, true)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
