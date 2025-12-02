<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * EnsureRoleSelected
 *
 * اگر کاربر لاگین است ولی role ندارد،
 * باید به صفحه انتخاب نقش هدایت شود.
 */
class EnsureRoleSelected
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && is_null($user->role)) {
            return redirect()->route('role.select');
        }

        return $next($request);
    }
}
