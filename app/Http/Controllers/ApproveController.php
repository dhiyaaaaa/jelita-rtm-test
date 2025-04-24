<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\BeritaAcaraAuditee;
use App\Models\BeritaAcaraAuditor;
use App\Models\Laporan;
use App\Models\LaporanAuditee;
use App\Models\LaporanAuditor;
use App\Models\Ptk;
use App\Models\PtkAuditee;
use App\Models\PtkAuditor;
use App\Models\RtlAuditee;
use App\Models\MonitoringAuditee;
use App\Models\MonitoringAuditor;
use Illuminate\Http\Request;

class ApproveController extends Controller
{
    private function approve($model, $relation, $relationModel, $documentId, $userId)
    {
        $model::updateOrCreate(
            [
                $relation . '_id' => $userId,
                $relationModel . '_id' => $documentId,
            ],
            ['approve' => 1]
        );

        return back()->with('success', 'Approval berhasil');
    }

    public function approve_berita_acara_auditor(string $beritaAcara, string $auditor)
    {
        return $this->approve(BeritaAcaraAuditor::class, 'auditor', 'berita_acara', $beritaAcara, $auditor);
    }

    public function approve_berita_acara_auditee(string $beritaAcara, string $auditee)
    {
        return $this->approve(BeritaAcaraAuditee::class, 'auditee', 'berita_acara', $beritaAcara, $auditee);
    }

    public function approve_ptk_auditor(string $ptk, string $auditor)
    {
        return $this->approve(PtkAuditor::class, 'auditor', 'ptk', $ptk, $auditor);
    }

    public function approve_ptk_auditee(string $ptk, string $auditee)
    {
        return $this->approve(PtkAuditee::class, 'auditee', 'ptk', $ptk, $auditee);
    }

    public function approve_laporan_auditor(string $laporan, string $auditor)
    {
        return $this->approve(LaporanAuditor::class, 'auditor', 'laporan', $laporan, $auditor);
    }

    public function approve_laporan_auditee(string $laporan, string $auditee)
    {
        return $this->approve(LaporanAuditee::class, 'auditee', 'laporan', $laporan, $auditee);
    }

    public function approve_rtl_auditee(string $rtl, string $auditee)
    {
        return $this->approve(RtlAuditee::class, 'auditee', 'rtl', $rtl, $auditee);
    }

    public function approve_monitoring_auditee(string $monitoring, string $auditee)
    {
        return $this->approve(MonitoringAuditee::class, 'auditee', 'monitoring', $monitoring, $auditee);
    }

    public function approve_monitoring_auditor(string $monitoring, string $auditor)
    {
        return $this->approve(MonitoringAuditor::class, 'auditor', 'monitoring', $monitoring, $auditor);
    }
    

}
