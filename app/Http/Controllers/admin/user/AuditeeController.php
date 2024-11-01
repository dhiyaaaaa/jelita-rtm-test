<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditeeStoreUpdateRequest;
use App\Models\Auditee;
use App\Models\Fakultas;
use App\Models\JadwalAudit;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuditeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Auditan',
            'jadwal' => $jadwal
        ];

        return view('admin.user.auditee.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        if ($type === 'prodi') {
            $user = User::whereHas('jabatan', function ($query) use ($unit) {
                $query->where('prodi_id', $unit);
            })->get()->unique();
            $route = 'ps';
        } else if ($type === 'fakultas') {
            $pascasarjana = Fakultas::where('nama', 'Pascasarjana')->first();
            if ($pascasarjana->id == $unit) {
                $jenjangPascaSarjana = Jenjang::whereIn('nama', ['S2', 'S3'])->pluck('id')->toArray();

                $prodi = Prodi::whereIn('jenjang_id', $jenjangPascaSarjana)->pluck('id')->toArray();

                $user = User::whereHas('jabatan', function ($query) use ($prodi, $unit) {
                    $query->whereIn('prodi_id', $prodi)->orWhere('fakultas_id', $unit)->orWhere([
                        ['prodi_id', null],
                        ['fakultas_id', null],
                        ['unit_id', null],
                    ]);
                })->get()->unique();
            } else {
                $prodi = Prodi::where('fakultas_id', $unit)->pluck('id')->toArray();
                $user = User::whereHas('jabatan', function ($query) use ($prodi, $unit) {
                    $query->whereIn('prodi_id', $prodi)->orWhere('fakultas_id', $unit);
                })->get()->unique();
            }
            $route = 'upps';
        } else if ($type === 'universitas') {
            $user = User::whereHas('jabatan', function ($query) use ($unit) {
                $query->where('unit_id', $unit);
            })->get()->unique();
            $route = 'upps';
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Tambah Auditan',
            'user' => $user,
            'unit' => $unit,
            'type' => $type,
            'route' => $route,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.user.auditee.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuditeeStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        if ($type === 'fakultas') {
            $route = 'upps';
            foreach ($request->auditee as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                Auditee::create([
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'user_id' => $auditeeId,
                    'jabatan_id' => $user->jabatan->first()->id,
                    'fakultas_id' => $unit,
                ]);
            }
        } else if ($type === 'universitas') {
            $route = 'upps';
            foreach ($request->auditee as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                Auditee::create([
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'user_id' => $auditeeId,
                    'jabatan_id' => $user->jabatan->first()->id,
                    'unit_id' => $unit,
                ]);
            }
        } elseif ($type === 'prodi') {
            $route = 'ps';
            foreach ($request->auditee as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                Auditee::create([
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'user_id' => $auditeeId,
                    'jabatan_id' => $user->jabatan->first()->id,
                    'prodi_id' => $unit,
                ]);
            }
        } else {
            abort(404);
        }

        return redirect()->route('auditee.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditee berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalAudit $jadwalAudit, string $type): View
    {
        // Sweet Alert
        $title = 'Hapus Auditan dan Auditor!';
        $text = "Apakah Anda yakin ingin menghapus auditan dan auditor ini?";
        confirmDelete($title, $text);

        $ps = collect();
        $upps = collect();

        if ($type === 'ps') {
            $ps = Prodi::with(['jenjang', 'auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                })->with(['user', 'auditor.user']);
            }])->get();
        } elseif ($type === 'upps') {
            $fakultas = Fakultas::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                })->with(['user.jabatan', 'auditor.user']);
            }])->get();

            $unit = Unit::with(['auditee' => function ($query) use ($jadwalAudit) {
                $query->whereHas('jadwal_audit', function ($subQuery) use ($jadwalAudit) {
                    $subQuery->where('jadwal_audit_id', $jadwalAudit->id);
                })->with(['user.jabatan', 'auditor.user']);
            }])->get();

            $upps = $unit->concat($fakultas)->all();
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Auditan ' . strtoupper($jadwalAudit->jadwal),
            'ps' => $ps,
            'upps' => $upps,
            'jadwalAudit' => $jadwalAudit,
            'type' => $type,
        ];

        return view('admin.user.auditee.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        if ($type === 'prodi') {
            $user = User::whereHas('jabatan', function ($query) use ($unit) {
                $query->where('prodi_id', $unit);
            })->get()->unique();
            $route = 'ps';
            $auditee = Auditee::where('prodi_id', $unit)->where('jadwal_audit_id', $jadwalAudit->id)->pluck('user_id');
        } else if ($type === 'fakultas') {
            $pascasarjana = Fakultas::where('nama', 'Pascasarjana')->first();
            if ($pascasarjana->id == $unit) {
                $jenjangPascaSarjana = Jenjang::whereIn('nama', ['S2', 'S3'])->pluck('id')->toArray();

                $prodi = Prodi::whereIn('jenjang_id', $jenjangPascaSarjana)->pluck('id')->toArray();

                $user = User::whereHas('jabatan', function ($query) use ($prodi, $unit) {
                    $query->whereIn('prodi_id', $prodi)->orWhere('fakultas_id', $unit)->orWhere([
                        ['prodi_id', null],
                        ['fakultas_id', null],
                        ['unit_id', null],
                    ]);
                })->get()->unique();
            } else {
                $prodi = Prodi::where('fakultas_id', $unit)->pluck('id')->toArray();
                $user = User::whereHas('jabatan', function ($query) use ($prodi, $unit) {
                    $query->whereIn('prodi_id', $prodi)->orWhere('fakultas_id', $unit);
                })->get()->unique();
            }
            $route = 'upps';
            $auditee = Auditee::where('fakultas_id', $unit)->where('jadwal_audit_id', $jadwalAudit->id)->pluck('user_id');
        } else if ($type === 'universitas') {
            $user = User::whereHas('jabatan', function ($query) use ($unit) {
                $query->where('unit_id', $unit);
            })->get()->unique();
            $route = 'upps';
            $auditee = Auditee::where('unit_id', $unit)->where('jadwal_audit_id', $jadwalAudit->id)->pluck('user_id');
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Edit Auditan',
            'user' => $user,
            'auditee' => $auditee,
            'unit' => $unit,
            'type' => $type,
            'route' => $route,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.user.auditee.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuditeeStoreUpdateRequest $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $newAuditeeIds = collect($request->auditee);

        if ($type === 'fakultas') {
            $route = 'upps';
            $auditees = Auditee::where('fakultas_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->get();

            $existingAuditees = $auditees->keyBy('user_id');
            $auditeesToDelete = $existingAuditees->keys()->diff($newAuditeeIds);
            $auditors = [];

            foreach ($auditees as $auditee) {
                $auditorIds = $auditee->auditor->pluck('id')->toArray();
                $auditors = $auditorIds;
            }

            Auditee::whereIn('user_id', $auditeesToDelete)
                ->where('fakultas_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->delete();

            foreach ($newAuditeeIds as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                if ($existingAuditees->has($auditeeId)) {
                    $auditee = $existingAuditees->get($auditeeId);
                    $auditee->update([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'fakultas_id' => $unit,
                    ]);
                } else {
                    $newAuditee = Auditee::create([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'user_id' => $auditeeId,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'fakultas_id' => $unit,
                    ]);

                    foreach ($auditors as $auditor) {
                        $newAuditee->auditor()->attach($auditor, [
                            'jadwal_audit_id' => $jadwalAudit->id,
                            'fakultas_id' => $unit,
                        ]);
                    }
                }
            }
        } elseif ($type === 'universitas') {
            $route = 'upps';
            $auditees = Auditee::where('unit_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->get();

            $existingAuditees = $auditees->keyBy('user_id');
            $auditeesToDelete = $existingAuditees->keys()->diff($newAuditeeIds);
            $auditors = [];

            foreach ($auditees as $auditee) {
                $auditorIds = $auditee->auditor->pluck('id')->toArray();
                $auditors = $auditorIds;
            }

            Auditee::whereIn('user_id', $auditeesToDelete)
                ->where('unit_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->delete();

            foreach ($newAuditeeIds as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                if ($existingAuditees->has($auditeeId)) {
                    $auditee = $existingAuditees->get($auditeeId);
                    $auditee->update([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'unit_id' => $unit,
                    ]);
                } else {
                    $newAuditee = Auditee::create([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'user_id' => $auditeeId,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'unit_id' => $unit,
                    ]);

                    foreach ($auditors as $auditor) {
                        $newAuditee->auditor()->attach($auditor, [
                            'jadwal_audit_id' => $jadwalAudit->id,
                            'unit_id' => $unit,
                        ]);
                    }
                }
            }
        } elseif ($type === 'prodi') {
            $route = 'ps';
            $auditees = Auditee::where('prodi_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->get();

            $existingAuditees = $auditees->keyBy('user_id');
            $auditeesToDelete = $existingAuditees->keys()->diff($newAuditeeIds);
            $auditors = [];

            foreach ($auditees as $auditee) {
                $auditorIds = $auditee->auditor->pluck('id')->toArray();
                $auditors = $auditorIds;
            }

            Auditee::whereIn('user_id', $auditeesToDelete)
                ->where('prodi_id', $unit)
                ->where('jadwal_audit_id', $jadwalAudit->id)
                ->delete();

            foreach ($newAuditeeIds as $auditeeId) {
                $user = User::findOrFail($auditeeId);

                if ($existingAuditees->has($auditeeId)) {
                    $auditee = $existingAuditees->get($auditeeId);
                    $auditee->update([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'prodi_id' => $unit,
                    ]);
                } else {
                    $newAuditee = Auditee::create([
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'user_id' => $auditeeId,
                        'jabatan_id' => $user->jabatan->first()->id,
                        'prodi_id' => $unit,
                    ]);

                    foreach ($auditors as $auditor) {
                        $newAuditee->auditor()->attach($auditor, [
                            'jadwal_audit_id' => $jadwalAudit->id,
                            'prodi_id' => $unit,
                        ]);
                    }
                }
            }
        } else {
            abort(404);
        }

        return redirect()->route('auditee.show', ['jadwalAudit' => $jadwalAudit->id, 'type' => $route])->with('success', 'Auditee berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        if ($type === 'prodi') {
            $auditees = Auditee::where(['prodi_id' => $unit, 'jadwal_audit_id' => $jadwalAudit->id])->get();
            foreach ($auditees as $auditee) {
                $auditee->auditor()->detach();
                $auditee->delete();
            }
        } else if ($type === 'fakultas') {
            $auditees = Auditee::where(['fakultas_id' => $unit, 'jadwal_audit_id' => $jadwalAudit->id])->get();
            foreach ($auditees as $auditee) {
                $auditee->auditor()->detach();
                $auditee->delete();
            }
        } else if ($type === 'universitas') {
            $auditees = Auditee::where(['unit_id' => $unit, 'jadwal_audit_id' => $jadwalAudit->id])->get();
            foreach ($auditees as $auditee) {
                $auditee->auditor()->detach();
                $auditee->delete();
            }
        } else {
            abort(404);
        }

        return back()->with('success', 'Auditee dan Auditor berhasil dihapus!');
    }
}
