<?php

namespace App\Http\Controllers\auditor\rtl;

use App\Http\Controllers\Controller;
use App\Http\Requests\MonitoringAuditorStoreRequest;
use App\Models\Auditor;
use App\Models\Auditee;
use App\Models\JawabanAuditor;
use App\Models\AuditeeAuditor;
use App\Models\Monitoring;
use App\Models\MonitoringAuditee;
use App\Models\MonitoringForm;
use App\Models\StatusMonitoring;
use App\Models\JadwalAudit;
use App\Models\Kriteria;
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
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
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
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
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
    public function isi_monitoring(string $monitoring): RedirectResponse
    {
        StatusMonitoring::updateOrCreate(
            ['monitoring_id' => $monitoring],
            ['status' => 'in_progress']
        );

        return redirect()->route('auditor.tindak-lanjut.form', [
            'monitoring' => $monitoring,
        ]);
    }

    public function form(Request $request, Monitoring $monitoring): View|RedirectResponse
    {
        $unit = get_type_model($monitoring);

        $auditor = Auditor::where([
            'jadwal_audit_id' => $monitoring->jadwal_audit_id,
            'user_id' => $this->user->id
        ])->first();

        $jadwal = JadwalAudit::findOrFail($monitoring->jadwal_audit_id);

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $monitoring->jadwal_audit_id, $unit['kolom'] => $unit['value']])
            ->first();


        $kriteriaBelumMemenuhi = Kriteria::where('slug', 'belum-memenuhi')->pluck('id')->first();
        $kriteria = [$kriteriaBelumMemenuhi];

        $temuanNegatif = JawabanAuditor::where('jadwal_audit_id', $monitoring->jadwal_audit_id)
            ->whereIn('kriteria_id', $kriteria)
            ->where($unit['kolom'], $unit['value'])
            ->with([
                'form.instrumen',
                'form.rtm_tindak_lanjut',
                'form.ptk_form',
                'form.rtl_form',
            ])->get();



        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $temuanNegatif = new LengthAwarePaginator(
            $temuanNegatif->forPage($currentPage, $perPage),
            $temuanNegatif->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $isianRtl = MonitoringForm::where('monitoring_id', $monitoring->id)->get();

        $sessionFormData = session()->get('form_monitoring-page_' . $currentPage . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor->id, []);
        $status = StatusMonitoring::where('monitoring_id', $monitoring->id)->first();

        $data = [
            'title' => 'Monitoring Tindak Lanjut Atas PTK',
            'temuanNegatif' => $temuanNegatif,
            'monitoring' => $monitoring,
            'jadwal' => $jadwal,
            'auditor' => $auditor,
            'status' => $status,
            'isianRtl' => $isianRtl,
            'sessionFormData' => $sessionFormData,
            'expired' => $jadwal->expired,

        ];
        return view('auditor.tindak_lanjut.form', $data);
    }

    public function save_form(Request $request, string $monitoring, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'status_') === 0) {
                        $id = substr($key, strlen('status_'));
                        $statusKey = 'status_' . $id;
                        $catatanKey = 'catatan_' . $id;
                        $status = $request->input($statusKey);

                        if (!in_array($status, ['selesai', 'tidak_selesai', null], true)) {
                            return response()->json(['message' => 'Harap periksa status tindak lanjut terlebih dahulu.'], 422);
                        }

                        $kriteria = [
                            'monitoring_id' => $monitoring,
                            'form_id' => $id,
                        ];

                        $data = [
                            'auditor_id' => $auditor,
                            'status' => $status,
                            'catatan' => $request->input($catatanKey, null),
                        ];

                        MonitoringForm::updateOrCreate($data, $kriteria);
                    }
                }

                $reponse['message'] = 'Monitoring tindak lanjut berhasil disimpan.';

                return response()->json($reponse);
            } catch (\Exception $e) {
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

    public function store_form(Request $request, Monitoring $monitoring, string $auditor): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        // Ambil data dari semua halaman yang tersimpan di session
        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_monitoring_auditor-page_' . $i . '-monitoringId_' . $monitoring->id . '-auditorId_' . $auditor;
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
                if (strpos($key, 'catatan_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    if (strpos($key, 'catatan_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Komentar Auditor PTK harus diisi';
                    }

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

        $existingForms = MonitoringForm::where('monitoring_id', $monitoring->id)->pluck('form_id')->toArray();
        $newFormIds = [];

        // Simpan semua data ke database
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'status_') === 0) {
                $id = substr($key, strlen('status_'));
                $newFormIds[] = $id;
                $catatanKey = 'catatan_' . $id;

                $data = [
                    'monitoring_id' => $monitoring->id,
                    'form_id' => $id,
                    'auditor_id' => $auditor,
                    'status' => $requestData[$statusKey] ?? null,

                ];

                MonitoringForm::updateOrCreate(
                    [
                        'monitoring_id' => $monitoring->id,
                        'form_id' => $id
                    ],
                    $data
                );
            }
        }

        // Update status  jika semua halaman telah diisi
        if ($request->input('final') === 'final') {
            $status = StatusMonitoring::where('monitoring_id', $monitoring->id)->first();
            if ($status && $status->status === 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditor.tindak-lanjut.show', $monitoring->jadwal_audit_id)
            ->with('success', 'Data berhasil disimpan.');
    }
}
