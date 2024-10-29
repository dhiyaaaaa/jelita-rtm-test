<?php

namespace App\Http\Controllers\gpm;

use App\Http\Controllers\Controller;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuditorController extends Controller
{
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Auditor',
            'jadwalAudit' => $jadwal,
        ];

        return view('gpm_dekan.auditor.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {
        // Sweet Alert
        $title = 'Hapus Auditan dan Auditor!';
        $text = "Apakah Anda yakin ingin menghapus auditan dan auditor ini?";
        confirmDelete($title, $text);

        $prodi = collect();

        $fakultas = Auth::user()->prodi->isNotEmpty() ? Auth::user()->prodi->first()->fakultas->id : (Auth::user()->fakultas->isNotEmpty() ? Auth::user()->fakultas->first()->id : null);

        if ($fakultas) {
            $prodi = Prodi::where('fakultas_id', $fakultas)->with(['jenjang', 'auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                })->with(['user', 'auditor.user']);
            }])->get();
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Auditan ' . strtoupper($jadwalAudit->jadwal),
            'prodi' => $prodi,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('gpm_dekan.auditor.show', $data);
    }

    public function create(JadwalAudit $jadwalAudit, string $unit): View
    {
        $prodi = Prodi::where('id', $unit)->with(['jenjang'])->first();
        $auditor = Auditor::whereHas('user', function ($query) use ($unit) {
            $query->whereDoesntHave('prodi', function ($query) use ($unit) {
                $query->where('prodi_id', $unit);
            });
        })->with(['user.prodi'])->where('jadwal_audit_id', $jadwalAudit->id)->get();

        $data = [
            'title' => 'Tambah Auditor ' . $prodi->nama . ' ' . $prodi->jenjang->nama,
            'auditor' => $auditor,
            'unit' => $unit,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('gpm_dekan.auditor.create', $data);
    }

    public function store(Request $request, JadwalAudit $jadwalAudit, string $unit): RedirectResponse
    {
        $request->validate(
            [
                'auditor_1' => [
                    'required',
                    'exists:auditor,id',
                    'different:auditor_2',
                    'different:auditor_3',
                ],
                'auditor_2' => [
                    'nullable',
                    'exists:auditor,id',
                    'different:auditor_1',
                    'different:auditor_3',
                ],
                'auditor_3' => [
                    'nullable',
                    'exists:auditor,id',
                    'different:auditor_1',
                    'different:auditor_2',
                ],
            ]
        );
        $auditors = collect([$request->auditor_1, $request->auditor_2, $request->auditor_3])->filter();
        $timestamp = now();

        if ($unit) {
            $prodi = Prodi::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditee()->attach($prodi->auditee, [
                    'prodi_id' => $prodi->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } else {
            abort(404);
        }

        return redirect()->route('gpm.auditor.show', ['jadwalAudit' => $jadwalAudit->id])->with('success', 'Auditor berhasil ditambah!');
    }


    public function edit(JadwalAudit $jadwalAudit, string $unit): View
    {
        $prodi = Prodi::where('id', $unit)->with(['jenjang'])->first();

        if ($unit) {
            $auditor = AuditeeAuditor::where('prodi_id', $unit)->where('jadwal_audit_id', $jadwalAudit->id)->distinct('auditor_id')->pluck('auditor_id');
        } else {
            abort(404);
        }

        $auditors = Auditor::whereHas('user', function ($query) use ($unit) {
            $query->whereDoesntHave('prodi', function ($query) use ($unit) {
                $query->where('prodi_id', $unit);
            });
        })->with(['user.prodi'])->where('jadwal_audit_id', $jadwalAudit->id)->get();

        $data = [
            'title' => 'Edit Auditor ' . $prodi->nama . ' ' . $prodi->jenjang->nama,
            'auditors' => $auditors,
            'auditor' => $auditor,
            'unit' => $unit,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('gpm_dekan.auditor.edit', $data);
    }

    public function update(Request $request, JadwalAudit $jadwalAudit, string $unit): RedirectResponse
    {
        $request->validate(
            [
                'auditor_1' => [
                    'required',
                    'exists:auditor,id',
                    'different:auditor_2',
                    'different:auditor_3',
                ],
                'auditor_2' => [
                    'nullable',
                    'exists:auditor,id',
                    'different:auditor_1',
                    'different:auditor_3',
                ],
                'auditor_3' => [
                    'nullable',
                    'exists:auditor,id',
                    'different:auditor_1',
                    'different:auditor_2',
                ],
            ]
        );

        $auditors = collect([$request->auditor_1, $request->auditor_2, $request->auditor_3])->filter();
        $timestamp = now();

        if ($unit) {
            $prodi = Prodi::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($prodi->auditee as $auditee) {
                $auditee->auditor()->detach();
            }
            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditee()->attach($prodi->auditee, [
                    'prodi_id' => $prodi->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } else {
            abort(404);
        }


        return redirect()->route('gpm.auditor.show', ['jadwalAudit' => $jadwalAudit->id])->with('success', 'Auditor berhasil diubah!');
    }
}
