<?php

namespace App\Providers;

use App\Models\Auditee;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\Menu;
use App\Models\Notifikasi;
use App\Models\Submenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layout.partials.sidebar', function ($view) {
            $user = Auth::user();
            $currentRole = $user->roles->first();

            if ($currentRole) {
                $menus = Menu::whereHas('role', function ($query) use ($currentRole) {
                    $query->where('id', $currentRole->id);
                })
                    ->where('status', 1)
                    ->get();
                $submenus = Submenu::whereHas('role', function ($query) use ($currentRole) {
                    $query->where('id', $currentRole->id);
                })
                    ->where('status', 1)
                    ->get();
            } else {
                $menus = collect([Menu::where('route', 'dashboard')->first()]);
                $submenus = collect();
            }

            $view->with('menus', $menus)->with('submenus', $submenus);
        });

        View::composer('components.layout.partials.navbar', function ($view) {
            $user = Auth::user();
            $notificationCount = 0;

            if ($user) {
                $currentRole = $user->roles->first()->name ?? null;
                $status = ($currentRole === 'auditor') ? 'diterima' : 'terkirim';

                if (in_array($currentRole, ['auditor', 'pj_universitas', 'pj_fakultas', 'pj_prodi'])) {
                    $idKey = $currentRole === 'auditor' ? 'auditor_id' : 'auditee_id';
                    $model = $currentRole === 'auditor' ? Auditor::class : Auditee::class;

                    $id = $model::where('user_id', $user->id)->pluck('id');
                    $auditeeAuditor = AuditeeAuditor::whereIn($idKey, $id)->get();

                    $jadwalAudit = $auditeeAuditor->pluck('jadwal_audit_id');
                    $prodi = $auditeeAuditor->pluck('prodi_id');
                    $fakultas = $auditeeAuditor->pluck('fakultas_id');
                    $unit = $auditeeAuditor->pluck('unit_id');

                    $notificationCount = Notifikasi::whereIn('jadwal_audit_id', $jadwalAudit)
                        ->where(function ($query) use ($prodi, $fakultas, $unit) {
                            $query->whereIn('prodi_id', $prodi)
                                ->orWhereIn('fakultas_id', $fakultas)
                                ->orWhereIn('unit_id', $unit);
                        })
                        ->where('status', $status)
                        ->count();
                }
            }

            $view->with('notificationCount', $notificationCount);
        });
    }
}
