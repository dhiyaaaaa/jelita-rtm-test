<?php

namespace App\Http\Controllers\auditee\lapangan;

use App\Http\Controllers\Controller;
use App\Models\Auditee;
use App\Models\JawabanAuditor;
use App\Models\Ptk;
use App\Models\PtkForm;
use App\Models\PtkFormDeskripsi;
use App\Models\PtkFormRencana;
use App\Models\StatusPtkAuditee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
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

    public function isi_ptk(Ptk $ptk): RedirectResponse
    {
        $unit = get_type_model($ptk);

        $jawabanAuditor = JawabanAuditor::where(['jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value'], 'ptk' => 1])->first();

        if (!$jawabanAuditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam PTK');
        }

        StatusPtkAuditee::updateOrCreate(
            ['ptk_id' => $ptk->id],
            [
                'status' => 'in_progress',
            ]
        );

        return redirect()->route('auditee.lapangan.create_ptk', ['ptk' => $ptk->id]);
    }

    public function create(Request $request, Ptk $ptk): View|RedirectResponse
    {
        $unit = get_type_model($ptk);

        $jawabanAuditor = JawabanAuditor::where(['jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value'], 'ptk' => 1])->first();

        if (!$jawabanAuditor) {
            return back()->with('error', 'Belum ada instrumen yang masuk ke dalam PTK');
        }

        $auditee = Auditee::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value']])->first();

        if (!$auditee) {
            $auditee = null;
        }


        $daftarTilik = JawabanAuditor::where(['jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value']])
            ->where('ptk', 1)
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($unit, $ptk) {
                    $query->where(['jadwal_audit_id' => $ptk->jadwal_audit_id, $unit['kolom'] => $unit['value']]);
                },
                'form.link' => function ($query) use ($unit) {
                    $query->where($unit['kolom'], $unit['value']);
                },
            ])
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
        $jawabanPtkRencana = PtkFormRencana::where('ptk_id', $ptk->id)->get();

        $sessionFormData = $auditee ? session()->get('form_ptk_auditee-page_' . $currentPage . '-ptkId_' . $ptk->id . '-auditeeId_' . $auditee->id, []) : [];

        $status = StatusPtkAuditee::where('ptk_id', $ptk->id)->first();

        $data = [
            'title' => 'Temuan Negatif',
            'daftarTilik' => $paginatedDaftarTilik,
            'jadwalId' => $ptk->jadwal_audit_id,
            'ptkId' => $ptk->id,
            'auditee' => $auditee,
            'status' => $status,
            'expired' => $ptk->jadwal_audit->expired,
            'jawabanPtk' => $jawabanPtk,
            'jawabanPtkRencana' => $jawabanPtkRencana,
            'jawabanPtkDeskripsi' => $jawabanPtkDeskripsi,
            'sessionFormData' => $sessionFormData,
        ];

        return view('auditee.lapangan.create', $data);
    }

    public function save(Request $request, string $ptk, string $auditee): JsonResponse
    {
        if ($request->ajax()) {
            try {
                if ($auditee == 'null') {
                    return response()->json(['message' => 'Anda belum ditambahkan sebagai auditan sehingga tidak bisa mengisi form'], 403);
                }

                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'target_') === 0) {
                        $id = substr($key, strlen('target_'));
                        $targetKey = 'target_' . $id;
                        $picKey = 'pic_' . $id;

                        $data = [
                            'auditee_id' => $auditee,
                            'target' => $request->input($targetKey, null),
                            'pic' => $request->input($picKey, null),
                        ];

                        PtkForm::updateOrCreate(
                            [
                                'ptk_id' => $ptk,
                                'form_id' => $id,
                            ],
                            $data
                        );
                    }

                    if (preg_match('/^rencana_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                        $formId = $matches[1];
                        $existRencana = PtkFormRencana::where('ptk_id', $ptk)
                            ->where('form_id', $formId)
                            ->pluck('rencana', 'id')->toArray();
                        // Log::info('ID: ', $existDeskripsi);

                        $submittedRencanas = $value;
                        // Log::info('Submitted Deskripsi: ', $submittedRencanas);

                        if (!is_array($submittedRencanas)) {
                            return response()->json(['message' => 'Invalid input data format'], 422);
                        }

                        $submittedRencanas = array_filter($submittedRencanas);

                        $currentSubmittedRencanas = array_map(function ($deskripsi) {
                            return $deskripsi;
                        }, $submittedRencanas);
                        // Log::info('ID: ', $currentSubmittedDeskripsis);

                        foreach ($existRencana as $id => $rencana) {
                            if (!in_array($rencana, $currentSubmittedRencanas)) {
                                PtkFormRencana::where('id', $id)->delete();
                            }
                        }

                        foreach ($submittedRencanas as $rencana) {
                            if (!in_array($rencana, $existRencana)) {
                                PtkFormRencana::create(
                                    [
                                        'ptk_id' => $ptk,
                                        'form_id' => $formId,
                                        'rencana' => $rencana,
                                        'auditee_id' => $auditee,
                                        'rencana' => $rencana,
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

    // Save PTK per nomor
    public function save_per_nomor(Request $request, string $ptk, string $auditee, string $formId) {}

    public function store(Request $request, string $ptk, string $auditee): RedirectResponse
    {
        if ($auditee == 'null') {
            return redirect()->route('auditee.lapangan.create_ptk', ['ptk' => $ptk])->with('error_message', 'Anda belum ditambahkan sebagai auditan sehingga tidak bisa mengisi form');
        }

        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = 'form_ptk_auditee-page_' . $i . '-ptkId_' . $ptk . '-auditeeId_' . $auditee;
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

                if (strpos($key, 'target_') === 0 || strpos($key, 'pic_') === 0) {

                    if (strpos($key, 'target_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Target harus diisi';
                    }

                    if (strpos($key, 'pic_') === 0) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'PIC harus diisi';
                    }

                    if (!isset($value) || $value === '') {
                        $missingFields[] = $key;
                        ++$nullCount;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                } elseif (strpos($key, 'rencana_') === 0) {
                    if (is_array($value) && (empty($value) || $value[0] === null || $value[0] === '')) {
                        foreach ($value as $rencanaKey => $rencanaValue) {
                            $rules[$key . '.' . $rencanaKey] = 'required';
                            $messages[$key . '.' . $rencanaKey . '.required'] = 'Rencana harus diisi.';
                            $missingFields[] = $key . '.' . $rencanaKey;
                        }
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

            return redirect()->route('auditee.lapangan.create_ptk', ['ptk' => $ptk, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;

                return redirect()->route('auditee.lapangan.create_ptk', ['ptk' => $ptk, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'target_') === 0) {
                $id = substr($key, strlen('target_'));
                $targetKey = 'target_' . $id;
                $picKey = 'pic_' . $id;

                $data = [
                    'auditee_id' => $auditee,
                    'target' => $requestData[$targetKey] ?? null,
                    'pic' => $requestData[$picKey] ?? null,
                ];

                PtkForm::updateOrCreate(
                    [
                        'ptk_id' => $ptk,
                        'form_id' => $id,
                    ],
                    $data
                );

                if (array_key_exists('rencana_' . $id, $requestData)) {
                    $rencanaKey = 'rencana_' . $id;
                    $existRencana = PtkFormRencana::where('ptk_id', $ptk)
                        ->where('form_id', $id)
                        ->pluck('rencana', 'id')->toArray();
                    // Log::info('ID: ', $existDeskripsi);

                    $submittedRencanas = $requestData['rencana_' . $id];
                    // Log::info('Submitted Deskripsi: ', $submittedRencanas);

                    if (!is_array($submittedRencanas)) {
                        return response()->json(['message' => 'Invalid input data format'], 422);
                    }

                    $submittedRencanas = array_filter($submittedRencanas);

                    $currentSubmittedRencanas = array_map(function ($deskripsi) {
                        return $deskripsi;
                    }, $submittedRencanas);
                    // Log::info('ID: ', $currentSubmittedDeskripsis);

                    foreach ($existRencana as $jawabanId => $rencana) {
                        if (!in_array($rencana, $currentSubmittedRencanas)) {
                            PtkFormRencana::where('id', $jawabanId)->delete();
                        }
                    }

                    foreach ($submittedRencanas as $rencana) {
                        if (!in_array($rencana, $existRencana)) {
                            PtkFormRencana::create(
                                [
                                    'ptk_id' => $ptk,
                                    'form_id' => $id,
                                    // 'rencana' => $rencana,
                                    'auditee_id' => $auditee,
                                    'rencana' => $rencana,
                                ]
                            );
                        }
                    }
                }
            }
        }

        if ($requestData['final'] == 'final') {
            $status = StatusPtkAuditee::where('ptk_id', $ptk)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditee.lapangan')
            ->with('success', 'Data berhasil disimpan.');
    }
}
