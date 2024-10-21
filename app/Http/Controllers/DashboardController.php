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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $rolesAuditee = ['pj_prodi', 'pj_fakultas', 'pj_universitas', 'gkm', 'gpm'];

        $jadwal = null;
        $box = null;
        $auditorid = null;

        if ($user->roles->pluck('name')->contains('pusjamu')) {
            if ($user->jabatan->isNotEmpty() && $user->jabatan->first()->slug === 'rektor') {
                $jadwal = JadwalAudit::orderBy('created_at', 'desc')->get();
            } else {
                $box = [
                    'Program Studi' => [
                        'count' => Prodi::count(),
                        'route' => 'prodi',
                        'color' => 'info'
                    ],
                    'Unit Pengelola Program Studi' => [
                        'count' => Fakultas::count() + Unit::count(),
                        'route' => 'fakultas',
                        'color' => 'success'
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
            }
        } else if ($user->roles->pluck('name')->contains('auditor')) {
            $auditors = Auditor::where('user_id', $user->id)->get();
            $auditorid = $auditors->pluck('id')->toArray();

            $jadwal = JadwalAudit::with(['auditee_auditor', 'auditee_auditor.auditor.user', 'auditee_auditor.prodi', 'auditee_auditor.fakultas', 'auditee_auditor.unit'])->whereHas('auditor', function ($query) use ($auditorid) {
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
                    'units' => $unitData
                ];
            });
        } else if ($user->roles->pluck('name')->intersect($rolesAuditee)->isNotEmpty()) {
            if ($user->jabatan->first()->slug === 'rektor') {
                $jadwal = JadwalAudit::orderBy('created_at', 'desc')->get();
            } else {

                $auditees = Auditee::where('user_id', $user->id)->get();
                $id = $auditees->pluck('id')->toArray();
                $jadwal = JadwalAudit::with(['auditee' => function ($query) use ($user, $auditees) {
                    $query->where('user_id', $user->id)
                        ->with('auditor', function ($query) use ($auditees) {
                            foreach ($auditees as $auditee) {
                                $unit = get_type_model($auditee);
                                $query->where($unit['kolom'], $unit['value'])->with(['user']);
                            }
                        });
                }])
                    ->whereHas('auditee', function ($query) use ($id) {
                        $query->whereIn('id', $id);
                    })->orderBy('created_at', 'DESC')
                    ->get();
            }
        }

        $data = [
            'title' => 'Dashboard',
            'box' => $box,
            'jadwal' => $jadwal,
            'user' => $user,
            'auditorid' => $auditorid,
        ];

        return view('dashboard.index', $data);
    }

    public function notifikasi(): View
    {
        $user = Auth::user();

        $rolesAuditee = ['pj_prodi', 'pj_fakultas', 'pj_universitas', 'gkm', 'gpm'];
        $notifikasi = collect();

        $auditors = Auditor::where('user_id', $user->id)->pluck('id');
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
            ->when($user->roles->pluck('name')->contains('auditor'), function ($query) {
                return $query->where('status', 'diterima')->orWhere('status', 'selesai');
            })
            ->when($user->roles->pluck('name')->intersect($rolesAuditee)->isNotEmpty(), function ($query) {
                return $query->where('status', 'terkirim')->orWhere('status', 'selesai')->orWhere('status', 'diterima');
            })
            ->with(['auditor.user', 'auditee.user', 'form.instrumen', 'jadwal_audit', 'prodi', 'fakultas', 'unit'])
            ->get()
            ->sortByDesc(function ($item) use ($user) {
                return $user->roles->pluck('name')->contains('auditor') ? $item->updated_at : $item->created_at;
            })
            ->groupBy(function ($item) use ($user) {
                if ($user->roles->pluck('name')->contains('auditor')) {
                    return \Carbon\Carbon::parse($item->updated_at)->translatedFormat('j F Y');
                } else {
                    return \Carbon\Carbon::parse($item->created_at)->translatedFormat('j F Y');
                }
            });


        return view('dashboard.notifikasi', [
            'title' => 'Notifikasi',
            'notifikasi' => $notifikasi,
            'auditees' => $auditees,
            'auditors' => $auditors,
        ]);
    }
}
