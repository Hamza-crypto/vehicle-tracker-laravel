<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $allowedRoles = ['vehicle_manager', 'admin'];
        if (! in_array(Auth::user()->role, $allowedRoles)) {
            abort(401);
        }

        return $next($request);
    }
}
