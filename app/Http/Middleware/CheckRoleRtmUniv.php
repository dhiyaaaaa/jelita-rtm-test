<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleRtmUniv
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

            $userRole = $user->roles->pluck('name')->toArray();

            if (in_array('pusjamu', $userRole) ||
            ($user->jabatan->isNotEmpty() && in_array($user->jabatan->first()->slug, ['ketua-lp3m', 'rektor']))
            )
            {
            return $next($request);
            }
        }

        abort(403);
    }
}
