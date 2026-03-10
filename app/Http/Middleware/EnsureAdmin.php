<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // If not logged in as admin → redirect to login
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login'); // change to your login route name
        }

        $user = Auth::guard('admin')->user();

        // dd($user->user_type);

        // If logged in but not admin → 403
        if ($user->user_type !== "admin") {
            abort(403, 'Unauthorized: Admins only');
        }

        return $next($request);
    }
}
