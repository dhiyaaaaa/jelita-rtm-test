<?php

namespace App\Http\Controllers;

use App\Models\Auditee;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\Instrumen;
use App\Models\JadwalAudit;
use App\Models\Notifikasi;
use App\Models\Prodi;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first() : null;
    }

    private function dashboard_admin()
    {
        $jadwal = $box = null;

        $box = [
            'Program Studi' => [
                'count' => Prodi::count(),
                'route' => 'prodi',
                'color' => 'info'
            ],
            'Unit Pengelola Program Studi' => [
                'count' => Fakultas::count(),
                'route' => 'fakultas',
                'color' => 'success'
            ],
            'Pimpinan PT' => [
                'count' => Unit::count(),
                'route' => 'unit',
                'color' => 'purple'
            ],
            'Auditan' => [
                'count' => Auditee::distinct('user_id')->count(),
                'route' => 'auditee',
                'color' => 'warning'
            ],
            'Auditor' => [
                'count' => Auditor::distinct('user_id')->count(),
                'route' => 'auditor',
                'color' => 'danger'
            ],
            'Instrumen' => [
                'count' => Instrumen::count(),
                'route' => 'instrumen',
                'color' => 'secondary'
            ],
            'Hasil Audit' => [
                'count' => JadwalAudit::count(),
                'route' => 'hasil_audit',
                'color' => 'lightblue'
            ],
        ];

        return compact('box', 'jadwal');
    }

    private function dashboard_gpm()
    {
        $box = [
            'Program Studi' => [
                'count' => 0,
                'route' => 'hasil_audit_prodi',
                'color' => 'info'
            ],
            'Hasil Audit' => [
                'count' => 0,
                'route' => 'hasil_audit_prodi',
                'color' => 'warning'
            ],
            'Auditor' => [
                'count' => 0,
                'route' => 'gpm.auditor',
                'color' => 'danger'
            ],
        ];

        if (!$this->jabatanUser) return $box;

        if ($this->user->prodi->isNotEmpty() && $this->user->prodi->first()) {
            $fakultas = Fakultas::where('id', $this->user->prodi->first()->fakultas_id)->first();
            $allProdi = Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray();

            $box['Program Studi']['count'] = Prodi::where('fakultas_id', $fakultas->id)->count();
            $box['Hasil Audit']['count'] = JadwalAudit::count();
            $box['Auditor']['count'] = AuditeeAuditor::whereIn('prodi_id', $allProdi)->distinct('auditor_id')->count();
        }

        return $box;
    }

    private function dashboard_auditan()
    {
        $prodi = $this->user->prodi->first();
        $fakultas = $this->user->fakultas->first();
        $unit = $this->user->unit->first();

        if (!$this->jabatanUser) return collect();

        $jadwalAuditan = JadwalAudit::whereHas('form.instrumen', function ($query) {
            $query->where(function ($query) {
                $query->orWhereHas('jenjang', function ($query) {
                    if ($this->user->prodi->first()) {
                        $query->where('prodi_id', $this->user->prodi->first()->id)
                            ->orWhere('jenjang_id', $this->user->prodi->first()->jenjang->id);
                    } elseif ($this->user->unit->first()) {
                        $query->where('unit_id', $this->user->unit->first()->id);
                    }
                });
            })->orWhereHas('jabatan', function ($query) {
                $query->where('jabatan_id', $this->jabatanUser->id);
            });
        })->with(['auditee_auditor' => function ($q) use ($prodi, $fakultas, $unit) {
            if ($prodi) {
                $q->where('prodi_id', $prodi->id)->with(['auditor.user'])->distinct('auditor_id');
            } elseif ($fakultas) {
                $q->where('fakultas_id', $fakultas->id)->with(['auditor.user'])->distinct('auditor_id');
            } elseif ($unit) {
                $q->where('unit_id', $unit->id)->with(['auditor.user'])->distinct('auditor_id');
            }
        }])
            ->orderBy('created_at', 'DESC')
            ->get();

        return $jadwalAuditan;
    }

    private function dashboard_auditor()
    {
        $auditors = Auditor::where('user_id', $this->user->id)->get();
        $auditorid = $auditors->pluck('id')->toArray();

        $jadwalAuditor = JadwalAudit::with(['auditee_auditor', 'auditee_auditor.auditor.user', 'auditee_auditor.prodi', 'auditee_auditor.fakultas', 'auditee_auditor.unit'])->whereHas('auditor', function ($query) use ($auditorid) {
            $query->whereIn('id', $auditorid);
        })->with(['auditor.user'])->orderBy('created_at', 'DESC')->get()->map(function ($item) use ($auditorid) {
            $units = $item->auditee_auditor->whereIn('auditor_id', $auditorid)
                ->map(function ($auditee_auditor) {
                    return $auditee_auditor->prodi_id
                        ? $auditee_auditor->prodi
                        : ($auditee_auditor->fakultas_id
                            ? $auditee_auditor->fakultas
                            : $auditee_auditor->unit);
                })
                ->filter()
                ->unique();

            $unitData = $units->map(function ($unit) use ($item) {
                $unitType = $unit->type === 'prodi'
                    ? 'Prodi'
                    : ($unit->type === 'fakultas'
                        ? 'Fakultas'
                        : 'Unit');

                $relatedAuditees = $item->auditee_auditor->where(
                    $unit->type === 'prodi'
                        ? 'prodi_id'
                        : ($unit->type === 'fakultas'
                            ? 'fakultas_id'
                            : 'unit_id'),
                    $unit->id
                )->unique('auditor_id');

                return [
                    'unitType' => $unitType,
                    'unitName' => $unit->nama,
                    'auditees' => $relatedAuditees->map(function ($aud) {
                        return $aud->auditor->user;
                    })
                ];
            });

            return [
                'id' => $item->id,
                'jadwal' => $item->jadwal,
                'tgl_mulai' => $item->tgl_mulai,
                'tgl_selesai' => $item->tgl_selesai,
                'fitur_auditor' => $item->fitur_auditor,
                'expired' => $item->expired,
                'units' => $unitData
            ];
        });

        return $jadwalAuditor;
    }

    public function index(): View
    {
        $rolesAuditee = ['pj_prodi', 'pj_fakultas', 'pj_universitas', 'gkm'];

        $jadwalAuditor = $jadwalAuditan = $jadwal = $box = collect();
        $auditorid = null;

        $hasGpmRole = Auth::user()->roles->contains('name', 'gpm');
        $totalRoles = Auth::user()->roles->count();

        if ($hasGpmRole && $totalRoles === 1) {
            $box = $this->dashboard_gpm() ?? collect();
        } elseif ($this->jabatanUser && $this->jabatanUser->slug === 'rektor') {
            $jadwal = JadwalAudit::orderBy('created_at', 'desc')->get();
        } elseif ($this->user->roles->pluck('name')->contains('auditor') && $this->user->roles->pluck('name')->intersect($rolesAuditee)->isNotEmpty()) {
            $jadwalAuditan = $this->dashboard_auditan();
            $jadwalAuditor = $this->dashboard_auditor();
        } else if ($this->user->roles->pluck('name')->contains('pusjamu')) {
            $adminData = $this->dashboard_admin();
            $box = $adminData['box'];
            $jadwal = $adminData['jadwal'];
        } else if ($this->user->roles->pluck('name')->contains('auditor')) {
            $jadwalAuditor = $this->dashboard_auditor();
        } else if ($this->user->roles->pluck('name')->intersect($rolesAuditee)->isNotEmpty()) {
            $jadwalAuditan = $this->dashboard_auditan();
        }

        $data = [
            'title' => 'Dashboard',
            'box' => $box,
            'jadwal' => $jadwal,
            'jadwalAuditan' => $jadwalAuditan,
            'jadwalAuditor' => $jadwalAuditor,
            'user' => $this->user,
            'auditorid' => $auditorid,
        ];

        return view('dashboard.index', $data);
    }

    public function notifikasi(): View
    {
        $user = Auth::user();
        $rolesAuditee = ['pj_prodi', 'pj_fakultas', 'pj_universitas', 'gkm', 'gpm'];

        $auditors = Auditor::where('user_id', $user->id)->pluck('id')->toArray();
        $auditees = Auditee::where('user_id', $user->id)->get();
        $auditeesUuid = $auditees->pluck('id')->toArray();

        $auditeeAuditor = AuditeeAuditor::whereIn('auditor_id', $auditors)
            ->orWhereIn('auditee_id', $auditeesUuid)
            ->distinct()
            ->get(['jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id']);

        $jadwalAuditUuids = $auditeeAuditor->pluck('jadwal_audit_id');
        $prodiUuids = $auditeeAuditor->pluck('prodi_id');
        $fakultasUuids = $auditeeAuditor->pluck('fakultas_id');
        $unitUuids = $auditeeAuditor->pluck('unit_id');

        $notifikasi = Notifikasi::whereIn('jadwal_audit_id', $jadwalAuditUuids)
            ->where(function ($query) use ($prodiUuids, $fakultasUuids, $unitUuids) {
                $query->whereIn('prodi_id', $prodiUuids)
                    ->orWhereIn('fakultas_id', $fakultasUuids)
                    ->orWhereIn('unit_id', $unitUuids);
            })
            ->with(['auditor', 'auditor.user', 'auditee.user', 'form.instrumen', 'jadwal_audit', 'prodi', 'fakultas', 'unit'])
            ->get();

        $notifikasiAuditor = $notifikasi->filter(function ($item) use ($user) {
            return $user->roles->pluck('name')->contains('auditor') &&
                in_array($item->status, ['diterima', 'selesai']);
        })->sortByDesc('updated_at')
            ->groupBy(fn($item) => Carbon::parse($item->updated_at)->translatedFormat('j F Y'));

        $notifikasiAuditee = $notifikasi->filter(function ($item) use ($user, $rolesAuditee) {
            return $user->roles->pluck('name')->intersect($rolesAuditee)->isNotEmpty() &&
                in_array($item->status, ['terkirim', 'diterima', 'selesai']);
        })->sortByDesc('created_at')
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->translatedFormat('j F Y'));

        return view('dashboard.notifikasi', [
            'title' => 'Notifikasi',
            'notifikasiAuditor' => $notifikasiAuditor,
            'notifikasiAuditee' => $notifikasiAuditee,
            'auditees' => $auditees,
            'auditors' => $auditors,
        ]);
    }
}
