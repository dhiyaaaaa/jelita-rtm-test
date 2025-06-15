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
use App\Models\Monitoring;
use App\Models\MonitoringAuditee;
use App\Models\MonitoringForm;
use App\Models\StatusMonitoring;
use App\Models\JadwalAudit;
use App\Models\Kriteria;
use App\Models\MonitoringFormStatus;
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

        $kriteria = Kriteria::findOrFail($kriteria);

        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $temuan = JawabanAuditor::where('jadwal_audit_id', $monitoring->jadwal_audit_id)
            ->where('kriteria_id', $kriteria->id)
            ->where($unit['kolom'], $unit['value']);

        if ($unit['type'] === 'fakultas') {
            $prodiList = Prodi::where('fakultas_id', $unit['value'])->pluck('id')->toArray();

            $temuanProdi = JawabanAuditor::where('jadwal_audit_id', $monitoring->jadwal_audit_id)
                ->whereIn('prodi_id', $prodiList)
                ->where('kriteria_id', $kriteria->id);
                
            $temuanNegatif = $temuan->union($temuanProdi)
            ->with([
                'form.instrumen',
                'form.rtm_tindak_lanjut',
                'form.ptk_form',
                'form.rtl_form',
                'form.rtm_rtl_form',
            ])->paginate($perPage);
        } else {
            $temuanNegatif = $temuan
            ->with([
                'form.instrumen',
                'form.rtm_tindak_lanjut',
                'form.ptk_form',
                'form.rtl_form',
                'form.rtm_rtl_form',
            ])
            ->paginate($perPage);
        }

        // Paginasi
        $temuanNegatif = new LengthAwarePaginator(
            $temuanNegatif->forPage($currentPage, $perPage),
            $temuanNegatif->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $isianCatatan = MonitoringForm::where('monitoring_id', $monitoring->id)->get();
        $isianStatus = MonitoringFormStatus::where('monitoring_id', $monitoring->id)->get();

        $sessionFormData = session()->get('form_monitoring-page_' . $currentPage . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor->id, []);
        
    
        $status = StatusMonitoring::where('monitoring_id', $monitoring->id)
        ->where('kriteria_id', $kriteria->id)
        ->first();

        $data = [
            'title' => 'Monitoring Tindak Lanjut Atas PTK',
            'temuanNegatif' => $temuanNegatif,
            'monitoring' => $monitoring,
            'jadwal' => $jadwal,
            'auditor' => $auditor,
            'kriteriaTemuan' => $kriteria,
            'status' => $status,
            'isianStatus' => $isianStatus,
            'isianCatatan' => $isianCatatan,
            'sessionFormData' => $sessionFormData,
            'currentPage'=> $currentPage,
            'unitType' => $unit['type'], 
            'kriteria' => $kriteria, 
            'kriteriaId' => $kriteria,
        ];

        return view('auditor.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $monitoring, string $auditor, string $kriteria): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'status_') === 0) {
                        $id = substr($key, strlen('status_'));
                        $statusKey = 'status_' . $id;
                        $status = $request->input($statusKey);


                        if (!in_array($status, ['selesai', 'proses','belum_dilaksanakan', null], true)) {
                            return response()->json(['message' => 'Harap periksa jawaban terlebih dahulu.'], 422);
                        }

                        $data = [
                            'auditor_id' => $auditor,
                            'status' => $status,
                        ];

                        MonitoringFormStatus::updateOrCreate(
                            [
                                'monitoring_id' => $monitoring,
                                'form_id' => $id,
                            ],
                            $data
                        );
                    }
                    if (preg_match('/^catatan_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                        $formId = $matches[1];
                        $existCatatan = MonitoringForm::where('monitoring_id', $monitoring)
                            ->where('form_id', $formId)
                            ->pluck('catatan', 'id')->toArray();

                        $submittedCatatans = $value;

                        if (!is_array($submittedCatatans)) {
                            return response()->json(['message' => 'Invalid input data format'], 422);
                        }

                        $submittedCatatans = array_filter($submittedCatatans);

                        $currentSubmittedCatatans = array_map(function ($catatan) {
                            return $catatan;
                        }, $submittedCatatans);

                        foreach ($existCatatan as $id => $catatan) {
                            if (!in_array($catatan, $currentSubmittedCatatans)) {
                                MonitoringForm::where('id', $id)->delete();
                            }
                        }
                        $kriteria = Kriteria::findOrFail($kriteria);

                        foreach ($submittedCatatans as $catatan) {
                            if (!in_array($catatan, $existCatatan)) {
                                MonitoringForm::updateOrCreate(
                                    [
                                        'monitoring_id' => $monitoring,
                                        'form_id' => $formId,
                                        'catatan' => $catatan,
                                    ],
                                    [
                                        'auditor_id' => $auditor,
                                        'kriteria_id' => $kriteria->id,
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
        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
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
            $sessionKey = 'form_monitoring-page_' . $i . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor;
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
