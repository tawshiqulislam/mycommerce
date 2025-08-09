<?php

namespace App\Http\Middleware;

use App\Helpers\RoleHelper;
use Closure;
use Illuminate\Support\Facades\Auth;

class RolePos
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect('/admin');
        }
        if (RoleHelper::admin() || RoleHelper::seller()) {
            return $next($request);
        }
        return redirect('/admin');
    }
}
