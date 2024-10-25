<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuditor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->roles->isEmpty()) {
                abort(403);
            }

            $userRole = $user->roles->pluck('name')->toArray();

            if (in_array('pusjamu', $userRole) || (in_array('auditor', $userRole) && $user->auditor)) {
                return $next($request);
            }
        }

        abort(403);
    }
}
