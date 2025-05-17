<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtlUniv;
use App\Models\Jabatan;
use App\Models\Fakultas;
use App\Models\Unit;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\Prodi;
use App\Models\RtmRtlForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class RtmRtlController extends Controller
{
    protected $user;
    
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);
        $selectedKriteria = $request->input('kriteria', []);

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $temuanRtm = RtmTindakLanjut::join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->where('rtm_rtl_id', $rtmRtl->id)
            ->whereHas('jabatan', function($query) {
                $query->where('type', 'universitas'); 
            })
            ->when(!empty($selectedKriteria), function($query) use ($selectedKriteria) {
                return $query->whereIn('kriteria_id', $selectedKriteria);
            })
            ->with([
                'form.instrumen',
                'form.jawaban_auditor',
                'form.ptk_form_deskripsi',
                'form.laporan_form',
                'kriteria',
                'jabatan',
            ])
            ->orderBy($order_by_kode)
            ->get()
            ->unique('form_id') 
            ->values();
        
        if ($temuanRtm->isEmpty()) {
            $temuanAuditor = JawabanAuditor::join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
                 ->where(function($query) use ($rtmRtl) {
                    if ($rtmRtl->fakultas_id) {
                        $query->where('fakultas_id', $rtmRtl->fakultas_id);
                    } else {
                        $query->where('unit_id', $rtmRtl->unit_id);
                    }
                })
                ->when(!empty($selectedKriteria), function($query) use ($selectedKriteria) {
                    return $query->whereIn('jawaban_auditor.kriteria_id', $selectedKriteria);
                })
                ->with([
                    'form.instrumen',
                    'form.jawaban_auditor',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'kriteria',
                ])
                ->orderBy($order_by_kode)
                ->get()
                ->unique('form_id') 
                ->values();

                if ($temuanAuditor->isEmpty()) {
                    return back()->with('warning', 'Tidak ada data temuan hasil audit yang tersedia');
                }
                
                $temuan = $temuanAuditor;
                $infoMessage = 'Menampilkan data hasil audit karena belum ada rencana tindak lanjut';
        } else {
            $temuan = $temuanRtm;
            $infoMessage = null;
        }
        
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $paginatedTemuan = new LengthAwarePaginator(
            $temuan->forPage($currentPage, $perPage),
            $temuan->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        

        $jawabanRtm = RtmRtlForm::where('rtm_rtl_id', $rtmRtl->id)->get();

        $sessionFormData = session()->get('form_rtmRtl_univ-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl, []);
        
        $status = StatusRtmRtlUniv::where('rtm_rtl_id', $rtmRtl->id)->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'rtmRtlId' => $rtmRtl->id,
            'paginatedTemuan' => $paginatedTemuan,
            'jawabanRtm' => $jawabanRtm,
            'sessionFormData' => $sessionFormData,
            'currentPage' => $currentPage,
            'status' => $status,
            'infoMessage' => $infoMessage,
            'temuan' => $temuan,
            'jawabanRtm' => $jawabanRtm,
            'selectedKriteria' => $selectedKriteria,
        ];

        return view('admin.rtm_univ.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $rtmRtl): JsonResponse
    {
        if ($request->ajax()) {
            try {

                $user = $this->user->id;
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'rekomendasi_') === 0) {
                        $formId = substr($key, strlen('rekomendasi_'));
                        $rekomendasi = $request->input($key, null);
                        $koreksi = $request->input('koreksi_' . $formId, null);

                        if (empty($rekomendasi) || empty($koreksi)) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Rekomendasi dan Koreksi harus diisi!'
                            ], 422);
                        }
    
                        $data = [
                            'rtm_rtl_id' => $rtmRtl,
                            'form_id' => $formId,
                            'user_id' => $user,
                            'rekomendasi' => $rekomendasi,
                            'koreksi' => $koreksi,
                        ];

                        RtmRtlForm::updateOrCreate(
                            [
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                            ],
                            $data
                        );
                    }
                }

                $response['message'] = 'Jawaban berhasil disimpan.';

                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menyimpan jawaban!',
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    public function store_form(Request $request, string $rtmRtl)
    {
        
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;
        $user = $this->user->id;
        $rtmRtl = RtmRtl::findOrFail($rtmRtl);

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = 'form_rtmRtl_univ-page_' . $i . '-rtmRtlId_' . $rtmRtl;
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }
        Log::debug('Session retrieved:', [$sessionKey => session()->get($sessionKey)]);

        $rules = [];
        $messages = [];
        $missingFields = [];
        $requestData = [];
        $nullCount = 0;
        $threshold = 1;

        foreach ($sessionFormData as $page => $data) {
            foreach ($data as $key => $value) {
                $requestData[$key] = $value;

                if (strpos($key, 'rekomendasi_') === 0 || strpos($key, 'koreksi_') === 0) {

                    if (strpos($key, 'rekomendasi_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Rekomendasi harus diisi';
                    }

                    if (strpos($key, 'koreksi_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Permintaan Tindakan Koreksi harus diisi';
                    }

                    if (!isset($value) || $value === '') {
                        $missingFields[] = $key;
                        ++$nullCount;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                } 
            }
        }

        $customErrorMessage = '';
        if ($nullCount >= $threshold) {
            $customErrorMessage = 'Harap isi semua jawaban terlebih dahulu.';
        }

        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = $customErrorMessage;

            if (!$customErrorMessage) {
                $errorMessage = $errors->first();
            }

            return redirect()->route('admin.rtm-rtl.form', ['rtmRtl' => $rtmRtl, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;

                return redirect()->route('admin.rtm-rtl.form', ['rtmRtl' => $rtmRtl, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'rekomendasi_') === 0) {
                $id = substr($key, strlen('rekomendasi_'));
                $rekomendasiKey = 'rekomendasi_' . $id;
                $koreksiKey = 'koreksi_' . $id;

                $data = [
                    'user_id' => $user,
                    'rekomendasi' => $requestData[$rekomendasiKey] ?? null,
                    'koreksi' => $requestData[$koreksiKey] ?? null,
                ];

                RtmRtlForm::updateOrCreate(
                    [
                        'rtm_rtl_id' => $rtmRtl,
                        'form_id' => $id,
                    ],
                    $data
                );
            }
        }

        if ($requestData['final'] == 'final') {
            $status = StatusRtmRtlUniv::where('rtm_rtl_id', $rtmRtl)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmRtl->rtm_jadwal])
            ->with('success', 'Data berhasil disimpan.');
    }

}
