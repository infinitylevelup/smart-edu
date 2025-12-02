<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * استفاده:
     * ->middleware('role:student')
     * ->middleware('role:teacher')
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        // اگر لاگین نیست
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // اگر نقش کاربر با نقش موردنیاز یکی نیست
        if (($user->role ?? null) !== $role) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
