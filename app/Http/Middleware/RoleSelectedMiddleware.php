<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // ✅ در دیتابیس شما نقش انتخابی در selected_role است
        if (empty($user->selected_role)) {
            return redirect()->route('landing')
                ->withErrors(['role' => 'لطفاً ابتدا نقش خود را انتخاب کنید.']);
        }

        return $next($request);
    }
}
