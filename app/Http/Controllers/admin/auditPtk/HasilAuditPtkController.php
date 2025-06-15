<?php

namespace App\Http\Controllers\admin\auditPtk;

use App\Http\Controllers\Controller;
use App\Models\JadwalAudit;
use App\Models\MonitoringApprove;
use App\Models\RtlApprove;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class HasilAuditPtkController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function show(JadwalAudit $jadwalAudit)
    {
        $faks = DB::table('fakultas')
            ->leftJoin('rtl', function ($join) use ($jadwalAudit) {
                $join->on('fakultas.id', '=', 'rtl.fakultas_id')
                    ->where('rtl.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('status_rtl', 'rtl.id', '=', 'status_rtl.rtl_id')
            ->leftJoin('kriteria as kriteria_rtl', 'status_rtl.kriteria_id', '=', 'kriteria_rtl.id')
            ->leftJoin('monitoring', function ($join) use ($jadwalAudit) {
                $join->on('fakultas.id', '=', 'monitoring.fakultas_id')
                    ->where('monitoring.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('status_monitoring', 'monitoring.id', '=', 'status_monitoring.monitoring_id')
            ->leftJoin('kriteria as kriteria_monitoring', 'status_monitoring.kriteria_id', '=', 'kriteria_monitoring.id')
            ->leftJoin('auditee', function ($join) use ($jadwalAudit) {
                $join->on('fakultas.id', '=', 'auditee.fakultas_id')
                    ->where('auditee.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('users as auditee_users', 'auditee.user_id', '=', 'auditee_users.id')
            ->leftJoin('auditee_auditor_ptk', function ($join) use ($jadwalAudit) {
                $join->on('fakultas.id', '=', 'auditee_auditor_ptk.fakultas_id')
                    ->where('auditee_auditor_ptk.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('auditor', 'auditee_auditor_ptk.auditor_id', '=', 'auditor.id')
            ->leftJoin('users as auditor_users', 'auditor.user_id', '=', 'auditor_users.id')
            ->leftJoin('rtl_approve', 'rtl.id', '=', 'rtl_approve.rtl_id')
            ->leftJoin('monitoring_approve', 'monitoring.id', '=', 'monitoring_approve.monitoring_id')
            ->select([
                'fakultas.id',
                'fakultas.nama',
                DB::raw("CONCAT('Fakultas ', fakultas.nama) as display_name"),
                DB::raw('STRING_AGG(DISTINCT auditee_users.name, \'|\') as auditees'),
                DB::raw('STRING_AGG(DISTINCT auditor_users.name, \'|\') as auditors'),
                'rtl.id as rtl_id',
                'monitoring.id as monitoring_id',
                'status_rtl.status as status_rtl',
                'kriteria_rtl.nama as nama_kriteria_rtl',
                'status_monitoring.status as status_monitoring',
                'kriteria_monitoring.nama as nama_kriteria_monitoring',
                'rtl_approve.approve as approval_rtl',
                'monitoring_approve.approve as approval_monitoring',
            ])
            ->groupBy(
                'fakultas.id',
                'fakultas.nama',
                'rtl.id',
                'monitoring.id',
                'status_rtl.status',
                'kriteria_rtl.nama',
                'status_monitoring.status',
                'kriteria_monitoring.nama',
                'rtl_approve.approve',
                'monitoring_approve.approve',
            )
            ->get();

        $units = DB::table('unit')
            ->leftJoin('rtl', function ($join) use ($jadwalAudit) {
                $join->on('unit.id', '=', 'rtl.unit_id')
                    ->where('rtl.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('status_rtl', 'rtl.id', '=', 'status_rtl.rtl_id')
            ->leftJoin('kriteria as kriteria_rtl', 'status_rtl.kriteria_id', '=', 'kriteria_rtl.id')
            ->leftJoin('monitoring', function ($join) use ($jadwalAudit) {
                $join->on('unit.id', '=', 'monitoring.unit_id')
                    ->where('monitoring.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('status_monitoring', 'monitoring.id', '=', 'status_monitoring.monitoring_id')
            ->leftJoin('kriteria as kriteria_monitoring', 'status_monitoring.kriteria_id', '=', 'kriteria_monitoring.id')
            ->leftJoin('auditee', function ($join) use ($jadwalAudit) {
                $join->on('unit.id', '=', 'auditee.unit_id')
                    ->where('auditee.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('users as auditee_users', 'auditee.user_id', '=', 'auditee_users.id')
            ->leftJoin('auditee_auditor_ptk', function ($join) use ($jadwalAudit) {
                $join->on('unit.id', '=', 'auditee_auditor_ptk.unit_id')
                    ->where('auditee_auditor_ptk.jadwal_audit_id', $jadwalAudit->id);
            })
            ->leftJoin('auditor', 'auditee_auditor_ptk.auditor_id', '=', 'auditor.id')
            ->leftJoin('users as auditor_users', 'auditor.user_id', '=', 'auditor_users.id')
            ->leftJoin('rtl_approve', 'rtl.id', '=', 'rtl_approve.rtl_id')
            ->leftJoin('monitoring_approve', 'monitoring.id', '=', 'monitoring_approve.monitoring_id')
            ->select([
                'unit.id',
                'unit.nama',
                DB::raw("CONCAT('Unit ', unit.nama) as display_name"),
                DB::raw('STRING_AGG(DISTINCT auditee_users.name, \'|\') as auditees'),
                DB::raw('STRING_AGG(DISTINCT auditor_users.name, \'|\') as auditors'),
                'rtl.id as rtl_id',
                'monitoring.id as monitoring_id',
                'status_rtl.status as status_rtl',
                'kriteria_rtl.nama as nama_kriteria_rtl',
                'status_monitoring.status as status_monitoring',
                'kriteria_monitoring.nama as nama_kriteria_monitoring',
                'rtl_approve.approve as approval_rtl',
                'monitoring_approve.approve as approval_monitoring',
            ])
            ->groupBy(
                'unit.id',
                'unit.nama',
                'rtl.id',
                'monitoring.id',
                'status_rtl.status',
                'kriteria_rtl.nama',
                'status_monitoring.status',
                'kriteria_monitoring.nama', 
                'rtl_approve.approve',
                'monitoring_approve.approve',
            )
            ->get();

        $allResults = $units->concat($faks);
        
        $groupedResults = $allResults->groupBy('display_name')->map(function ($items, $name) {
            $auditees = collect();
            $auditors = collect();
            
            foreach ($items as $item) {
                if ($item->auditees) {
                    $auditees = $auditees->merge(explode('|', $item->auditees));
                }
                if ($item->auditors) {
                    $auditors = $auditors->merge(explode('|', $item->auditors));
                }
            }
            
            return (object) [
                'id' => $items->first()->id,
                'nama' => $name,
                'auditees' => $auditees->unique()->implode('|'),
                'auditors' => $auditors->unique()->implode('|'),
                'items' => $items->map(function ($item) {
                    return (object) [
                        'rtl_id' => $item->rtl_id,
                        'monitoring_id' => $item->monitoring_id,
                        'status_rtl' => $item->status_rtl,
                        'nama_kriteria_rtl' => $item->nama_kriteria_rtl,
                        'status_monitoring' => $item->status_monitoring,
                        'nama_kriteria_monitoring' => $item->nama_kriteria_monitoring
                    ];
                })
            ];
        });

        $user = $this->user->id;

        $rtls = $jadwalAudit->rtl()->with('fakultas', 'unit')->get();
        $monitorings = $jadwalAudit->monitoring()->with('fakultas', 'unit')->get();

        $ketuaLP3M = User::whereHas('jabatan', fn($q) => $q->where('slug', 'ketua-lp3m'))->first();
        $isKetuaLP3M = $this->user->jabatan->contains('slug', 'ketua-lp3m');
        $approvalRtlKetuaLP3M = RtlApprove::with(['user.jabatan'])
            ->whereHas('rtl', function($q) use ($jadwalAudit) {
                $q->where('jadwal_audit_id', $jadwalAudit->id);
            })
            ->whereHas('user.jabatan', function($q) {
                $q->where('slug', 'ketua-lp3m');
            })
            ->first();

        $approvalMonitoringKetuaLP3M = MonitoringApprove::with(['user.jabatan'])
            ->whereHas('monitoring', function($q) use ($jadwalAudit) {
                $q->where('jadwal_audit_id', $jadwalAudit->id);
            })
            ->whereHas('user.jabatan', function($q) {
                $q->where('slug', 'ketua-lp3m');
            })
            ->first();

        $data = [
            'title' => 'Hasil Tindak Lanjut ' . $jadwalAudit->jadwal,
            'groupedUpps' => $groupedResults,
            'jadwalAudit' => $jadwalAudit,
            'user' => $user,
            'rtls' => $rtls,
            'monitorings' => $monitorings,
            'approvalRtlKetuaLP3M' => $approvalRtlKetuaLP3M,
            'approvalMonitoringKetuaLP3M' => $approvalMonitoringKetuaLP3M,
            'isKetuaLP3M' => $isKetuaLP3M,
            'ketuaLP3MId' => optional($ketuaLP3M)->id,

        ];

        return view('tindak_lanjut.audit_ptk.show', $data);
    }
}