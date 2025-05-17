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
        
        $allKriteria = Kriteria::all();
        
        $rtmRtl = RtmRtl::with([
            'status_rtm_rtl.kriteria',
            'status_rtm_rtl_prodi',
            'rtm_jadwal.status_rtm_catatan',
            'jadwal_audit',
            'rtm_jadwal',
            'auditee',
        ])->findOrFail($rtmRtl->id);

        $approval = $rtmRtl->auditee->first() ? $rtmRtl->auditee->first()->pivot->approve : null;

        $data = [
            'title' => 'Rencana Tindak Lanjut Hasil Audit' . ' ' . $rtmRtl->jadwal_audit->jadwal,
            'rtmRtl' => $rtmRtl,
            'allKriteria' => $allKriteria, 
            'auditee' => $auditee,
            'approval' => $approval
        ];

        return view('dekan.rtm.rtm_rtl.show', $data);
    }

    public function isi_rtm_rtl(RtmRtl $rtmRtl, $kriteria)
    {
        $kriteria = Kriteria::findOrFail($kriteria);

        StatusRtmRtl::updateOrCreate(
            [
                'rtm_rtl_id' => $rtmRtl->id,
                'kriteria_id' => $kriteria->id,
            ],
            ['status' => 'in_progress']
        );

        return response()->json([
            'success' => true,
            'redirect_url' => route('dekan.rtm-rtl.form', [
                'rtmRtl' => $rtmRtl->id,
                'kriteria' => $kriteria->id
            ]),
        ]);
    }

    public function form(Request $request, RtmRtl $rtmRtl, $kriteria): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);
        $kriteria = Kriteria::findOrFail($kriteria);

        $fakultas = $rtmRtl->fakultas_id ? Fakultas::find($rtmRtl->fakultas->id) : null;
        $unit = $rtmRtl->unit_id ? Unit::find($rtmRtl->unit_id) : null;

        $jabatanUserId = optional($this->user->jabatan->first())->id;
        $jabatanUser = Jabatan::find($jabatanUserId);
        $auditee = $this->auditee;

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $temuanFakultas = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->where('kriteria_id', $kriteria->id)
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
            ->paginate($perPage);
        

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

        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)
        ->where('kriteria_id', $kriteria->id)
        ->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $formData,
            'kriteria' => $kriteria,
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

    public function save_form(Request $request, string $rtmRtl, string $auditee, string $kriteria): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Permintaan tidak valid.'], 400);
        }

        try {
            $currentPage = $request->input('currentPage', 1);
            $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;

            $auditee = Auditee::where('user_id', Auth::id())->first();
            $auditeeId = $auditee?->id;

            $kriteria = Kriteria::findOrFail($kriteria);

            $validationRules = [];
            $validationMessages = [];
            $hasData = false;

            // Buat aturan validasi dinamis
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
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

            // Jalankan validasi
            $validator = Validator::make($request->all(), $validationRules, $validationMessages);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    $hasData = true;

                    $existingData = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl)
                        ->where('form_id', $formId)
                        ->get();

                    $submittedData = collect($value)->filter(fn($item) =>
                        !empty($item['tindakan']) && !empty($item['pic']) && !empty($item['waktu'])
                    );

                    // Hapus data lama yang tidak lagi dikirim
                    $toDelete = $existingData->filter(function ($item) use ($submittedData) {
                        return !$submittedData->contains('tindakan', $item->tindakan);
                    });

                    foreach ($toDelete as $item) {
                        $item->delete();
                    }

                    // Simpan data baru
                    foreach ($submittedData as $item) {
                        [$userId, $jabatanId] = explode('|', $item['pic']);

                        RtmTindakLanjut::updateOrCreate(
                            [
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                                'tindakan' => $item['tindakan']
                            ],
                            [
                                'user_id' => $userId,
                                'jabatan_id' => $jabatanId,
                                'waktu' => $item['waktu'],
                                'kriteria_id' => $kriteria->id,
                                'auditee_id' => $auditeeId
                            ]
                        );
                    }
                }
            }

            DB::commit();

            if (!$hasData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang valid untuk disimpan.'
                ], 422);
            }

            session()->forget($sessionKey);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RTM RTL Save Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee, string $kriteria): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $kriteriaId = $kriteria;
        $auditeeId = $auditee;
        $allData = [];
        $missingFields = [];

        // Ambil semua data dari session
        for ($page = 1; $page <= $totalPages; $page++) {
            $sessionKey = 'form_rtm_rtl-page_' . $page . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditeeId;
            $pageData = session($sessionKey, []);

            foreach ($pageData as $formId => $formData) {
                foreach ($formData['tindakan'] as $tindakan) {
                    $validator = Validator::make(
                        $tindakan,
                        [
                            'tindakan' => 'required|string',
                            'pic' => ['required', 'string', 'regex:/\|/'],
                            'waktu' => 'required|string',
                        ],
                        [
                            'tindakan.required' => 'Tindakan harus diisi',
                            'pic.required' => 'PIC harus diisi',
                            'waktu.required' => 'Waktu target harus diisi',
                        ]
                    );

                    if ($validator->fails()) {
                        return back()->withErrors($validator)->withInput();
                    }

                    $allData[$formId]['tindakan'][] = $tindakan;
                }
            }
        }

        if (!empty($missingFields)) {
            return back()->with('error_message', 'Ada data yang belum lengkap: ' . implode(', ', $missingFields));
        }

        // Simpan data ke database
        DB::beginTransaction();
        try {
            foreach ($allData as $formId => $formData) {
                foreach ($formData['tindakan'] as $tindakan) {
                    [$userId, $jabatanId] = explode('|', $tindakan['pic']);

                    // Simpan tindak lanjut
                    RtmTindakLanjut::updateOrCreate(
                        [
                            'rtm_rtl_id'   => $rtmRtl->id,
                            'form_id'      => $formId,
                            'kriteria_id'  => $kriteriaId,
                            'auditee_id'   => $auditeeId,
                        ],
                        [
                            'tindakan'     => $tindakan['tindakan'],
                            'user_id'      => $userId,
                            'jabatan_id'   => $jabatanId,
                            'waktu'        => $tindakan['waktu'],
                        ]
                    );
                }
            }

            // Update status
            StatusRtmRtl::updateOrCreate(
                [
                    'rtm_rtl_id' => $rtmRtl->id,
                    'kriteria_id' => $kriteriaId
                ],
                ['status' => 'completed']
            );

            DB::commit();

            for ($page = 1; $page <= $totalPages; $page++) {
                $sessionKey = 'form_rtm_rtl-page_' . $page . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditeeId;
                session()->forget($sessionKey);
            }

            return redirect()
                ->route('dekan.rtm-rtl.show', $rtmRtl)
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan tindak lanjut RTM-RTL: ' . $e->getMessage());

            return back()->with('error_message', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}