<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\Laporan;
use App\Models\Ptk;
use Illuminate\Http\Request;

class ApproveController extends Controller
{
    private function approve($model, $relation, $documentId, $userId)
    {
        $document = $model::findOrFail($documentId);
        $document->$relation()->updateExistingPivot($userId, ['approve' => 1]);

        return back()->with('success', 'Approval berhasil');
    }

    public function approve_berita_acara_auditor(string $beritaAcara, string $auditor)
    {
        return $this->approve(BeritaAcara::class, 'auditor', $beritaAcara, $auditor);
    }

    public function approve_berita_acara_auditee(string $beritaAcara, string $auditee)
    {
        return $this->approve(BeritaAcara::class, 'auditee', $beritaAcara, $auditee);
    }

    public function approve_ptk_auditor(string $ptk, string $auditor)
    {
        return $this->approve(Ptk::class, 'auditor', $ptk, $auditor);
    }

    public function approve_ptk_auditee(string $ptk, string $auditee)
    {
        return $this->approve(Ptk::class, 'auditee', $ptk, $auditee);
    }

    public function approve_laporan_auditor(string $laporan, string $auditor)
    {
        return $this->approve(Laporan::class, 'auditor', $laporan, $auditor);
    }

    public function approve_laporan_auditee(string $laporan, string $auditee)
    {
        return $this->approve(Laporan::class, 'auditee', $laporan, $auditee);
    }
}
