<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCsrfForMpesa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Disable CSRF for MPESA routes
        if ($request->is('mpesa/*') || $request->is('test-mpesa-credentials')) {
            // Remove CSRF token check
            $request->session()->forget('_token');
        }

        return $next($request);
    }
}