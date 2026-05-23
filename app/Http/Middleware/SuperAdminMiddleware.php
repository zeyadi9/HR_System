<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * السماح فقط لـ super_admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        return redirect()->route('Overtime')->withErrors(['error' => 'Access denied. Super Admin only.']);
    }
}
