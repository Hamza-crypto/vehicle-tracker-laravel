<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YardManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowedRoles = [ 'yard_manager','vehicle_manager', 'admin'];
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(401);
        }
        return $next($request);
    }
}
