<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtl;
use App\Models\Jabatan;
use App\Models\Fakultas;
use App\Models\Unit;
use App\Models\JadwalAudit;
use App\Models\Auditee;
use App\Models\Kriteria;
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

        $hasilRtm = RtmTindakLanjut::whereHas('rtm_rtl', function($query) use ($rtmRtl) {
            $query->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id);
        })->first();

        if (!$hasilRtm) {
            return back()->with('error', 'Belum ada temuan yang masuk untuk diajukan pembahasan RTM Universitas');
        }

        $fakultas = $rtmRtl->fakultas_id ? Fakultas::find($rtmRtl->fakultas_id) : null;
        $unit = $rtmRtl->unit_id ? Unit::find($rtmRtl->unit_id) : null;
        $jabatanOptions = Jabatan::all();

        $temuanFakultas = RtmTindakLanjut::whereHas('rtm_rtl', function($query) use ($rtmRtl) {
            $query->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id);
            })
            ->when($rtmRtl->unit_id, function ($query) use ($rtmRtl) {
                return $query->where('unit_id', $rtmRtl->unit_id);
            })
            ->when($request->filled('jabatan_id'), function ($query) use ($request) {
                $query->whereHas('jabatan', function($q) use ($request) {
                    $q->whereIn('id', (array)$request->jabatan_id);
                });
            })
            ->with([
                'form.instrumen.jabatan',
                'form.jawaban_auditee',
                'form.ptk_form_deskripsi',
                'form.laporan_form',
                'kriteria',
                'rtm_rtl',
                'user',
                'jabatan',
            ])
            ->join('form', 'rtm_tindak_lanjut.form_id', '=', 'form.id')
            ->orderBy('form.instrumen_id', 'asc')
            ->get()
            ->map(function($item) use ($request) {
                if ($request->filled('jabatan_id')) {
                    $item->filtered_tindakan = $item->form->rtm_tindak_lanjut
                        ->whereIn('jabatan_id', (array)$request->jabatan_id);
                } else {
                    $item->filtered_tindakan = $item->form->rtm_tindak_lanjut;
                }
                return $item;
            })
            ->unique('id');

        //Paginasi filter
        $filteredForPagination = $temuanFakultas;
            
        if ($request->filled('jabatan_id')) {
            $filteredForPagination = $temuanFakultas->filter(function($item) use ($request) {
                return in_array(optional($item->jabatan)->id, (array)$request->jabatan_id);
            });
        }

        $isEmptyWithFilter = $request->filled('jabatan_id') && $filteredForPagination->isEmpty();

        if ($isEmptyWithFilter) {
            return redirect()
                ->route('admin.rtm-ptk.form', ['rtmPtk' => $rtmRtl->id])
                ->with('warning', 'Tidak ditemukan temuan untuk PIC dengan jabatan yang dipilih');
        }

        $perPage = 10;
        $currentPage = $request->query('page', 1);

        $paginatedTemuanFakultas = new LengthAwarePaginator(
            $filteredForPagination->forPage($currentPage, $perPage),
            $filteredForPagination->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($temuanFakultas->isEmpty()) {
            return back()->with('error', 'Tidak ada temuan untuk ditindaklanjuti.');
        }
        
        $jawabanRtm = RtmRtlForm::where('rtm_rtl_id', $rtmRtl->id)->get();

        $sessionFormData = session()->get('form_rtmRtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl->id, []);
        

        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanRtm' => $jawabanRtm,
            'sessionFormData' => $sessionFormData,
            'currentPage' => $currentPage,
            'status' => $status,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'temuanFakultas' => $temuanFakultas,
            'jabatanOptions' => $jabatanOptions,
            'hasilRtm' => $hasilRtm,
            'isEmptyWithFilter' => $isEmptyWithFilter,
            'selectedJabatan' => $request->jabatan_id ? Jabatan::whereIn('id', (array)$request->jabatan_id)->get() : collect(),
        ];

        return view('admin.rtm_univ.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $rtmRtl): JsonResponse
    {
        if ($request->ajax()) {
            try {

                $user = $this->user;
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'rekomendasi_') === 0) {
                        $id = substr($key, strlen('rekomendasi_'));
                        $rekomendasiKey = 'rekomendasi_' . $id;
                        $koreksiKey = 'koreksi_' . $id;

                        $data = [
                            'user_id' => $user,
                            'rekomendasi' => $request->input($rekomendasiKey, null),
                            'koreksi' => $request->input($koreksiKey, null),
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

    public function store_form(Request $request, RtmRtl $rtmRtl)
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        $user = $this->user;

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = 'form_rtmrtl-page_' . $i . '-rtmRtlId_' . $rtmRtl;
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

            return redirect()->route('admin.rtm.rtl.save_form', ['rtmRtl' => $rtmRtl, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;

                return redirect()->route('admin.rtm-rtl.save_form', ['rtmRtl' => $rtmRtl, 'page' => $errorPage])
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
            $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmRtl->rtm_jadwal])
            ->with('success', 'Data berhasil disimpan.');
    }

}
