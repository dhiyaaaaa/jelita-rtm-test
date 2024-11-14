<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditeeAuditorStoreUpdateRequest;
use App\Http\Requests\AuditorStoreRequest;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AuditorController extends Controller
{
    // Ajax Request
    public function get_users(Request $request, JadwalAudit $jadwalAudit): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $auditors = User::select(['id', 'name', 'email'])->whereHas('roles', function ($query) {
                    $query->whereNotIn('name', ['pusjamu']);
                })->whereDoesntHave('auditor', function ($query) use ($jadwalAudit) {
                    $query->where('jadwal_audit_id', $jadwalAudit->id);
                })->get();

                return DataTables::of($auditors)
                    ->addColumn('checkbox', function ($item) {
                        return $item->id;
                    })
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error',
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid request.',
        ], 400);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Auditor',
            'jadwal' => $jadwal
        ];

        return view('admin.user.auditor.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalAudit $jadwalAudit): View
    {
        $data = [
            'title' => 'Tambah Auditor',
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.user.auditor.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuditorStoreRequest $request, JadwalAudit $jadwalAudit): RedirectResponse
    {
        foreach ($request->auditor as $auditorId) {
            $user = User::findOrFail($auditorId);

            if (!$user->hasRole('auditor')) {
                $roleAuditor = Role::where('name', 'auditor')->first();
                $user->roles()->attach($roleAuditor);
            }

            $exist = Auditor::where('user_id', $auditorId)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->first();

            if (!$exist) {
                Auditor::create([
                    'user_id' => $auditorId,
                    'jadwal_audit_id' => $jadwalAudit->id,
                ]);
            }
        }

        return redirect()->route('auditor')->with('success', 'Auditor berhasil ditambah!');
    }


    /**
     * Display the specified resource.
     */
    public function show(JadwalAudit $jadwalAudit): View
    {
        // Sweet Alert
        $title = 'Hapus Auditor!';
        $text = "Apakah Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        $auditors = JadwalAudit::whereHas('auditor', function ($query) use ($jadwalAudit) {
            $query->where('jadwal_audit_id', $jadwalAudit->id);
        })->with(['auditor'])->get();

        $data = [
            'title' => 'Auditor ' . $jadwalAudit->jadwal,
            'auditors' => $auditors,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.user.auditor.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auditor $auditor): RedirectResponse
    {
        $auditor->delete();

        return back()->with('success', 'Auditor berhasil dihapus!');
    }

    public function create_auditee_auditor(JadwalAudit $jadwalAudit, string $unit, string $type): View
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

        return view('admin.user.auditee.auditee_auditor.create', $data);
    }

    public function store_auditee_auditor(AuditeeAuditorStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
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
                $auditor->auditee()->attach($fakultas->auditee, [
                    'fakultas_id' => $fakultas->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } else if ($type === 'universitas') {
            $unit = Unit::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                });
            }])->findOrFail($unit);

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditee()->attach($unit->auditee, [
                    'unit_id' => $unit->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } elseif ($type === 'prodi') {
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

        $route = ($type === 'prodi') ? 'ps' : ($type === 'fakultas' || $type === 'universitas' ? 'upps' : '');

        return redirect()->route('auditee.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditor berhasil ditambah!');
    }


    public function edit_auditee_auditor(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        if ($type === 'prodi') {
            $auditor = AuditeeAuditor::select('auditor_id')
                ->where('prodi_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->groupBy('auditor_id')
                ->orderBy(DB::raw('MAX(updated_at)'), 'asc')
                ->pluck('auditor_id');
        } elseif ($type === 'fakultas') {
            $auditor = AuditeeAuditor::select('auditor_id')
                ->where('fakultas_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->groupBy('auditor_id')
                ->orderBy(DB::raw('MAX(updated_at)'), 'asc')
                ->pluck('auditor_id');
        } elseif ($type === 'universitas') {
            $auditor = AuditeeAuditor::select('auditor_id')
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

        return view('admin.user.auditee.auditee_auditor.edit', $data);
    }

    public function update_auditee_auditor(AuditeeAuditorStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
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
                $auditor->auditee()->attach($fakultas->auditee, [
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
                $auditee->auditor()->detach();
            }

            foreach ($auditors as $auditorId) {
                $auditor = Auditor::findOrFail($auditorId);
                $auditor->auditee()->attach($unit->auditee, [
                    'unit_id' => $unit->id,
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
                $timestamp = $timestamp->addMilliseconds(1000);
            }
        } elseif ($type === 'prodi') {
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

        $route = ($type === 'prodi') ? 'ps' : ($type === 'fakultas' || $type === 'universitas' ? 'upps' : '');

        return redirect()->route('auditee.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditor berhasil diubah!');
    }
}
