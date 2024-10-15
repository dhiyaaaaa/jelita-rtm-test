<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuditee
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

            if ($user->roles->first()->name === 'pusjamu' || $user->auditee && in_array($user->roles->first()->name, ['pj_prodi', 'pj_fakultas', 'pj_universitas', 'gkm', 'gpm'])) {
                return $next($request);
            }
        }

        abort(403);
    }
}
