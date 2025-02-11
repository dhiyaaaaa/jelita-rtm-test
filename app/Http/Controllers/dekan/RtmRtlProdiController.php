<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtl;
use App\Models\Kriteria;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\JawabanAuditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

class RtmRtlProdiController extends Controller
{
    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $kriteriaList = Kriteria::whereIn('slug', ['belum-memenuhi', 'memenuhi', 'melampaui'])->pluck('id')->toArray();
        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);
        $fakultas = $rtmRtl->fakultas;
        $prodiList = Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray();

        // Ambil temuan berdasarkan Prodi
        $temuanProdi = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->whereIn('prodi_id', $prodiList)
            ->whereIn('kriteria_id', $kriteriaList)
            ->with([
                'form.instrumen',
                'form.ptk_form_deskripsi',
                'kriteria',
                'prodi',
                'form.laporan_form',
            ])
            ->get()
            ->groupBy(fn($temuan) => $temuan->form->instrumen->pernyataan)
            ->map(fn($temuanInstrumen) => $temuanInstrumen->groupBy(fn($temuan) => $temuan->kriteria->slug)
                ->map(fn($group) => $group->map(fn($temuan) => [
                    'prodi' => $temuan->prodi->nama, 
                    'kriteria' => $temuan->kriteria->slug,
                    'deskripsi' => optional($temuan->form->ptk_form_deskripsi->firstWhere('prodi_id', $temuan->prodi_id))->deskripsi,
                    'catatan' => optional($temuan->form->jawaban_auditor->firstWhere('prodi_id', $temuan->prodi_id))->catatan,
                    'kelebihan' => optional($temuan->form->laporan_form->firstWhere('prodi_id', $temuan->prodi_id))->kelebihan,
                    'ruang_peningkatan' => optional($temuan->form->laporan_form->firstWhere('prodi_id', $temuan->prodi_id))->ruang_peningkatan,
                ]))
            );

        $temuanProdiInstrumen = collect($temuanProdi)->flatten(1);

        // Paginasi
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItemsProdi = $temuanProdiInstrumen->slice(($currentPage - 1) * 10, 10)->values();
        $paginatedTemuanProdi = new LengthAwarePaginator(
            $currentPageItemsProdi,
            $temuanProdiInstrumen->count(),
            10,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('dekan.rtm.rtm_rtl.form_prodi', [
            'title' => 'Tindak Lanjut Hasil Audit',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanProdi' => $paginatedTemuanProdi,
            'jawabanTindakLanjut' => RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->get(),
            'sessionFormData' => session()->get("form_rtm_rtl_prodi-page_{$currentPage}-rtmRtlId_{$rtmRtl->id}-fakultasId_{$fakultas->id}", []),
            'status' => StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first(),
            'fakultas' => $fakultas,
            'temuanProdi' => $temuanProdi,
            'prodiList' => $prodiList
        ]);
    }

    public function save_form(Request $request, RtmRtl $rtmRtl, string $prodi): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'tindakan_') === 0) {
                    $id = substr($key, 9);
                    $data = [
                        'prodi_id' => $prodi,
                        'tindakan' => $value
                    ];

                    RtmTindakLanjut::updateOrCreate(
                        ['rtm_rtl_id' => $rtmRtl->id, 'form_id' => $id],
                        $data
                    );
                }
            }

            return response()->json(['message' => 'Jawaban berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan jawaban.'], 500);
        }
    }

    public function store_form(Request $request, RtmRtl $rtmRtl, Prodi $prodi): RedirectResponse
    {
        $rules = [];
        $messages = [];

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'tindakan_') === 0) {
                $rules[$key] = 'required';
                $messages[$key . '.required'] = 'Tindak lanjut harus diisi';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'tindakan_') === 0) {
                $id = substr($key, 9);
                RtmTindakLanjut::updateOrCreate(
                    ['rtm_rtl_id' => $rtmRtl->id, 'form_id' => $id],
                    ['prodi_id' => $prodi->id, 'tindakan' => $value]
                );
            }
        }

        return redirect()->route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id])
            ->with('success', 'Data berhasil disimpan.');
    }
}
