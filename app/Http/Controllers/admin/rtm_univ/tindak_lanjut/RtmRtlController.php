<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RtmRtlUniv;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtlUniv;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditor;
use App\Models\RtmJadwal;
use App\Models\RtmRtlForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function form(Request $request, RtmRtlUniv $rtmRtlUniv): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtmRtlUniv->jadwal_audit_id);
        $rtmJadwal = RtmJadwal::findOrFail($rtmRtlUniv->rtm_jadwal_id);
        $selectedKriteria = $request->input('kriteria', []);

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        if($rtmRtlUniv->fakultas_id) 
        {
            $temuanRtm = RtmTindakLanjut::join('rtm_rtl', 'rtm_tindak_lanjut.rtm_rtl_id', "=", 'rtm_rtl.id')
                ->join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where(function($query) use ($rtmRtlUniv) {
                        if ($rtmRtlUniv->fakultas_id) {
                            $query->where('rtm_rtl.fakultas_id', $rtmRtlUniv->fakultas_id);
                        } else {
                            $query->where('rtm_rtl.unit_id', $rtmRtlUniv->unit_id);
                        }
                    })
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
                    return back()->with('warning', 'Tidak ada rencana tindak lanjut yang sudah dibuat pada kriteria yang Anda pilih');
                }
                $temuan = $temuanRtm;
            
        } else {
            $temuanAuditor = JawabanAuditor::join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jadwal_audit_id', $rtmRtlUniv->jadwal_audit_id)
                 ->where(function($query) use ($rtmRtlUniv) {
                    if ($rtmRtlUniv->fakultas_id) {
                        $query->where('fakultas_id', $rtmRtlUniv->fakultas_id);
                    } else {
                        $query->where('unit_id', $rtmRtlUniv->unit_id);
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
        

        $jawabanRtm = RtmRtlForm::where('rtm_rtl_univ_id', $rtmRtlUniv->id)->get();

        $sessionFormData = session()->get('form_rtmRtl_univ-page_' . $currentPage . '-rtmRtlUnivId_' . $rtmRtlUniv->id, []);
       
        $status = StatusrtmRtlUniv::where('rtm_rtl_univ_id', $rtmRtlUniv->id)->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'rtmRtlUnivId' => $rtmRtlUniv->id,
            'paginatedTemuan' => $paginatedTemuan,
            'jawabanRtm' => $jawabanRtm,
            'sessionFormData' => $sessionFormData,
            'currentPage' => $currentPage,
            'status' => $status,
            'temuan' => $temuan,
            'jawabanRtm' => $jawabanRtm,
            'selectedKriteria' => $selectedKriteria,
            'rtmJadwal' => $rtmJadwal,
        ];

        return view('admin.rtm_univ.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $rtmRtlUniv): JsonResponse
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
                            'rtm_rtl_univ_id' => $rtmRtlUniv,
                            'form_id' => $formId,
                            'user_id' => $user,
                            'rekomendasi' => $rekomendasi,
                            'koreksi' => $koreksi,
                        ];

                        RtmRtlForm::updateOrCreate(
                            [
                                'rtm_rtl_univ_id' => $rtmRtlUniv,
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

    public function store_form(Request $request, string $rtmRtlUniv)
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        $user = $this->user->id;
        $rtmRtlUniv = RtmRtlUniv::findOrFail($rtmRtlUniv);

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = 'form_rtmRtl_univ-page_' . $i . '-rtmRtlUnivId_' . $rtmRtlUniv->id;
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

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
            // Log::debug('Validasi gagal', [
            //     'errors' => $errors->messages(),
            //     'page' => $errorPage
            // ]);

            return redirect()->route('admin.rtm-rtl.form', ['rtmRtlUniv' => $rtmRtlUniv, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;


                return redirect()->route('admin.rtm-rtl.form', ['rtmRtlUniv' => $rtmRtlUniv, 'page' => $errorPage])
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

                rtmRtlForm::updateOrCreate(
                    [
                        'rtm_rtl_univ_id' => $rtmRtlUniv->id,
                        'form_id' => $id,
                    ],
                    $data
                );
            }
        }

        if ($requestData['final'] == 'final') {
            $status = StatusRtmRtlUniv::where('rtm_rtl_univ_id', $rtmRtlUniv->id)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
                Log::debug('Status diubah menjadi completed');
            }
        }
        return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmRtlUniv->rtm_jadwal_id]);
    }

}
 