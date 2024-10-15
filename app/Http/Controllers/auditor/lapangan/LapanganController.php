<?php

namespace App\Http\Controllers\auditor\lapangan;

use App\Http\Controllers\Controller;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LapanganController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    // Index
    public function index(): View
    {
        $jadwal = Auditor::with('jadwal_audit')->where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->get();
        $data = [
            'title' => 'Audit Lapangan',
            'jadwal' => $jadwal,
        ];

        return view('auditor.lapangan.index', $data);
    }

    // Show
    public function show(JadwalAudit $jadwalAudit): View
    {
        // Sweet Alert
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
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where(['jadwal_audit_id' => $jadwalAudit->id, 'daftar_tilik' => 1]);
            },
            'ptk' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'ptk.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'laporan' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'laporan.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'berita_acara' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'berita_acara.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'ptk.status_ptk_auditee',
            'ptk.status_ptk_auditor',
            'laporan.status_laporan'
        ])->get();

        $fakultas = Fakultas::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where(['jadwal_audit_id' => $jadwalAudit->id, 'daftar_tilik' => 1]);
            },
            'ptk' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'ptk.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'laporan' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'laporan.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'berita_acara' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'berita_acara.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'ptk.status_ptk_auditee',
            'ptk.status_ptk_auditor',
            'laporan.status_laporan'
        ])->get();

        $unit = Unit::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where(['jadwal_audit_id' => $jadwalAudit->id, 'daftar_tilik' => 1]);
            },
            'ptk' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'ptk.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'laporan' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'laporan.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'berita_acara' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'berita_acara.auditor' => function ($query) use ($auditor) {
                $query->where('auditor_id', $auditor->id);
            },
            'ptk.status_ptk_auditee',
            'ptk.status_ptk_auditor',
            'laporan.status_laporan'
        ])->get();

        $auditee = $prodi->merge($fakultas)->merge($unit);

        $data = [
            'title' => 'Audit Lapangan ' . strtoupper($jadwalAudit->jadwal),
            'auditee' => $auditee,
            'jadwal' => $jadwalAudit,
            'auditor' => $auditor,
            'expired' => $jadwalAudit->expired,
        ];

        return view('auditor.lapangan.show', $data);
    }
}
