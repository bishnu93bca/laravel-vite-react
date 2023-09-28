<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyAdmin
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
        // if (! Auth::user()) {
        //     return redirect('/admin/login');
        // }

        // // Any HL user accessing an admin middleware route gets redirected to admin/products
        // if (Auth::user()->isHL()) {
        //     return redirect('/admin/products');
        // }

        // if (! Auth::user()->isAdmin()) {
        //     return redirect(frontend_url());
        // }

        return $next($request);
    }
}
