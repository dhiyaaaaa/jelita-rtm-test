<?php

namespace App\Http\Controllers\auditor\rtl;

use App\Http\Controllers\Controller;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\Unit;
use App\Models\Auditor;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class TindakLanjutController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index(): View
    {
        $jadwal = Auditor::with('jadwal_audit')->where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->get();
        $data = [
            'title' => 'Monitoring Tindak Lanjut PTK',
            'jadwal' => $jadwal,
        ];

        return view('auditor.tindak_lanjut.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {   
        //Alert
        $title = 'Hapus!';
        $text = "Apakah Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id])->first();

        $prodi = Prodi::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'jenjang',
            'user',
            'monitoring' => function ($query) use ($jadwalAudit){
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'monitoring.auditor' => function ($query) use ($auditor){
                $query->where('auditor_id', $auditor->id);
            },
            'rtl.status_rtl_auditee',
            'monitoring.status_monitoring'
        ])->get();

        $fakultas = Fakultas::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'monitoring' => function ($query) use ($jadwalAudit){
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'monitoring.auditor' => function ($query) use ($auditor){
                $query->where('auditor_id', $auditor->id);
            },
            'rtl.status_rtl_auditee',
            'monitoring.status_monitoring'
        ])->get();

        $unit = Unit::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'monitoring' => function ($query) use ($jadwalAudit){
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'monitoring.auditor' => function ($query) use ($auditor){
                $query->where('auditor_id', $auditor->id);
            },
            'rtl.status_rtl_auditee',
            'monitoring.status_monitoring'
        ])->get();

        $auditee = $prodi->merge($fakultas)->merge($unit);

        $data = [
            'title' => 'Monitoring Tindak Lanjut',
            'auditee' => $auditee,
            'jadwal' => $jadwalAudit,
            'auditor' => $auditor,
            'expired' => $jadwalAudit->expired,
        ];

        return view('auditor.tindak_lanjut.show', $data);
    }
}
