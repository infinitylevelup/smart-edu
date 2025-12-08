<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRoleSelected
{
    /**
     * اگر کاربر لاگین است ولی هنوز نقش قابل استفاده ندارد،
     * به صفحه انتخاب نقش (یا landing) هدایت شود.
     *
     * نقش جاری از سه منبع ممکن:
     * 1) selected_role در "users"
     * 2) primaryRole از pivot "role_user"
     * 3) status به عنوان fallback قدیمی
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // اگر مهمان است، اجازه عبور بده
        if (!$user) {
            return $next($request);
        }

        $currentRole = $user->selected_role
            ?? $user->primaryRole()
            ?? $user->status;

        // اگر هیچ نقشی ندارد، بفرست صفحه انتخاب نقش
        if (!$currentRole) {
            // اگر route رسمی انتخاب نقش داری
            if ($request->routeIs('role.select', 'role.set')) {
                return $next($request); // جلوگیری از لوپ
            }

            return redirect()->route('role.select');
            // اگر route انتخاب نقش نداری:
            // return redirect()->route('landing');
        }

        return $next($request);
    }
}
