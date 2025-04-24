<?php

namespace App\Http\Controllers\dekan;

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
use App\Models\JawabanAuditor;
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
    protected $jabatanUser;
    protected $auditee;
    protected $isDisabled;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
        $this->auditee = Auditee::where(['user_id' => $this->user->id])->first();
        $this->isDisabled = !$this->auditee;
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:rtm_jadwal,id',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'unit_id' => 'nullable|exists:unit,id',
            'jadwal_audit_id' => 'required|exists:jadwal_audit,id',
        ]);
        
        RtmRtl::updateOrCreate([
            'rtm_jadwal_id' => $request->jadwal_id,
            'fakultas_id' => $request->fakultas_id ?? Auth::user()->fakultas_id,
            'unit_id' => $request->unit_id ?? Auth::user()->unit_id,
            'jadwal_audit_id' => $request->jadwal_audit_id,
            'tgl' => Carbon::now()->toDateString(),
        ]);

        return response()->json(['success' => 'Tindak lanjut berhasil ditambahkan.']);
    }

    public function show(RtmRtl $rtmRtl)
    {
        $auditee = Auditee::where(['user_id' => $this->user->id])->first();
        $rtmRtl = RtmRtl::with ([
            'status_rtm_rtl.kriteria',
            'status_rtm_rtl_prodi',
            'jadwal_audit',
            'rtm_jadwal',
        ])->findOrFail($rtmRtl->id);

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit' . ' ' . $rtmRtl->jadwal_audit->jadwal,
            'rtmRtl' => $rtmRtl,
            'auditee' => $auditee,
        ];

        return view('dekan.rtm.rtm_rtl.show', $data);
    }

    public function isi_rtm_rtl(RtmRtl $rtmRtl, string $kriteria): RedirectResponse
    {
        $kriteriaTindakan = Kriteria::where('nama', $kriteria)->first();
        
        $kriteriaId = $kriteriaTindakan->id;

        $StatusRtmRtl = StatusRtmRtl::updateOrCreate(
            [
                'rtm_rtl_id' => $rtmRtl->id,
                'kriteria_id' => $kriteriaId,
            ],
            ['status' => 'in_progress']
        );
        Log::info('Kriteria yang dikirim:', ['kriteria' => $kriteria]);
        return redirect()->route('dekan.rtm-rtl.form', [
            'rtmRtl' => $rtmRtl, 
            'kriteria' => $kriteria
        ]);
    }
    

    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);

        $fakultas = $rtmRtl->fakultas_id ? Fakultas::find($rtmRtl->fakultas->id) : null;
        $unit = $rtmRtl->unit_id ? Unit::find($rtmRtl->unit_id) : null;

        $jabatanUserId = optional($this->user->jabatan->first())->id;
        $jabatanUser = Jabatan::find($jabatanUserId);
        $auditee = $this->auditee;

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $kriteria = $request->query('kriteria');
        $kriteriaTemuan = ['Belum Memenuhi', 'Memenuhi', 'Melampaui'];

        // Validasi kriteria
        if (!in_array($kriteria, $kriteriaTemuan)) {
            return back()->with('error', 'Tidak ada temuan dengan kriteria ini.');
        }

        // Ambil ID kriteria berdasarkan nama
        $kriteriaId = Kriteria::where('nama', $kriteria)->pluck('id')->first();

        $temuanFakultas = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->where('kriteria_id', $kriteriaId)
            ->when($rtmRtl->fakultas_id, function ($query) use ($rtmRtl) {
                return $query->where('fakultas_id', $rtmRtl->fakultas_id);
            })
            ->when($rtmRtl->unit_id, function ($query) use ($rtmRtl) {
                return $query->where('unit_id', $rtmRtl->unit_id);
            })
            ->with([
                'form.instrumen.jabatan',
                'form.jawaban_auditee',
                'form.ptk_form_deskripsi',
                'form.laporan_form',
                'kriteria',
            ])
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->orderBy('kriteria_id', 'asc')
            ->orderBy('form.instrumen_id', 'asc')
            ->get();

        $perPage = 10;
        $currentPage = $request->query('page', 1);

        $paginatedTemuanFakultas = new LengthAwarePaginator(
            $temuanFakultas->forPage($currentPage, $perPage),
            $temuanFakultas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($temuanFakultas->isEmpty()) {
            return back()->with('error', 'Tidak ada temuan untuk ditindaklanjuti.');
        }
        
        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->get();

        $auditeeId = $auditee ? $auditee->id : 'no_auditee';

        //PIC data 
        $picData = DB::table('jabatan_user')
        ->join('jabatan', 'jabatan_user.jabatan_id', '=', 'jabatan.id')
        ->join('users', 'jabatan_user.user_id', '=', 'users.id')
        ->leftJoin('prodi', 'jabatan_user.prodi_id', '=', 'prodi.id')
        ->leftJoin('fakultas', 'jabatan_user.fakultas_id', '=', 'fakultas.id')
        ->leftJoin('unit', 'jabatan_user.unit_id', '=', 'unit.id')
        ->where(function ($query) use ($fakultas, $unit) {
            if ($fakultas) {
                $query->where(function ($q) use ($fakultas) {
                    $q->where('prodi.fakultas_id', $fakultas->id)
                    ->orWhere('jabatan_user.fakultas_id', $fakultas->id);
                });
                $query->orWhereNotNull('jabatan_user.unit_id');
            } elseif ($unit) {
                $query->whereNotNull('jabatan_user.unit_id');
            }
        })
        ->select(
            'jabatan_user.jabatan_id',
            'jabatan.nama as jabatan_nama',
            'jabatan.type',
            'users.name as user_name',
            'users.id as user_id',
            'prodi.nama as prodi_nama',
            'fakultas.nama as fakultas_nama',
            'unit.nama as unit_nama'
        )
        ->get();
        
        // Ambil data dari session
        $sessionKey = 'form_rtm_rtl-page_' . $request->query('page', 1) . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditeeId;
        $sessionFormData = session()->get($sessionKey, []);
        Log::info('RTM controller session data', $sessionFormData);

        $formData = [];
        
        foreach ($jawabanTindakLanjut as $jawaban) {
            if (!isset($formData[$jawaban->form_id])) {
                $formData[$jawaban->form_id] = [
                    'tindakan' => [],
                ];
            }
            $formData[$jawaban->form_id]['tindakan'][] = [
                'tindakan' => $jawaban->tindakan,
                'pic' => $jawaban->user_id . '|' . $jawaban->jabatan_id,
                'waktu' => $jawaban->waktu,
            ];
        }
        if (!empty($sessionFormData)) {
            $formData = array_merge($formData, $sessionFormData);
        }
        
        if (!empty($sessionFormData)) {
            $formData = array_merge($formData, $sessionFormData);
        }

        $kriteriaTindakan = Kriteria::where('nama', $kriteria)->first();

        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)
        ->where('kriteria_id', $kriteriaTindakan->id)
        ->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $formData,
            'kriteriaTemuan' => $kriteria,
            'kriteria' => $kriteriaTindakan,
            'kriteriaId' => $kriteriaId,
            'status' => $status,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'auditeeId' => $auditeeId,
            'auditee' => $auditee,
            'temuanFakultas' => $temuanFakultas,
            'picData' => $picData,
            'jawabanAuditor' => $jawaban_auditor,
            'isDisabled' => !$auditee,
        ];

        return view('dekan.rtm.rtm_rtl.form', $data);
    }

    public function save_form(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            $currentPage = $request->input('currentPage', 1);
            $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;
            
            // Initialize validation structures
            $errors = [];
            $hasData = false;
            $validationRules = [];
            $validationMessages = [];

            // Build dynamic validation rules based on request data
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    
                    foreach ($value as $index => $item) {
                        $validationRules["$key.$index.tindakan"] = 'required';
                        $validationRules["$key.$index.pic"] = ['required', 'regex:/^.+\|.+$/'];
                        $validationRules["$key.$index.waktu"] = 'required';
                        
                        $validationMessages["$key.$index.tindakan.required"] = 'Rencana tindakan harus diisi';
                        $validationMessages["$key.$index.pic.required"] = 'PIC harus dipilih';
                        $validationMessages["$key.$index.pic.regex"] = 'Format PIC tidak valid';
                        $validationMessages["$key.$index.waktu.required"] = 'Target waktu harus diisi';
                    }
                }
            }

            // Validate the request
            $validator = Validator::make($request->all(), $validationRules, $validationMessages);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Process the data if validation passes
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    $hasData = true;

                    $kriteria = Kriteria::whereHas('jawaban_auditor', function ($query) use ($formId) {
                        $query->where('form_id', $formId);
                    })->first();

                    $auditee = Auditee::where('user_id', $this->user->id)->first();
                    $auditeeId = $auditee ? $auditee->id : null;

                    $existingData = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl)
                        ->where('form_id', $formId)
                        ->get();

                    $submittedData = collect($value)->filter(fn($item) => 
                        !empty($item['tindakan']) && !empty($item['pic']) && !empty($item['waktu'])
                    );

                    // Delete old data not in new submission
                    $toDelete = $existingData->filter(function($item) use ($submittedData) {
                        return ! $submittedData->contains('tindakan', $item->tindakan);
                    });

                    foreach($toDelete as $item) {
                        $item->delete();
                    }

                    // Save/update data
                    foreach ($submittedData as $item) {
                        [$picUserId, $picJabatanId] = explode('|', $item['pic']);

                        RtmTindakLanjut::updateOrCreate(
                            [
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                                'tindakan' => $item['tindakan'],
                            ],
                            [
                                'user_id' => $picUserId,
                                'jabatan_id' => $picJabatanId,
                                'waktu' => $item['waktu'],
                                'kriteria_id' => $kriteria->id,
                                'auditee_id' => $auditeeId
                            ]
                        );
                    }
                }
            }

            if (!$hasData) {
                return response()->json([
                    'message' => 'Tidak ada data yang valid untuk disimpan.'
                ], 422);
            }

            session()->forget($sessionKey);
            return response()->json(['message' => 'Data berhasil disimpan.'], 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $kriteriaId = $request->input('kriteria');
        $auditeeId = $request->input('auditee');
        $allData = [];
        $rules = [];
        $messages = [];
        $missingFields = [];

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_rtm_rtl-page_' . $i . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;
            $pageData = session($sessionKey, []);

            foreach ($pageData as $formId => $formData) {
                if (!isset($allData[$formId])) {
                    $allData[$formId] = ['tindakan' => []];
                }

                foreach ($formData['tindakan'] as $index => $tindakan) {
                    $rules["tindakan_{$formId}.{$index}.tindakan"] = 'required';
                    $rules["tindakan_{$formId}.{$index}.pic"] = 'required';
                    $rules["tindakan_{$formId}.{$index}.waktu"] = 'required';

                    $messages["tindakan_{$formId}.{$index}.tindakan.required"] = 'Tindakan harus diisi';
                    $messages["tindakan_{$formId}.{$index}.pic.required"] = 'PIC harus diisi';
                    $messages["tindakan_{$formId}.{$index}.waktu.required"] = 'Target waktu harus diisi';

                    if (empty($tindakan['tindakan'])) {
                        $missingFields[] = "Tindakan di halaman $i kosong";
                    }
                    if (empty($tindakan['pic'])) {
                        $missingFields[] = "PIC di halaman $i kosong";
                    }
                    if (empty($tindakan['waktu'])) {
                        $missingFields[] = "Target waktu di halaman $i tidak valid";
                    }

                    $allData[$formId]['tindakan'][] = $tindakan;
                }
            }
        }

        //field kosong
        if (!empty($missingFields)) {
            return redirect()
                ->back()
                ->with('error_message', 'Ada data yang belum lengkap: ' . implode(', ', $missingFields));
        }

        DB::beginTransaction();
        try {
            foreach ($allData as $formId => $formData) {
                //hapus data lama
                RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
                ->where('form_id', $formId)
                ->delete();

                //simpan data baru
                foreach ($formData['tindakan'] as $tindakan) {

                    if (empty($tindakan['pic']) || !str_contains($tindakan['pic'], '|')) {
                        continue; 
                    }
                    [$picUserId, $picJabatanId] = explode('|', $tindakan['pic']);

                    RtmTindakLanjut::create([
                        'rtm_rtl_id' => $rtmRtl->id,
                        'form_id' => $formId,
                        'kriteria_id' => $kriteriaId,
                        'auditee_id' => $auditeeId,
                        'tindakan' => $tindakan['tindakan'],
                        'user_id'      => $picUserId,
                        'jabatan_id'   => $picJabatanId,
                        'waktu' => $tindakan['waktu'],
                    ]);
                }
            }

            StatusRtmRtl::updateOrCreate(
                [
                    'rtm_rtl_id' => $rtmRtl->id,
                    'kriteria_id' => $kriteriaId
                ],
                ['status' => 'completed']
            );

            DB::commit();

            for ($i = 1; $i <= $totalPages; $i++) {
                $sessionKey = 'form_rtm_rtl-page_' . $i . '-rtmRtlId_' . $rtmRtl;
                session()->forget($sessionKey);
            }

            return redirect()->route('admin.rtm-rtl.show', ['rtmJadwal' => $rtmRtl->rtmJadwal->id])
            ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving Tindak Lanjut data: ' . $e->getMessage());

            return redirect()
                ->route('admin.rtm-rtl.form', [
                    'rtmRtl' => $rtmRtl->id
                ])
                ->with('error_message', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
