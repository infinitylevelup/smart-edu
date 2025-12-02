<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectedMiddleware
{
    /**
     * اجازه ورود به داشبورد فقط وقتی role انتخاب شده باشد
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // اگر role خالی یا null بود یعنی انتخاب نشده
        if (empty($user->role)) {
            return redirect()->route('landing')
                ->withErrors(['role' => 'لطفاً ابتدا نقش خود را انتخاب کنید.']);
        }

        return $next($request);
    }
}
