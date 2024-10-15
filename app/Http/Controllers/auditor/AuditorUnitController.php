<?php

namespace App\Http\Controllers\auditor;

use App\Http\Controllers\Controller;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuditorUnitController extends Controller
{
    public function show(JadwalAudit $jadwalAudit, string $type): View|RedirectResponse
    {
        $auditor = Auditor::where(['jadwal_audit_id' => $jadwalAudit->id, 'user_id' => Auth::user()->id])->first();

        if (!$jadwalAudit->fitur_auditor) abort(403);

        if ($type !== 'prodi' && $type !== 'upps') abort(404);

        if ($type === 'prodi') {
            $units = Prodi::with(['auditor' => function ($query) use ($jadwalAudit, $auditor) {
                $query->where('auditee_auditor.auditor_id', $auditor->id);
                $query->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
            }, 'fakultas', 'jenjang'])->get();
        } else if ($type === 'upps') {
            $fak = Fakultas::with(['auditor' => function ($query) use ($jadwalAudit, $auditor) {
                $query->where('auditee_auditor.auditor_id', $auditor->id);
                $query->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
            }])->get();
            $un = Unit::with(['auditor' => function ($query) use ($jadwalAudit, $auditor) {
                $query->where('auditee_auditor.auditor_id', $auditor->id);
                $query->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
            }])->get();
            foreach ($fak as $f) {
                $f->nama = 'Fakultas ' . $f->nama;
            }
            $units = $fak->merge($un);
        }

        $data = [
            'title' => 'Pilih ' . ($type === 'prodi' ? 'Program Studi' : 'Fakultas/Unit'),
            'units' => $units,
            'type' => $type,
            'jadwalAudit' => $jadwalAudit,
            'auditor' => $auditor,
        ];

        return view('auditor.show', $data);
    }

    public function pilih(Auditor $auditor, string $unit, string $type): RedirectResponse
    {
        $cek = AuditeeAuditor::where('jadwal_audit_id', $auditor->jadwal_audit_id)
            ->where(get_type($type), $unit)
            ->get()
            ->unique('auditor_id');

        if ($cek->count() === 3) return back()->with('error', 'Maksimal jumlah Auditor adalah 3');

        if ($type === 'fakultas') {
            $fakultas = Fakultas::with(['auditee' => function ($query) use ($auditor) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($auditor) {
                    $subQuery->where('jadwal_audit_id', $auditor->jadwal_audit_id);
                });
            }])->findOrFail($unit);

            $auditor->auditee()->attach($fakultas->auditee, [
                'fakultas_id' => $fakultas->id,
                'jadwal_audit_id' => $auditor->jadwal_audit_id,
            ]);
        } else if ($type === 'universitas') {
            $unit = Unit::with(['auditee' => function ($query) use ($auditor) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($auditor) {
                    $subQuery->where('jadwal_audit_id', $auditor->jadwal_audit_id);
                });
            }])->findOrFail($unit);

            $auditor->auditee()->attach($unit->auditee, [
                'unit_id' => $unit->id,
                'jadwal_audit_id' => $auditor->jadwal_audit_id,
            ]);
        } elseif ($type === 'prodi') {
            $user_prodi = Auth::user()->prodi->first()->id;

            if ($user_prodi === $unit) return back()->with('error', 'Anda tidak bisa melakukan audit pada prodi Anda sendiri.');

            $prodi = Prodi::with(['auditee' => function ($query) use ($auditor) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($auditor) {
                    $subQuery->where('jadwal_audit_id', $auditor->jadwal_audit_id);
                });
            }])->findOrFail($unit);

            $auditor->auditee()->attach($prodi->auditee, [
                'prodi_id' => $prodi->id,
                'jadwal_audit_id' => $auditor->jadwal_audit_id,
            ]);
        } else {
            abort(404);
        }

        return redirect()->route('dashboard')->with('success', 'Unit berhasil dipilih');
    }
}
