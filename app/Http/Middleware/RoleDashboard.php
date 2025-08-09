<?php

namespace App\Http\Middleware;

use App\Helpers\RoleHelper;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleDashboard
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect('/');
        }
        if (RoleHelper::dashboard()) {
            return $next($request);
        }
        return redirect('/');
    }
}