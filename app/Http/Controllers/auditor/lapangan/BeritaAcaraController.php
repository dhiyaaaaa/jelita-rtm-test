<?php

namespace App\Http\Controllers\auditor\lapangan;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeritaAcaraAuditorStoreUpdateRequest;
use App\Models\Auditee;
use App\Models\AuditeeAuditor;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraAuditee;
use App\Models\JadwalAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaAcaraController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        $auditees = Auditee::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $data = [
            'title' => 'Tambah Berita Acara',
            'auditees' => $auditees,
            'jadwal' => $jadwalAudit,
            'unitId' => $unit,
            'type' => $type,
        ];

        return view('auditor.lapangan.beritaacara.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BeritaAcaraAuditorStoreUpdateRequest $request, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = AuditeeAuditor::select('auditor_id')
            ->where('jadwal_audit_id', $jadwalAudit)
            ->distinct()
            ->where(get_type($type), $unit)
            ->get();

        $beritaAcara = BeritaAcara::updateOrCreate([
            'jadwal_audit_id' => $jadwalAudit,
            'tgl' => $request->tgl,
            get_type($type) => $unit
        ]);

        $beritaAcara->auditee()->attach($request->auditee);

        $timestamp = now();

        foreach ($auditors as $auditor) {
            $beritaAcara->auditor()->attach($auditor->auditor_id, [
                'created_at' => $timestamp->addMilliseconds(1000)
            ]);
        }

        return redirect()->route('auditor.lapangan.show', [
            'jadwalAudit' => $jadwalAudit,
        ])
            ->with('success', 'Berita acara berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeritaAcara $beritaAcara): View
    {
        $unit = get_type_model($beritaAcara);

        $auditees = Auditee::where(['jadwal_audit_id' => $beritaAcara->jadwal_audit_id, $unit['kolom'] => $unit['value']])->get();

        $auditeeSelected = BeritaAcaraAuditee::where('berita_acara_id', $beritaAcara->id)->pluck('auditee_id');

        $data = [
            'title' => 'Edit Berita Acara',
            'auditees' => $auditees,
            'auditeeSelected' => $auditeeSelected,
            'berita_acara' => $beritaAcara,
        ];

        return view('auditor.lapangan.beritaacara.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BeritaAcaraAuditorStoreUpdateRequest $request, BeritaAcara $beritaAcara): RedirectResponse
    {
        $beritaAcara->update([
            'tgl' => $request->tgl,
        ]);

        // One to One Auditee
        $auditee = BeritaAcaraAuditee::where('berita_acara_id', $beritaAcara->id)->first();

        if ($auditee->auditee_id !== $request->auditee) {
            $auditee->update([
                'auditee_id' => $request->auditee,
                'approve' => 0,
            ]);
        }

        return redirect()->route('auditor.lapangan.show', [
            'jadwalAudit' => $beritaAcara->jadwal_audit_id,
        ])
            ->with('success', 'Berita acara berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeritaAcara $beritaAcara): RedirectResponse
    {
        $beritaAcara->delete();

        return back()->with('success', 'Berita Acara berhasil dihapus.');
    }
}
