<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * EnsureUserRole
 *
 * usage:
 * ->middleware('role:student')
 * ->middleware('role:teacher')
 */
class EnsureUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        // اگر نقش کاربر داخل roles نبود
        if (!in_array($user->role, $roles)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
