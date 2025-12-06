<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleSelectedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $user->loadMissing('roles');

        if ($user->roles->isEmpty()) {
            return redirect()->route('landing')
                ->withErrors(['role' => 'لطفاً ابتدا نقش خود را انتخاب کنید.']);
        }

        return $next($request);
    }
}
