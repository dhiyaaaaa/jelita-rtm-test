<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use App\Models\RtmJadwal;
use App\Models\RtmRtlUniv;
use App\Models\Kriteria;
use App\Models\RtmUnivApprove;
use App\Models\StatusRtmRtlUniv;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TindakLanjutController extends Controller
{
    protected $user;
    protected $jabatanUser;
    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
    }

    public function show(RtmJadwal $rtmJadwal)
    {
        $rtmJadwal->load(['jadwal_audit', 'rtm_rtl_univ', 'user']);
        
        if (!$rtmJadwal->jadwal_audit) {
            abort(404, 'Jadwal audit tidak ditemukan');
        }

        $kriteriaOptions = Kriteria::all();
        $selectedKriteria = request('kriteria', []);

        $countTemuanFakultas = DB::table('rtm_tindak_lanjut')
            ->join('rtm_rtl', 'rtm_tindak_lanjut.rtm_rtl_id', '=', 'rtm_rtl.id')
            ->join('jabatan', 'rtm_tindak_lanjut.jabatan_id', '=', 'jabatan.id')
            ->selectRaw('
                rtm_rtl.fakultas_id as fakultas_id,
                COUNT(DISTINCT rtm_tindak_lanjut.form_id) as jumlah_temuan_fakultas
            ')
            ->where('rtm_rtl.jadwal_audit_id', $rtmJadwal->jadwal_audit_id)
            ->whereNotNull('rtm_rtl.fakultas_id')
            ->where('jabatan.type', 'universitas')
            ->when(!empty($selectedKriteria), function($query) use ($selectedKriteria) {
                return $query->whereIn('rtm_tindak_lanjut.kriteria_id', $selectedKriteria);
            })
            ->groupBy('rtm_rtl.fakultas_id');

        $countTemuanUnit = DB::table('jawaban_auditor')
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->selectRaw('
                jawaban_auditor.unit_id as unit_id,
                COUNT(DISTINCT jawaban_auditor.form_id) as jumlah_temuan_unit
            ')
            ->where('jawaban_auditor.jadwal_audit_id', $rtmJadwal->jadwal_audit_id)
            ->whereNotNull('jawaban_auditor.unit_id')
            ->when(!empty($selectedKriteria), function($query) use ($selectedKriteria) {
                return $query->whereIn('jawaban_auditor.kriteria_id', $selectedKriteria);
            })
            ->groupBy('jawaban_auditor.unit_id');

        $fakultas = DB::table('fakultas')
            ->leftJoin('rtm_rtl_univ', function ($join) use ($rtmJadwal) {
                $join->on('fakultas.id', '=', 'rtm_rtl_univ.fakultas_id')
                    ->where('rtm_rtl_univ.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl', function ($join) use ($rtmJadwal) {
                $join->on('fakultas.id', '=', 'rtm_rtl.fakultas_id')
                    ->where('rtm_rtl.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('status_rtm_rtl_univ', 'rtm_rtl_univ.id', '=', 'status_rtm_rtl_univ.rtm_rtl_univ_id')
            ->leftJoin('status_rtm_rtl', 'rtm_rtl.id', '=', 'status_rtm_rtl.rtm_rtl_id')
            ->leftJoin('rtm_rtl_univ_approve', 'rtm_rtl_univ.id', '=', 'rtm_rtl_univ_approve.rtm_rtl_univ_id')
            ->leftJoinSub($countTemuanFakultas, 'temuan_fakultas', function ($join) {
                $join->on('fakultas.id', '=', 'temuan_fakultas.fakultas_id');
            })
            ->select([
                'fakultas.id',
                'fakultas.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'status_rtm_rtl.status',
                'rtm_rtl_univ.id as rtm_rtl_univ_id',
                'status_rtm_rtl_univ.status',
                'rtm_rtl_univ_approve.approve as approval_status',
                DB::raw("'fakultas' as jenis_unit"),
                DB::raw('COALESCE(temuan_fakultas.jumlah_temuan_fakultas, 0) as jumlah_temuan')
            ])
            ->orderBy('fakultas.nama', 'asc')
            ->get();


        $unit = DB::table('unit')
            ->leftJoin('rtm_rtl_univ', function ($join) use ($rtmJadwal) {
                $join->on('unit.id', '=', 'rtm_rtl_univ.unit_id')
                    ->where('rtm_rtl_univ.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl', function ($join) use ($rtmJadwal) {
                $join->on('unit.id', '=', 'rtm_rtl.unit_id')
                    ->where('rtm_rtl.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('status_rtm_rtl_univ', 'rtm_rtl_univ.id', '=', 'status_rtm_rtl_univ.rtm_rtl_univ_id')
            ->leftJoin('status_rtm_rtl', 'rtm_rtl.id', '=', 'status_rtm_rtl.rtm_rtl_id')
            ->leftJoin('rtm_rtl_univ_approve', 'rtm_rtl_univ.id', '=', 'rtm_rtl_univ_approve.rtm_rtl_univ_id')
            ->leftJoinSub($countTemuanUnit, 'temuan_unit', function ($join) {
                $join->on('unit.id', '=', 'temuan_unit.unit_id');
            })
            ->select([
                'unit.id',
                'unit.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'status_rtm_rtl.status',
                'rtm_rtl_univ.id as rtm_rtl_univ_id',
                'status_rtm_rtl_univ.status',
                'rtm_rtl_univ_approve.approve as approval_status',
                DB::raw("'unit' as jenis_unit"),
                DB::raw('COALESCE(temuan_unit.jumlah_temuan_unit, 0) as jumlah_temuan')
            ])
            ->orderBy('unit.nama', 'asc')
            ->get(); 

        $units = $fakultas->merge($unit)->unique('id');
        $user = $this->user->id;

        $rektor = User::whereHas('jabatan', fn($q) => $q->where('slug', 'rektor'))->first();
        $ketuaLP3M = User::whereHas('jabatan', fn($q) => $q->where('slug', 'ketua-lp3m'))->first();
        
        // Data Approve
        $approvalRektor = RtmUnivApprove::with(['user.jabatan'])
        ->where('rtm_jadwal_id', $rtmJadwal->id)
        ->get()
        ->first(function ($item) {
            return $item->user->jabatan->contains('slug', 'rektor');
        });

        $approvalKetuaLP3M = RtmUnivApprove::with(['user.jabatan'])
        ->where('rtm_jadwal_id', $rtmJadwal->id)
        ->get()
        ->first(function ($item) {
            return $item->user->jabatan->contains('slug', 'ketua-lp3m');
        });

        $isRektor = $this->user->jabatan->contains('slug', 'rektor');
        $isKetuaLP3M = $this->user->jabatan->contains('slug', 'ketua-lp3m');

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'rtmJadwal' => $rtmJadwal,
            'units' => $units,
            'kriteriaOptions' => $kriteriaOptions,
            'user' => $user,
            'approvalRektor' => $approvalRektor,
            'approvalKetuaLP3M' => $approvalKetuaLP3M,
            'isRektor' => $isRektor,
            'isKetuaLP3M' => $isKetuaLP3M,
            'rektorId' => optional($rektor)->id,
            'ketuaLP3MId' => optional($ketuaLP3M)->id,
        ];
        return view('admin.rtm_univ.tindak_lanjut.show', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:rtm_jadwal,id',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'unit_id' => 'nullable|exists:unit,id',
            'jadwal_audit_id' => 'required|exists:jadwal_audit,id',
        ]);
        
        RtmRtlUniv::updateOrCreate([
            'rtm_jadwal_id' => $request->jadwal_id,
            'fakultas_id' => $request->fakultas_id,
            'unit_id' => $request->unit_id,
            'jadwal_audit_id' => $request->jadwal_audit_id,
            'tgl' => Carbon::now()->toDateString(),
        ]);

        return response()->json(['success' => 'Tindak lanjut berhasil ditambahkan.']);
    }

    public function isi_rtm_rtl_univ(RtmRtlUniv $rtmRtlUniv)
    {
        StatusRtmRtlUniv::updateOrCreate(
            [
                'rtm_rtl_univ_id' => $rtmRtlUniv->id,
            ],
            ['status' => 'in_progress']
        );

        return redirect()->route('admin.rtm-rtl.form', [
            'rtmRtlUniv' => $rtmRtlUniv, 
        ]);
    }
}
