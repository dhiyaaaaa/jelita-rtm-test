<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check_auditee' => \App\Http\Middleware\CheckAuditee::class,
            'check_auditor' => \App\Http\Middleware\CheckAuditor::class,
            'check_is_admin_or_rektor' => \App\Http\Middleware\CheckIsAdminOrRektor::class,
            'check_is_gpm_or_dekan' => \App\Http\Middleware\CheckIsGpmOrDekan::class,
            'check_role_rtm' => \App\Http\Middleware\CheckRoleRtm::class,
            'check_role_rtm_univ' => \App\Http\Middleware\CheckRoleRtmUniv::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // $exceptions->renderable(function (Throwable $e) {
        //     return response()->json([
        //         'message' => 'Internal Server Error',
        //         'error' => $e->getMessage(),
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //         'trace' => $e->getTraceAsString(),
        //     ], 500);
        // });
    })->create();
