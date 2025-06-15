<?php

namespace App\Http\Controllers\admin\auditPtk;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditeeAuditorPtkStoreUpdateRequest;
use App\Models\Auditee;
use App\Models\AuditeeAuditorPtk;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AuditeeAuditorPtkController extends Controller
{
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Auditor',
            'jadwal' => $jadwal
        ];

        return view('admin.ptk.auditee_auditor.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {
        // Sweet Alert
        $title = 'Hapus Auditor!';
        $text = "Apakah Anda yakin ingin menghapus auditor ini?";
        confirmDelete($title, $text);

        $fakultas = Fakultas::with(['auditee' => function($query) use ($jadwalAudit) {
            $query->whereHas('jadwal_audit', function ($subQuery) use($jadwalAudit) {
                $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
            })->with(['user.jabatan', 'auditorPtk.user']);
        }])->get();

        $unit = Unit::with(['auditee' => function($query) use ($jadwalAudit) {
            $query->whereHas('jadwal_audit', function ($subQuery) use($jadwalAudit) {
                $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
            })->with(['user.jabatan', 'auditorPtk.user']);
        }])->get();

        $upps = $unit->concat($fakultas)->all();

        $data = [
            'title' => 'Auditan Audit PTK ' . strtoupper($jadwalAudit->jadwal),
            'upps' => $upps,
            'jadwalAudit' => $jadwalAudit, 
        ];

        return view('admin.ptk.auditee_auditor.show', $data);
    }

    public function create_auditee_auditor_ptk(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        $auditor = Auditor::whereHas('user', function ($query) use ($unit) {
            $query->whereDoesntHave('prodi', function ($query) use ($unit) {
                $query->where('prodi_id', $unit);
            });
        })->with(['user.prodi'])->where('jadwal_audit_id', $jadwalAudit->id)->get();

        $data = [
            'title' => 'Tambah Auditor',
            'auditor' => $auditor,
            'unit' => $unit,
            'type' => $type,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.ptk.auditee_auditor.create', $data);
    }

    public function store_auditee_auditor_ptk(AuditeeAuditorPtkStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = collect([$request->auditor_1, $request->auditor_2, $request->auditor_3])->filter();
        $timestamp = now();

        if ($type === 'fakultas') {
            $fakultas = Fakultas::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditeePtk()->attach($fakultas->auditee, [
                    'fakultas_id' => $fakultas->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } elseif ($type === 'universitas') {
            $unit = Unit::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditeePtk()->attach($unit->auditee, [
                    'unit_id' => $unit->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } else {
            abort(404);
        }

        $route = ($type === 'fakultas' || $type === 'universitas' ? 'upps' : '');

        return redirect()->route('auditee_auditor_ptk.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditor berhasil ditambah!');
    }

    public function edit_auditee_auditor_ptk(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        if ($type === 'fakultas') {
            $auditor = AuditeeAuditorPtk::select('auditor_id')
                ->where('fakultas_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->groupBy('auditor_id')
                ->orderBy(DB::raw('MAX(updated_at)'), 'asc')
                ->pluck('auditor_id');
        } elseif ($type === 'universitas') {
            $auditor = AuditeeAuditorPtk::select('auditor_id')
                ->where('unit_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->groupBy('auditor_id')
                ->orderBy(DB::raw('MAX(updated_at)'), 'asc')
                ->pluck('auditor_id');
        } else {
            abort(404);
        }

        $auditors = Auditor::with('user')->where('jadwal_audit_id', $jadwalAudit->id)->distinct('id')->get();

        $data = [
            'title' => 'Edit Auditor',
            'auditors' => $auditors,
            'auditor' => $auditor,
            'unit' => $unit,
            'type' => $type,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.ptk.auditee_auditor.edit', $data);
    }

    //Edit Auditor
    public function update_auditee_auditor_ptk(AuditeeAuditorPtkStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = collect([$request->auditor_1, $request->auditor_2, $request->auditor_3])->filter();
        $timestamp = now();

        if ($type === 'fakultas') {
            $fakultas = Fakultas::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($fakultas->auditee as $auditee) {
                $auditee->auditor()->detach();
            }

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditeePtk()->attach($fakultas->auditee, [
                    'fakultas_id' => $fakultas->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } elseif ($type === 'universitas') {
            $unit = Unit::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($unit->auditee as $auditee) {
                $auditee->auditorPtk()->detach();
            }

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditeePtk()->attach($unit->auditee, [
                    'unit_id' => $unit->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } else {
            abort(404);
        }

        $route = ($type === 'fakultas' || $type === 'universitas' ? 'upps' : '');

        return redirect()->route('auditee_auditor_ptk.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditor berhasil diubah!');
    }

    public function destroy(JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        if ($type === 'fakultas') {
            $auditees = Auditee::where(['fakultas_id' => $unit, 'jadwal_audit_id' => $jadwalAudit->id])->get();
            foreach ($auditees as $auditee) {
                $auditee->auditorPtk()->detach();
                // $auditee->delete();
            }
        } else if ($type === 'universitas') {
            $auditees = Auditee::where(['unit_id' => $unit, 'jadwal_audit_id' => $jadwalAudit->id])->get();
            foreach ($auditees as $auditee) {
                $auditee->auditorPtk()->detach();
                // $auditee->delete();
            }
        } else {
            abort(404);
        }

        return back()->with('success', 'Auditor berhasil dihapus!');
    }

}
