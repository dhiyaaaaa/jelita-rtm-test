<?php

namespace App\Http\Controllers\auditor\lapangan;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanAuditorStoreRequest;
use App\Models\Auditee;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\JawabanAuditor;
use App\Models\Kriteria;
use App\Models\Laporan;
use App\Models\LaporanAuditee;
use App\Models\LaporanForm;
use App\Models\StatusLaporan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LaporanController extends Controller
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
            'title' => 'Tambah Temuan Positif',
            'auditees' => $auditees,
            'jadwalId' => $jadwalAudit,
            'unitId' => $unit,
            'type' => $type,
        ];

        return view('auditor.lapangan.laporan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LaporanAuditorStoreRequest $request, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $jadwalAudit)
            ->distinct()
            ->where(get_type($type), $unit)
            ->orderBy('created_at', 'asc')
            ->get();

        $laporan = Laporan::updateOrCreate([
            'jadwal_audit_id' => $jadwalAudit,
            'tgl' => $request->tgl,
            get_type($type) => $unit
        ]);

        $laporan->auditee()->attach($request->auditee);

        $timestamp = now();

        foreach ($auditors as $auditor) {
            $laporan->auditor()->attach($auditor->auditor_id, [
                'created_at' => $timestamp->addMilliseconds(5000)
            ]);
        }

        return redirect()->route('auditor.lapangan.show', ['jadwalAudit' => $jadwalAudit])
            ->with('success', 'Temuan Positif berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laporan $laporan): View
    {
        $unit = get_type_model($laporan);

        $auditees = Auditee::where(['jadwal_audit_id' => $laporan->jadwal_audit_id, $unit['kolom'] => $unit['value']])->get();

        $auditeeSelected = LaporanAuditee::where('laporan_id', $laporan->id)->pluck('auditee_id');

        $data = [
            'title' => 'Edit Temuan Positif',
            'auditees' => $auditees,
            'auditeeSelected' => $auditeeSelected,
            'laporan' => $laporan,
        ];

        return view('auditor.lapangan.laporan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LaporanAuditorStoreRequest $request, Laporan $laporan): RedirectResponse
    {
        $laporan->update([
            'tgl' => $request->tgl,
        ]);

        // One to One
        $auditee = LaporanAuditee::where('laporan_id', $laporan->id)->first();

        if ($auditee) {
            if ($auditee->auditee_id !== $request->auditee) {
                $auditee->update([
                    'auditee_id' => $request->auditee,
                    'approve' => 0,
                ]);
            }
        } else {
            LaporanAuditee::create([
                'laporan_id' => $laporan->id,
                'auditee_id' => $request->auditee,
                'approve' => 0,
            ]);
        }

        // Auditor
        $unit = get_type_model($laporan);
        $auditors = AuditeeAuditor::select('auditor_id', 'created_at')
            ->where('jadwal_audit_id', $laporan->jadwal_audit_id)
            ->distinct()
            ->where($unit['kolom'], $unit['value'])
            ->orderBy('created_at', 'asc')
            ->pluck('auditor_id');

        $existingAuditors = $laporan->auditor()->orderByPivot('created_at', 'asc')->pluck('auditor_id');

        if ($auditors->toArray() !== $existingAuditors->toArray()) {
            $timestamp = now();

            foreach ($auditors as $index => $auditorId) {
                $laporan->auditor()->updateExistingPivot($auditorId, [
                    'created_at' => $timestamp->addMilliseconds(5000),
                ]);
            }
        }

        return redirect()->route('auditor.lapangan.show', [
            'jadwalAudit' => $laporan->jadwal_audit_id,
        ])
            ->with('success', 'Temuan Positif berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan): RedirectResponse
    {
        $laporan->delete();

        return back()->with('success', 'Temuan Positif berhasil dihapus.');
    }

    // Isi Laporan
    public function isi_laporan(string $laporan): RedirectResponse
    {
        StatusLaporan::updateOrCreate(
            ['laporan_id' => $laporan],
            ['status' => 'in_progress']
        );

        return redirect()->route('auditor.lapangan.laporan.form', [
            'laporan' => $laporan,
        ]);
    }

    // Form Laporan Hal Hal Positif
    public function form(Request $request, Laporan $laporan): View|RedirectResponse
    {
        $unit = get_type_model($laporan);

        $auditor = Auditor::where(['jadwal_audit_id' => $laporan->jadwal_audit_id, 'user_id' => $this->user->id])->first();

        $kriteriaMemenuhi = Kriteria::where('slug', 'memenuhi')->pluck('id')->first();
        $kriteriaMelampaui = Kriteria::where('slug', 'melampaui')->pluck('id')->first();
        $kriteria = [$kriteriaMelampaui, $kriteriaMemenuhi];

        // Cek jika kosong
        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $laporan->jadwal_audit_id, $unit['kolom'] => $unit['value']])
            ->first();
        $jawaban_auditor_all = JawabanAuditor::where(['jadwal_audit_id' => $laporan->jadwal_audit_id, $unit['kolom'] => $unit['value']])
            ->get();


        if (!$jawaban_auditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam Temuan Positif.');
        }

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $forms = JawabanAuditor::where('jadwal_audit_id', $laporan->jadwal_audit_id)
            ->where('daftar_tilik', 0)
            ->whereIn('kriteria_id', $kriteria)
            ->where($unit['kolom'], $unit['value'])
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($laporan, $unit) {
                    $query->where('jadwal_audit_id', $laporan->jadwal_audit_id);
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
        $paginatedForms = new LengthAwarePaginator(
            $forms->forPage($currentPage, $perPage),
            $forms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );

        $jawabanLaporan = LaporanForm::where('laporan_id', $laporan->id)->get();

        $sessionFormData = session()->get('form_laporan-page_' .  $currentPage . '-laporanId_' . $laporan->id  . '-auditorId_' . $auditor->id);

        $status = StatusLaporan::where('laporan_id', $laporan->id)->first();

        $data = [
            'title' => 'Temuan Positif',
            'paginatedForms' => $paginatedForms,
            'laporan' => $laporan,
            'auditor' => $auditor,
            'status' => $status,
            'jadwal' => $laporan->jadwal_audit_id,
            'expired' => $laporan->jadwal_audit->expired,
            'jawabanLaporan' => $jawabanLaporan,
            'sessionFormData' => $sessionFormData,
            'jawabanAuditorAll' => $jawaban_auditor_all
        ];

        return view('auditor.lapangan.laporan.form', $data);
    }

    public function save_form(Request $request, string $laporan, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'kelebihan_') === 0) {
                        $id = substr($key, strlen('kelebihan_'));
                        $kelebihanKey = 'kelebihan_' . $id;
                        $ruangKey = 'ruang_' . $id;

                        $data = [
                            'auditor_id' => $auditor,
                            'kelebihan' => $request->input($kelebihanKey, null),
                            'ruang_peningkatan' => $request->input($ruangKey, null),
                        ];

                        $kriteria = [
                            'laporan_id' => $laporan,
                            'form_id' => $id,
                        ];


                        LaporanForm::updateOrCreate($kriteria, $data);
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

    // Save per nomor (backup)
    public function save_form_per_nomor(Request $request, string $laporan, string $auditor, string $formId) {}

    public function store_form(Request $request, Laporan $laporan, string $auditor): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_laporan-page_' . $i . '-laporanId_' . $laporan->id . '-auditorId_' . $auditor;
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
                if (strpos($key, 'kelebihan_') === 0 || strpos($key, 'ruang_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    if (strpos($key, 'kelebihan_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Kelebihan harus diisi';
                    }

                    if (strpos($key, 'ruang_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Ruang Peningkatan harus diisi';
                    }

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

            return redirect()->route('auditor.lapangan.laporan.form', ['laporan' => $laporan->id, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;
                return redirect()->route('auditor.lapangan.laporan.form', ['laporan' => $laporan->id, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        $existingForms = LaporanForm::where('laporan_id', $laporan->id)
            ->pluck('form_id')->toArray();
        $newFormIds = [];

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'kelebihan_') === 0 || strpos($key, 'ruang_') === 0) {
                $id = substr($key, strpos($key, '_') + 1);
                $newFormIds[] = $id;

                $data = [
                    'auditor_id' => $auditor,
                    'kelebihan' => $requestData['kelebihan_' . $id] ?? null,
                    'ruang_peningkatan' => $requestData['ruang_' . $id] ?? null,
                ];

                $criteria = [
                    'laporan_id' => $laporan->id,
                    'form_id' => $id,
                ];

                LaporanForm::updateOrCreate($criteria, $data);
            }
        }


        $formsToDelete = array_diff($existingForms, $newFormIds);
        LaporanForm::where('laporan_id', $laporan->jadwal_audit_id)
            ->whereIn('form_id', $formsToDelete)
            ->delete();

        if ($requestData['final'] == 'final') {
            $status = StatusLaporan::where('laporan_id', $laporan->id)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditor.lapangan.show', $laporan->jadwal_audit_id)
            ->with('success', 'Data berhasil disimpan.');
    }
}
