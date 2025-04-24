<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use App\Models\RtmJadwal;
use App\Models\RtmRtl;
use App\Models\RtmRtlForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TindakLanjutController extends Controller
{
    public function show(RtmJadwal $rtmJadwal)
    {
        $rtmJadwal->load(['jadwal_audit', 'rtm_rtl']);
        
        if (!$rtmJadwal->jadwal_audit) {
            abort(404, 'Jadwal audit tidak ditemukan');
        }

        $fakultas = DB::table('fakultas')
            ->leftJoin('rtm_rtl', function ($join) use ($rtmJadwal) {
                $join->on('fakultas.id', '=', 'rtm_rtl.fakultas_id')
                    ->where('rtm_rtl.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl_form', 'rtm_rtl.id', '=', 'rtm_rtl_form.rtm_rtl_id')
            ->select([
                'fakultas.id',
                'fakultas.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'rtm_rtl_form.status',
                DB::raw("'fakultas' as jenis_unit")
            ])
            ->orderBy('fakultas.nama', 'asc')
            ->get();


        $unit = DB::table('unit')
            ->leftJoin('rtm_rtl', function ($join) use ($rtmJadwal) {
                $join->on('unit.id', '=', 'rtm_rtl.unit_id')
                    ->where('rtm_rtl.jadwal_audit_id', $rtmJadwal->jadwal_audit_id);
            })
            ->leftJoin('rtm_rtl_form', 'rtm_rtl.id', '=', 'rtm_rtl_form.rtm_rtl_id')
            ->select([
                'unit.id',
                'unit.nama',
                'rtm_rtl.id as rtm_rtl_id',
                'rtm_rtl_form.status',
                DB::raw("'unit' as jenis_unit")
            ])
            ->orderBy('unit.nama', 'asc')
            ->get();

        $units = $fakultas->merge($unit);

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'rtmJadwal' => $rtmJadwal,
            'units' => $units
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
        
        RtmRtl::updateOrCreate([
            'rtm_jadwal_id' => $request->jadwal_id,
            'fakultas_id' => $request->fakultas_id,
            'unit_id' => $request->unit_id,
            'jadwal_audit_id' => $request->jadwal_audit_id,
            'tgl' => Carbon::now()->toDateString(),
        ]);

        return response()->json(['success' => 'Tindak lanjut berhasil ditambahkan.']);
    }

    public function isi_rtm_rtl(string $rtmRtl)
    {
        RtmRtlform::where('rtm_rtl_id', $rtmRtl)->update(['status' => 'in_progress']);

        return redirect()->route('dekan.rtm-rtl.form', [
            'rtmRtl' => $rtmRtl,
        ]);
    }

}
