<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Usage:
     * ->middleware('role:admin')
     * ->middleware('role:admin,teacher')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        /**
         * نقش جاری:
         * selected_role (نقش انتخابی برای ورود)
         * اگر خالی بود status (نقش اصلی کاربر)
         */
        $currentRole = $user->selected_role ?? $user->status;

        if (!$currentRole || !in_array($currentRole, $roles)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}
