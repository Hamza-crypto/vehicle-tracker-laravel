<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role != 'admin' && Auth::user()->role != 'manager') {
            abort(401);
        }

        return $next($request);
    }
}
