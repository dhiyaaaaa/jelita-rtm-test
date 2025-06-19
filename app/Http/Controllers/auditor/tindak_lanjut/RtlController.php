<?php

namespace App\Http\Controllers\auditor\tindak_lanjut;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonitoringAuditorStoreRequest;
use App\Models\Auditor;
use App\Models\Auditee;
use App\Models\Prodi;
use App\Models\JawabanAuditor;
use App\Models\AuditeeAuditor;
use App\Models\AuditeeAuditorPtk;
use App\Models\Fakultas;
use App\Models\Monitoring;
use App\Models\MonitoringAuditee;
use App\Models\MonitoringForm;
use App\Models\StatusMonitoring;
use App\Models\JadwalAudit;
use App\Models\Kriteria;
use App\Models\MonitoringFormStatus;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;


class RtlController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;
    }
    public function create(string $jadwalAudit, string $unit, string $type): View
    {
        $auditees = Auditee::where(['jadwal_audit_id' => $jadwalAudit, get_type($type) => $unit])->get();

        $data = [
            'title' => 'Tambah Monitoring Tindak Lanjut',
            'auditees' => $auditees,
            'jadwalId' => $jadwalAudit,
            'unitId' => $unit,
            'type' => $type,
        ];

        return view('auditor.tindak_lanjut.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MonitoringAuditorStoreRequest $request, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = AuditeeAuditorPtk::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $jadwalAudit)
            ->distinct()
            ->where(get_type($type), $unit)
            ->orderBy('created_at', 'asc')
            ->get();

        $monitoring = Monitoring::updateOrCreate([
            'jadwal_audit_id' => $jadwalAudit,
            'tgl' => $request->tgl,
            get_type($type) => $unit
        ]);

        $monitoring->auditee()->attach($request->auditee);

        $timestamp = now();

        foreach ($auditors as $auditor) {
            $monitoring->auditor()->attach($auditor->auditor_id, [
                'created_at' => $timestamp->addMilliseconds(5000)
            ]);
        }

        return redirect()->route('auditor.tindak-lanjut.show', ['jadwalAudit' => $jadwalAudit])
            ->with('success', 'Form Monitoring Tindak Lanjut berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monitoring $monitoring): View
    {
        $unit = get_type_model($monitoring);

        $auditees = Auditee::where(['jadwal_audit_id' => $monitoring->jadwal_audit_id, $unit['kolom'] => $unit['value']])->get();

        $auditeeSelected = MonitoringAuditee::where('monitoring_id', $monitoring->id)->pluck('auditee_id');

        $data = [
            'title' => 'Edit Form Monitoring Tindak Lanjut',
            'auditees' => $auditees,
            'auditeeSelected' => $auditeeSelected,
            'monitoring' => $monitoring,
        ];

        return view('auditor.tindak_lanjut.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MonitoringAuditorStoreRequest $request, Monitoring $monitoring): RedirectResponse
    {
        $monitoring->update([
            'tgl' => $request->tgl,
        ]);

        // One to One
        $auditee = MonitoringAuditee::where('monitoring_id', $monitoring->id)->first();

        if ($auditee) {
            if ($auditee->auditee_id !== $request->auditee) {
                $auditee->update([
                    'auditee_id' => $request->auditee,
                    'approve' => 0,
                ]);
            }
        } else {
            MonitoringAuditee::create([
                'monitoring_id' => $monitoring->id,
                'auditee_id' => $request->auditee,
                'approve' => 0,
            ]);
        }

        // Auditor
        $unit = get_type_model($monitoring);
        $auditors = AuditeeAuditorPtk::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $monitoring->jadwal_audit_id)
            ->distinct()
            ->where($unit['kolom'], $unit['value'])
            ->orderBy('created_at', 'asc')
            ->pluck('auditor_id');

        $existingAuditors = $monitoring->auditor()->orderByPivot('created_at', 'asc')->pluck('auditor_id');

        if ($auditors->toArray() !== $existingAuditors->toArray()) {
            $timestamp = now();

            foreach ($auditors as $index => $auditorId) {
                $monitoring->auditor()->updateExistingPivot($auditorId, [
                    'created_at' => $timestamp->addMilliseconds(5000),
                ]);
            }
        }

        return redirect()->route('auditor.tindak-lanjut.show', [
            'jadwalAudit' => $monitoring->jadwal_audit_id,
        ])
            ->with('success', 'Form Monitoring Tindak Lanjut berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Monitoring $monitoring): RedirectResponse
    {
        $monitoring->delete();

        return back()->with('success', 'Form Monitoring berhasil dihapus.');
    }

    // Isi monitoring
    public function isi_monitoring(Monitoring $monitoring, $kriteria)
    {
        $kriteria = Kriteria::findOrFail($kriteria);

        StatusMonitoring::updateOrCreate(
            [
                'monitoring_id' => $monitoring->id,
                'kriteria_id' => $kriteria->id,
            ],
            ['status' => 'in_progress']
        );
        
        return redirect()->route('auditor.tindak-lanjut.form', [
                'monitoring' => $monitoring->id,
                'kriteria' => $kriteria->id
            ]);
    }

    
    public function form(Request $request, Monitoring $monitoring, $kriteria): View|RedirectResponse
    {
        $unit = get_type_model($monitoring);

        $auditor = Auditor::where([
            'jadwal_audit_id' => $monitoring->jadwal_audit_id,
            'user_id' => $this->user->id
        ])->first();

        $jadwal = JadwalAudit::findOrFail($monitoring->jadwal_audit_id);

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $monitoring->jadwal_audit_id])->first();
        
        $fakultas = $monitoring->fakultas_id ? Fakultas::find($monitoring->fakultas_id) : null;
        $unit = $monitoring->unit_id ? Unit::find($monitoring->unit_id) : null;

        $kriteria = Kriteria::findOrFail($kriteria);

        if ($unit) {
            $temuan = JawabanAuditor::where('jadwal_audit_id', $monitoring->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->when($monitoring->unit_id, function ($query) use ($monitoring) {
                    return $query->where('unit_id', $monitoring->unit_id);
                })
                ->with([
                    'form.instrumen',
                    'form.rtm_tindak_lanjut',
                    'form.ptk_form',
                    'form.rtl_form',
                    'form.rtm_rtl_form',
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->orderBy('form.instrumen_id', 'asc')
                ->get();
        } else {
           $prodiList = $fakultas ? Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray() : [];

            $temuan = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $monitoring->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->where('fakultas_id', $monitoring->fakultas_id)
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'form.rtm_rtl_form',
                    'kriteria',
                    'prodi',
                    'fakultas'
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id');
                
            $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $monitoring->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->whereIn('prodi_id', $prodiList)
                ->with([
                    'prodi', 
                    'form.jawaban_auditor', 
                    'form.instrumen', 
                    'kriteria',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'form.rtm_rtl_form',
                    'fakultas'
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id');

            $allTemuan = $temuan->union($temuanProdi)
            ->orderBy('instrumen_id', 'asc')
            ->get();

            $groupedTemuan = $allTemuan->groupBy('form_id');
            $temuan = collect();

            foreach ($groupedTemuan as $formId => $items) {
                $firstItem = $items->first();
                
                // $catatanAuditor = $items->map(function($item) {
                //     $catatan = '';
                //     if ($item->prodi) {
                //         $catatan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                //     }
                //     $catatan .= $item->catatan ?? 'Tidak ada catatan';
                //     return $catatan;
                // })->implode("\n");

                $PtkDeskripsi = $items->flatMap(function($item) {
                    return $item->form->ptk_form_deskripsi->map(function($deskripsi) use ($item) {
                        $deskripsiTemuan = '';
                        if ($item->prodi) {
                            $deskripsiTemuan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                        }
                        $deskripsiTemuan .= $deskripsi->deskripsi;
                        if ($deskripsi->form->ptk_form->first()) {
                            $deskripsiTemuan .= " ({$deskripsi->form->ptk_form->first()->kategori_temuan})";
                        }
                        return $deskripsiTemuan;
                    });
                })->implode("\n");

                $LaporanKelebihan = $items->flatMap(function($item) {
                    return $item->form->laporan_form->map(function($kelebihan) use ($item) {
                        $kelebihanTemuan = '';
                        if ($item->prodi) {
                            $kelebihanTemuan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                        }
                        $kelebihanTemuan .= $kelebihan->kelebihan;
                        return $kelebihanTemuan;
                    });
                })->implode("\n");

                //$firstItem->catatan_auditor = $catatanAuditor;
                $firstItem->deskripsi = $PtkDeskripsi;
                $firstItem->kelebihan = $LaporanKelebihan;
                
                $temuan->push($firstItem);
            }
        }

        // Paginasi
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $paginatedTemuan = new LengthAwarePaginator(
            $temuan->forPage($currentPage, $perPage),
            $temuan->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($temuan->isEmpty()) {
            return back()->with('error', 'Tidak ada temuan untuk ditindaklanjuti.');
        }

        $isianCatatan = MonitoringForm::where('monitoring_id', $monitoring->id)
        ->where('kriteria_id', $kriteria->id)->get();
        $isianStatus = MonitoringFormStatus::where('monitoring_id', $monitoring->id)
        ->where('kriteria_id', $kriteria->id)->get();

        $sessionFormData = session()->get('form_monitoring-page_' . $currentPage . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor->id . '-kriteriaId_' . $kriteria->id, []);
        
    
        $status = StatusMonitoring::where('monitoring_id', $monitoring->id)
        ->where('kriteria_id', $kriteria->id)
        ->first();

        $data = [
            'title' => 'Monitoring Tindak Lanjut Atas PTK',
            'monitoring' => $monitoring,
            'jadwal' => $jadwal,
            'auditor' => $auditor,
            'kriteriaTemuan' => $kriteria,
            'status' => $status,
            'isianStatus' => $isianStatus,
            'isianCatatan' => $isianCatatan,
            'sessionFormData' => $sessionFormData,
            'currentPage'=> $currentPage,
            'temuan' => $paginatedTemuan,
            'kriteria' => $kriteria, 
            'paginatedTemuan' => $paginatedTemuan,
            'currentPage'=> $currentPage,
        ];

        return view('auditor.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $monitoring, string $auditor, string $kriteria): JsonResponse
    {
        Log::debug('Request Data:', $request->all());
        Log::debug('Monitoring ID:', ['monitoring' => $monitoring]);
        Log::debug('Auditor ID:', ['auditor' => $auditor]);
        Log::debug('Kriteria ID:', ['kriteria' => $kriteria]);

        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            $response = [];
            $kriteria = Kriteria::findOrFail($kriteria);

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'status_') === 0) {
                    $id = substr($key, strlen('status_'));
                    $status = $request->input($key);

                    if (!in_array($status, ['selesai', 'proses', 'belum_dilaksanakan', null], true)) {
                        return response()->json(['message' => 'Harap periksa jawaban terlebih dahulu.'], 422);
                    }

                    MonitoringFormStatus::updateOrCreate(
                        [
                            'monitoring_id' => $monitoring,
                            'form_id' => $id,
                            'kriteria_id' => $kriteria->id, // Make sure this is included
                            'auditor_id' => $auditor, // Also include auditor_id in the search criteria
                        ],
                        [
                            'status' => $status,
                        ]
                    );
                }

                if (preg_match('/^catatan_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    $existCatatan = MonitoringForm::where('monitoring_id', $monitoring)
                        ->where('form_id', $formId)
                        ->where('kriteria_id', $kriteria->id) // Add kriteria_id to the query
                        ->pluck('catatan', 'id')->toArray();

                    $submittedCatatans = is_array($value) ? array_filter($value) : [];

                    foreach ($existCatatan as $id => $catatan) {
                        if (!in_array($catatan, $submittedCatatans)) {
                            MonitoringForm::where('id', $id)->delete();
                        }
                    }

                    foreach ($submittedCatatans as $catatan) {
                        if (!in_array($catatan, $existCatatan)) {
                            MonitoringForm::updateOrCreate(
                                [
                                    'monitoring_id' => $monitoring,
                                    'form_id' => $formId,
                                    'kriteria_id' => $kriteria->id, // Include here
                                    'catatan' => $catatan,
                                ],
                                [
                                    'auditor_id' => $auditor,
                                    'catatan' => $catatan,
                                ]
                            );
                        }
                    }
                }
            }

            $response['message'] = 'Monitoring tindak lanjut berhasil disimpan.';
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error menyimpan form monitoring: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan hasil monitoring tindak lanjut!',
            ], 500);
        }
    }
    // Save Form per Nomor (backup)
    public function save_form_per_nomor(Request $request, string $monitoring, string $auditor, string $formId) {}

    public function store_form(Request $request, Monitoring $monitoring, string $auditor, string $kriteria): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $kriteriaId = $kriteria;
        $sessionFormData = [];
        $errorPage = null;

        // Ambil data dari semua halaman yang tersimpan di session
        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_monitoring-page_' . $i . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor . '-kriteriaId_' . $kriteria;
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

        $rules = [];
        $messages = [];
        $missingFields = [];
        $formId = [];
        $requestData = [];
        $nullCount = 0;
        $threshold = 1;

        // Validasi data dari session
        foreach ($sessionFormData as $page => $data) {
            foreach ($data as $key => $value) {
                $requestData[$key] = $value;
                if (strpos($key, 'status_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    $statusKey = 'status_' . $id;
                    $rules[$statusKey] = 'required';
                    $messages[$statusKey . '.required'] = 'Status harus diisi';

                    if (!isset($value) || $value === '') {
                        $missingFields[] = $key;
                        $nullCount++;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                } elseif (strpos($key, 'catatan_') === 0) {
                    if (is_array($value) && (empty($value) || $value[0] === null || $value[0] === '')) {
                        foreach ($value as $catatanKey => $catatanValue) {
                            $rules[$key . '.' . $catatanKey] = 'required';
                            $messages[$key . '.' . $catatanKey . '.required'] = 'Catatan harus diisi.';
                            $missingFields[] = $key . '.' . $catatanKey;
                            $nullCount++;
                            if ($errorPage === null) {
                                $errorPage = $page;
                            }
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

            return redirect()->route('auditor.tindak-lanjut.form', ['monitoring' => $monitoring->id, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;
                return redirect()->route('auditor.tindak-lanjut.form', ['monitoring' => $monitoring->id, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        $existingForms = MonitoringFormStatus::where('monitoring_id', $monitoring->id)->pluck('form_id')->toArray();
        $newFormIds = [];

        // Simpan semua data ke database
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'status_') === 0) {
                $id = substr($key, strlen('status_'));
                $newFormIds[] = $id;
                $statusKey = 'status_' . $id;

                $data = [
                    'monitoring_id' => $monitoring->id,
                    'form_id' => $id,
                    'auditor_id' => $auditor,
                    'kriteria_id' => $kriteriaId,
                    'status' => $requestData[$statusKey] ?? null,

                ];

                MonitoringFormStatus::updateOrCreate(
                    [
                        'monitoring_id' => $monitoring->id,
                        'form_id' => $id,
                    ],
                    $data
                );
                if (array_key_exists('catatan_' . $id, $requestData)) {
                    $existCatatan = MonitoringForm::where('monitoring_id', $monitoring->id)
                        ->where('form_id', $id)
                        ->pluck('catatan', 'id')->toArray();

                    $submittedCatatans = $requestData['catatan_' . $id];

                    if (!is_array($submittedCatatans)) {
                        return response()->json(['message' => 'Invalid input data format'], 422);
                    }

                    $submittedCatatans = array_filter($submittedCatatans);

                    $currentSubmittedCatatans = array_map(function ($catatan) {
                        return $catatan;
                    }, $submittedCatatans);

                    foreach ($existCatatan as $jawabanId => $catatan) {
                        if (!in_array($catatan, $currentSubmittedCatatans)) {
                            MonitoringForm::where('id', $jawabanId)->delete();
                        }
                    }

                    foreach ($submittedCatatans as $catatan) {
                        if (!in_array($catatan, $existCatatan)) {
                            MonitoringForm::updateOrCreate(
                                [
                                    'monitoring_id' => $monitoring->id,
                                    'form_id' => $id,
                                    'catatan' => $catatan,
                                ],
                                [
                                    'auditor_id' => $auditor,
                                    'kriteria_id' => $kriteriaId,
                                    'catatan' => $catatan,
                                ]
                            );
                        }
                    }
                }
            }
        }

        // Update status  jika semua halaman telah diisi
        StatusMonitoring::updateOrCreate(
                [
                    'monitoring_id' => $monitoring->id,
                    'kriteria_id' => $kriteria,
                ],
                ['status' => 'completed']
            );

        return redirect()->route('auditor.tindak-lanjut.show', $monitoring->jadwal_audit_id)
            ->with('success', 'Data berhasil disimpan.');
    }
}
