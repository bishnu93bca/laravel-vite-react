<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $is_logged_in = Auth::check();

        // Return JSON if it's an AJAX request
        if (!$is_logged_in && $request->isXmlHttpRequest())
        {
            return response()->json(null, 401); // 401 Unauthorized
        }
        else if (!$is_logged_in)
        {
            return redirect('/login');
        }

        return $next($request);
    }
}
