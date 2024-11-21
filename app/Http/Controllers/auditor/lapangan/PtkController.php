<?php

namespace App\Http\Controllers\auditor\lapangan;

use App\Http\Controllers\Controller;
use App\Http\Requests\PtkAuditorStoreRequest;
use App\Models\Auditee;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditor;
use App\Models\Ptk;
use App\Models\PtkAuditee;
use App\Models\PtkForm;
use App\Models\PtkFormDeskripsi;
use App\Models\PtkFormRencana;
use App\Models\StatusPtkAuditor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PtkController extends Controller
{

    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $jadwalAudit, string $unit, string $type): View
    {
        $auditees = Auditee::where(['jadwal_audit_id' => $jadwalAudit, get_type($type) => $unit])->get();

        $data = [
            'title' => 'Tambah Temuan Negatif',
            'auditees' => $auditees,
            'jadwalId' => $jadwalAudit,
            'unitId' => $unit,
            'type' => $type,
        ];

        return view('auditor.lapangan.ptk.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PtkAuditorStoreRequest $request, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $jadwalAudit)
            ->distinct()
            ->where(get_type($type), $unit)
            ->orderBy('created_at', 'asc')
            ->get();

        $ptk = Ptk::updateOrCreate([
            'jadwal_audit_id' => $jadwalAudit,
            'tgl' => $request->tgl,
            get_type($type) => $unit
        ]);

        $ptk->auditee()->attach($request->auditee);

        $timestamp = now();

        foreach ($auditors as $auditor) {
            $ptk->auditor()->attach($auditor->auditor_id, [
                'created_at' => $timestamp->addMilliseconds(5000)
            ]);
        }

        return redirect()->route('auditor.lapangan.show', ['jadwalAudit' => $jadwalAudit])
            ->with('success', 'Temuan Negatif berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ptk $ptk): View
    {
        $unit = get_type_model($ptk);

        $auditees = Auditee::where(['jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value']])->get();

        $auditeeSelected = PtkAuditee::where('ptk_id', $ptk->id)->pluck('auditee_id');

        $data = [
            'title' => 'Edit Temuan Negatif',
            'auditees' => $auditees,
            'auditeeSelected' => $auditeeSelected,
            'ptk' => $ptk,
        ];

        return view('auditor.lapangan.ptk.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PtkAuditorStoreRequest $request, Ptk $ptk): RedirectResponse
    {
        $ptk->update([
            'tgl' => $request->tgl,
        ]);

        // One to One
        $auditee = PtkAuditee::where('ptk_id', $ptk->id)->first();

        if ($auditee->auditee_id !== $request->auditee) {
            $auditee->update([
                'auditee_id' => $request->auditee,
                'approve' => 0,
            ]);
        }

        // Auditor
        $unit = get_type_model($ptk);
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $ptk->jadwal_audit_id)
            ->distinct()
            ->where($unit['kolom'], $unit['value'])
            ->orderBy('created_at', 'asc')
            ->pluck('auditor_id');

        $existingAuditors = $ptk->auditor()->orderByPivot('created_at', 'asc')->pluck('auditor_id');

        if ($auditors->toArray() !== $existingAuditors->toArray()) {
            $timestamp = now();

            foreach ($auditors as $index => $auditorId) {
                $ptk->auditor()->updateExistingPivot($auditorId, [
                    'created_at' => $timestamp->addMilliseconds(5000),
                ]);
            }
        }

        return redirect()->route('auditor.lapangan.show', [
            'jadwalAudit' => $ptk->jadwal_audit_id,
        ])
            ->with('success', 'Temuan Negatif berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ptk $ptk): RedirectResponse
    {
        $ptk->delete();

        return back()->with('success', 'Temuan Negatif berhasil dihapus.');
    }

    // Status Isi PTK
    public function isi_ptk(string $ptk)
    {
        StatusPtkAuditor::updateOrCreate(
            ['ptk_id' => $ptk],
            ['status' => 'in_progress']
        );

        return redirect()->route('auditor.lapangan.ptk.form', [
            'ptk' => $ptk,
        ]);
    }

    // Form PTK
    public function form(Request $request, Ptk $ptk): View|RedirectResponse
    {
        $unit = get_type_model($ptk);

        $jadwal = JadwalAudit::findOrFail($ptk->jadwal_audit_id);

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $jadwal->id, $unit['kolom'] => $unit['value'], 'ptk' => 1])->first();
        $jawaban_auditor_all = JawabanAuditor::where(['jadwal_audit_id' => $jadwal->id, $unit['kolom'] => $unit['value'], 'ptk' => 1])->get();

        if (!$jawaban_auditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam Temuan Negatif.');
        }

        $auditor = Auditor::where(['jadwal_audit_id' => $ptk->jadwal_audit_id, 'user_id' => $this->user->id])->first();
        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $daftarTilik = JawabanAuditor::where('jadwal_audit_id', $ptk->jadwal_audit_id)
            ->where('ptk', 1)
            ->where($unit['kolom'], $unit['value'])
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($unit, $ptk) {
                    $query->where('jadwal_audit_id', $ptk->jadwal_audit_id);
                    $query->where($unit['kolom'], $unit['value']);
                },
                'form.link' => function ($query) use ($unit) {
                    $query->where($unit['kolom'], $unit['value']);
                }
            ])
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->orderBy('instrumen.standar_id', 'asc')
            ->orderBy('instrumen.kategori_id', 'asc')
            ->orderBy($order_by_kode)
            ->select('jawaban_auditor.*')
            ->get();

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $paginatedDaftarTilik = new LengthAwarePaginator(
            $daftarTilik->forPage($currentPage, $perPage),
            $daftarTilik->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $jawabanPtk = PtkForm::where('ptk_id', $ptk->id)->get();
        $jawabanPtkDeskripsi = PtkFormDeskripsi::where('ptk_id', $ptk->id)->get();

        $sessionFormData = session()->get('form_ptk_auditor-page_' . $currentPage . '-ptkId_' . $ptk->id . '-auditorId_' . $auditor->id, []);

        $status = StatusPtkAuditor::where('ptk_id', $ptk->id)->first();

        $data = [
            'title' => 'Temuan Negatif',
            'daftarTilik' => $paginatedDaftarTilik,
            'jadwal' => $jadwal,
            'ptkId' => $ptk->id,
            'auditor' => $auditor,
            'status' => $status,
            'jawabanPtk' => $jawabanPtk,
            'jawabanPtkDeskripsi' => $jawabanPtkDeskripsi,
            'jawabanAuditorAll' => $jawaban_auditor_all,
            'sessionFormData' => $sessionFormData,
            'expired' => $jadwal->expired,
        ];

        return view('auditor.lapangan.ptk.form', $data);
    }

    public function save_form(Request $request, string $ptk, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'analisis_') === 0) {
                        $id = substr($key, strlen('analisis_'));
                        $analisisKey = 'analisis_' . $id;
                        $akibatKey = 'akibat_' . $id;
                        $kategoriKey = 'kategori_' . $id;
                        $kategoriTemuan = $request->input($kategoriKey);

                        if (!in_array($kategoriTemuan, ['observasi', 'minor', 'mayor', null], true)) {
                            return response()->json(['message' => 'Harap isi kategori temuan terlebih dahulu.'], 422);
                        }

                        $data = [
                            'auditor_id' => $auditor,
                            'kategori_temuan' => $kategoriTemuan,
                            'analisis' => $request->input($analisisKey, null),
                            'akibat' => $request->input($akibatKey, null),
                        ];

                        PtkForm::updateOrCreate(
                            [
                                'ptk_id' => $ptk,
                                'form_id' => $id,
                            ],
                            $data
                        );
                    }

                    if (preg_match('/^deskripsi_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                        $formId = $matches[1];
                        $existDeskripsi = PtkFormDeskripsi::where('ptk_id', $ptk)
                            ->where('form_id', $formId)
                            ->pluck('deskripsi', 'id')->toArray();

                        $submittedDeskripsis = $value;

                        if (!is_array($submittedDeskripsis)) {
                            return response()->json(['message' => 'Invalid input data format'], 422);
                        }

                        $submittedDeskripsis = array_filter($submittedDeskripsis);

                        $currentSubmittedDeskripsis = array_map(function ($deskripsi) {
                            return $deskripsi;
                        }, $submittedDeskripsis);

                        foreach ($existDeskripsi as $id => $deskripsi) {
                            if (!in_array($deskripsi, $currentSubmittedDeskripsis)) {
                                PtkFormDeskripsi::where('id', $id)->delete();
                            }
                        }

                        foreach ($submittedDeskripsis as $deskripsi) {
                            if (!in_array($deskripsi, $existDeskripsi)) {
                                PtkFormDeskripsi::updateOrCreate(
                                    [
                                        'ptk_id' => $ptk,
                                        'form_id' => $formId,
                                        'deskripsi' => $deskripsi,
                                    ],
                                    [
                                        'auditor_id' => $auditor,
                                        'deskripsi' => $deskripsi,
                                    ]
                                );
                            }
                        }
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

    // Save Form per Nomor (backup)
    public function save_form_per_nomor(Request $request, string $ptk, string $auditor, string $formId) {}

    public function store_form(Request $request, Ptk $ptk, string $auditor): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_ptk_auditor-page_' . $i . '-ptkId_' . $ptk->id . '-auditorId_' . $auditor;
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

        $rules = [];
        $messages = [];
        $missingFields = [];
        $formId = [];
        $requestData = [];
        $nullCount = 0;
        $threshold = 1;

        foreach ($sessionFormData as $page => $data) {
            foreach ($data as $key => $value) {
                $requestData[$key] = $value;

                if (strpos($key, 'analisis_') === 0 || strpos($key, 'akibat_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    if (strpos($key, 'analisis_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Analisis harus diisi';
                    }

                    if (strpos($key, 'akibat_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Akibat harus diisi';
                    }

                    $kategoriKey = 'kategori_' . $id;
                    $rules[$kategoriKey] = 'required';
                    $messages[$kategoriKey . '.required'] = 'Kategori harus diisi';

                    if (!isset($value) || $value === '') {
                        $missingFields[] = $key;
                        $nullCount++;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                } elseif (strpos($key, 'deskripsi_') === 0) {
                    if (is_array($value) && (empty($value) || $value[0] === null || $value[0] === '')) {
                        foreach ($value as $deskripsiKey => $deskripsiValue) {
                            $rules[$key . '.' . $deskripsiKey] = 'required';
                            $messages[$key . '.' . $deskripsiKey . '.required'] = 'Deskripsi harus diisi.';
                            $missingFields[] = $key . '.' . $deskripsiKey;
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

            return redirect()->route('auditor.lapangan.ptk.form', ['ptk' => $ptk->id, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;
                return redirect()->route('auditor.lapangan.ptk.form', ['ptk' => $ptk->id, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        $existingForms = PtkForm::where('ptk_id', $ptk->id)->pluck('form_id')->toArray();
        $newFormIds = [];

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'analisis_') === 0) {
                $id = substr($key, strlen('analisis_'));
                $newFormIds[] = $id;
                $kategoriKey = 'kategori_' . $id;
                $analisisKey = 'analisis_' . $id;
                $akibatKey = 'akibat_' . $id;

                $data = [
                    'ptk_id' => $ptk->id,
                    'form_id' => $id,
                    'auditor_id' => $auditor,
                    'kategori_temuan' => $requestData[$kategoriKey] ?? null,
                    'analisis' => $requestData[$analisisKey] ?? null,
                    'akibat' => $requestData[$akibatKey] ?? null,
                ];

                PtkForm::updateOrCreate(
                    [
                        'ptk_id' => $ptk->id,
                        'form_id' => $id,
                    ],
                    $data
                );


                if (array_key_exists('deskripsi_' . $id, $requestData)) {
                    $existDeskripsi = PtkFormDeskripsi::where('ptk_id', $ptk->id)
                        ->where('form_id', $id)
                        ->pluck('deskripsi', 'id')->toArray();

                    $submittedDeskripsis = $requestData['deskripsi_' . $id];

                    if (!is_array($submittedDeskripsis)) {
                        return response()->json(['message' => 'Invalid input data format'], 422);
                    }

                    $submittedDeskripsis = array_filter($submittedDeskripsis);

                    $currentSubmittedDeskripsis = array_map(function ($deskripsi) {
                        return $deskripsi;
                    }, $submittedDeskripsis);

                    foreach ($existDeskripsi as $jawabanId => $deskripsi) {
                        if (!in_array($deskripsi, $currentSubmittedDeskripsis)) {
                            PtkFormDeskripsi::where('id', $jawabanId)->delete();
                        }
                    }

                    foreach ($submittedDeskripsis as $deskripsi) {
                        if (!in_array($deskripsi, $existDeskripsi)) {
                            PtkFormDeskripsi::updateOrCreate(
                                [
                                    'ptk_id' => $ptk->id,
                                    'form_id' => $id,
                                    'deskripsi' => $deskripsi,
                                ],
                                [
                                    'auditor_id' => $auditor,
                                    'deskripsi' => $deskripsi,
                                ]
                            );
                        }
                    }
                }
            }
        }

        $formsToDelete = array_diff($existingForms, $newFormIds);
        PtkForm::where('ptk_id', $ptk->id)
            ->whereIn('form_id', $formsToDelete)
            ->delete();

        if ($requestData['final'] == 'final') {
            $status = StatusPtkAuditor::where('ptk_id', $ptk->id)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditor.lapangan.show', $ptk->jadwal_audit_id)
            ->with('success', 'Data berhasil disimpan.');
    }

    // Hapus Instrumen dari PTK
    public function hapus_instrumen(Request $request, string $ptk, string $form): JsonResponse
    {
        if ($request->ajax()) {
            $response = [];

            try {
                $temuan_negatif = Ptk::findOrFail($ptk);
                $unit = get_type_model($temuan_negatif);

                $jawaban = JawabanAuditor::where('jadwal_audit_id', $temuan_negatif->jadwal_audit_id)
                    ->where($unit['kolom'], $unit['value'])
                    ->where('form_id', $form)
                    ->first();

                if ($jawaban) {
                    $jawaban->update(['ptk' => 0]);

                    PtkForm::where(['ptk_id' => $temuan_negatif->id, 'form_id' => $form])->delete();

                    PtkFormDeskripsi::where(['ptk_id' => $temuan_negatif->id, 'form_id' => $form])->delete();

                    PtkFormRencana::where(['ptk_id' => $temuan_negatif->id, 'form_id' => $form])->delete();
                } else {
                    throw new \Exception('PTK tidak ditemukan');
                }

                $response['message'] = 'Instrumen berhasil dihapus dari Temuan Negatif!';
                return response()->json($response);
            } catch (\Exception $e) {

                $response['message'] = 'Gagal menghapus Instrumen!';

                return response()->json($response, 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }
}
