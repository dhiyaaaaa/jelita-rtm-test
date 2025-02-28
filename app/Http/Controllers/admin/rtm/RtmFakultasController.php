<?php

namespace App\Http\Controllers\admin\rtm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalAudit;
use App\Models\RtmJadwal;
use App\Models\RtmRtl;
use App\Models\Fakultas;
use App\Models\Unit;
use App\Models\JawabanAuditor;
use App\Models\RtmTindakLanjut;
use App\Models\Prodi;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;



class RtmFakultasController extends Controller
{
    public function index(): View
    {
        $jadwal = JadwalAudit::orderBy('created_at', 'DESC')->get();
        
        $data = [
            'title' => 'Periode Audit RTM',
            'jadwalAudit' => $jadwal,
        ];

        return view('admin.rtm_fakultas.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit)
    {
        $jadwalAudit = JadwalAudit::with('rtm_jadwal')->findOrFail($jadwalAudit->id);

        $rtmJadwalList = RtmJadwal::with(['fakultas', 'unit', 'rtm_rtl'])
            ->where('jadwal_audit_id', $jadwalAudit->id)
            ->get(); 

        $data = [
            'title' => 'Detail Rapat Tinjauan Manajemen',
            'jadwalAudit' => $jadwalAudit,
            'rtmJadwalList' => $rtmJadwalList,
        ];

        return view('admin.rtm_fakultas.show', $data);
    }



    public function detail($rtmJadwal)
    {
        $rtmJadwal = RtmJadwal::with(['jadwal_audit', 'fakultas', 'unit', 'rtm_rtl'])
            ->findOrFail($rtmJadwal);

        return view('admin.rtm_fakultas.detail', [
            'title' => 'Detail Hasil RTM Fakultas',
            'rtmJadwal' => $rtmJadwal,
        ]);
    }

    public function rtmrtl(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        // Ambil data jadwal audit
        $jadwalAudit = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);

        // Ambil data fakultas atau unit
        $fakultas = $rtmRtl->fakultas_id ? Fakultas::find($rtmRtl->fakultas_id) : null;
        $unit = $rtmRtl->unit_id ? Unit::find($rtmRtl->unit_id) : null;

        // Ambil data temuan fakultas/unit
        $temuanFakultas = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->when($rtmRtl->fakultas_id, function ($query) use ($rtmRtl) {
                return $query->where('fakultas_id', $rtmRtl->fakultas_id);
            })
            ->when($rtmRtl->unit_id, function ($query) use ($rtmRtl) {
                return $query->where('unit_id', $rtmRtl->unit_id);
            })
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->with([
                'form.instrumen.jabatan',
                'form.jawaban_auditee',
                'kriteria',
            ])
            ->orderBy('kriteria_id', 'asc')
            ->orderBy('instrumen.id', 'asc')
            ->select('jawaban_auditor.*')
            ->get();

        // Paginasi data temuan
        $perPage = 10;
        $currentPage = $request->query('page', 1);

        $paginatedTemuanFakultas = new LengthAwarePaginator(
            $temuanFakultas->forPage($currentPage, $perPage),
            $temuanFakultas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Ambil data jawaban tindak lanjut
        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
        ->get()
        ->groupBy('form_id');

        // Data yang akan dikirim ke view
        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'temuanFakultas' => $temuanFakultas,
            'jadwalAudit' => $jadwalAudit,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'isAdminView' => true, // Tambahkan flag untuk menandai bahwa ini adalah view admin
        ];

        return view('admin.rtm_fakultas.rtmrtl', $data);
    }

    public function rtmrtlprodi(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwalAudit = JadwalAudit::findOrfail($rtmRtl->jadwal_audit_id);
        $fakultas = $rtmRtl->fakultas;

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $prodiList = Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray();

        $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'jawaban_auditor.catatan')
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id') 
            ->where('jawaban_auditor.jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->whereIn('jawaban_auditor.prodi_id', $prodiList)
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->orderBy('jawaban_auditor.kriteria_id', 'asc') 
            ->with(['prodi', 'form.jawaban_auditor', 'form.instrumen', 'kriteria']) 
            ->get();

        $groupedTemuanProdi = $temuanProdi->groupBy('form.id');
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedTemuanProdi = new LengthAwarePaginator(
            $temuanProdi->slice($offset, $perPage),
            $temuanProdi->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
            ->with(['kriteria'])
            ->get()
            ->groupBy('form_id');

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Prodi',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanProdi' => $paginatedTemuanProdi,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'groupedTemuanProdi' => $groupedTemuanProdi,
            'temuanProdi' => $temuanProdi,
            'jawabanAuditor' => $jawaban_auditor,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.rtm_fakultas.rtmrtl_prodi', $data);
    }


}
