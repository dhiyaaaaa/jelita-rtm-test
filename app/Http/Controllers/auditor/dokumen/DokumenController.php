<?php

namespace App\Http\Controllers\auditor\dokumen;

use App\Http\Controllers\Controller;
use App\Models\Auditee;
use App\Models\Auditor;
use App\Models\Fakultas;
use App\Models\Form;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditee;
use App\Models\JawabanAuditor;
use App\Models\Level;
use App\Models\Link;
use App\Models\Notifikasi;
use App\Models\Prodi;
use App\Models\StatusAuditAuditee;
use App\Models\StatusAuditAuditor;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DokumenController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;
    }

    public function index(): View
    {
        $jadwal = Auditor::with('jadwal_audit')
            ->where('user_id', $this->user->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $data = [
            'title' => 'Audit Dokumen',
            'jadwal' => $jadwal,
        ];

        return view('auditor.dokumen.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {
        $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id])->first();

        $prodi = Prodi::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'jenjang',
            'auditee.user',
            'status_audit_auditee' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
                $query->where('daftar_tilik', 1);
            },
            'status_audit_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
        ])->get();

        $fakultas = Fakultas::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'auditee.user',
            'status_audit_auditee' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
                $query->where('daftar_tilik', 1);
            },
            'status_audit_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
        ])->get();

        $unit = Unit::whereHas('auditee.auditor', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'auditee.user',
            'status_audit_auditee' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
            'jawaban_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
                $query->where('daftar_tilik', 1);
            },
            'status_audit_auditor' => function ($query) use ($jadwalAudit) {
                $query->where('jadwal_audit_id', $jadwalAudit->id);
            },
        ])->get();

        $auditee = $prodi->merge($fakultas)->merge($unit);

        $data = [
            'title' => 'Audit Dokumen ' . strtoupper($jadwalAudit->jadwal),
            'auditee' => $auditee,
            'jadwal' => $jadwalAudit,
            'expired' => $jadwalAudit->expired,
            'auditor' => $auditor,
        ];

        return view('auditor.dokumen.show', $data);
    }

    public function isi_audit(string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        if ($this->user->no_telepon == '' || $this->user->no_telepon == null) {
            return redirect()->route('profile')->with('error', 'Harap isi no telepon terlebih dahulu.');
        }

        StatusAuditAuditor::updateOrCreate(
            ['jadwal_audit_id' => $jadwalAudit, get_type($type) => $unit],
            ['status' => 'in_progress']
        );

        return redirect()->route('auditor.dokumen.create', [
            'jadwalAudit' => $jadwalAudit,
            'unit' => $unit,
            'type' => $type,
        ]);
    }

    public function create(Request $request, JadwalAudit $jadwalAudit, string $unit, string $type): View|RedirectResponse
    {
        if ($this->user->roles->first()->name !== 'pusjamu') {
            if ($this->user->no_telepon == '' || $this->user->no_telepon == null) {
                return redirect()->route('profile')->with('error', 'Harap isi no telepon terlebih dahulu.');
            }
        }

        $auditees = Auditee::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $auditeeId = $auditees->pluck('id')->toArray();

        $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id])
            ->whereHas('auditee', function ($query) use ($auditeeId) {
                $query->whereIn('auditee_id', $auditeeId);
            })->first();

        if ($this->user->roles->first()->name !== 'pusjamu' && !$auditor) {
            abort(403);
        }

        $order_by_kode = DB::raw("
            CASE
                WHEN REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') ~ '^[0-9]+$'
                THEN CAST(REGEXP_REPLACE((SELECT kode FROM instrumen WHERE instrumen.id = form.instrumen_id), '[^0-9]', '', 'g') AS INTEGER)
                ELSE NULL
            END
        ");

        $forms = collect();
        if ($type === 'prodi') {
            $level = Level::where('slug', 'prodi')->first();
            $prodi = Prodi::findOrFail($unit);
            $forms = $forms->merge(
                Form::where('jadwal_id', $jadwalAudit->id)
                    ->whereHas('instrumen', function ($query) use ($level) {
                        $query->where('level_id', $level->id);
                    })
                    ->where(function ($query) use ($unit, $prodi) {
                        $query->whereHas('instrumen.jenjang', function ($query) use ($prodi) {
                            $query->where('jenjang_id', $prodi->jenjang->id);
                        });
                        $query->orWhereHas('instrumen.prodi', function ($query) use ($unit) {
                            $query->where('prodi_id', $unit);
                        });
                    })
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->orderBy(
                        DB::raw('(SELECT standar_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy(
                        DB::raw('(SELECT kategori_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy($order_by_kode)
                    ->get()
            );
        } elseif ($type === 'fakultas') {
            $level = Level::where('slug', 'fakultas')->first();
            $forms = $forms->merge(
                Form::where('jadwal_id', $jadwalAudit->id)
                    ->whereHas('instrumen', function ($query) use ($level) {
                        $query->where('level_id', $level->id);
                    })
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->orderBy(
                        DB::raw('(SELECT standar_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy(
                        DB::raw('(SELECT kategori_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy($order_by_kode)
                    ->get()
            );
        } elseif ($type === 'universitas') {
            $level = Level::where('slug', 'universitas')->first();
            $forms = $forms->merge(
                Form::where('jadwal_id', $jadwalAudit->id)
                    ->whereHas('instrumen', function ($query) use ($level) {
                        $query->where('level_id', $level->id);
                    })
                    ->where(function ($query) use ($unit) {
                        $query->whereDoesntHave('instrumen.unit')
                            ->orWhereHas('instrumen.unit', function ($query) use ($unit) {
                                $query->where('unit_id', $unit);
                            });
                    })
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                    ->orderBy(
                        DB::raw('(SELECT standar_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy(
                        DB::raw('(SELECT kategori_id FROM instrumen WHERE instrumen.id = form.instrumen_id)'),
                        'asc'
                    )
                    ->orderBy($order_by_kode)
                    ->get()
            );
        } else {
            abort(404);
        }

        $status = StatusAuditAuditor::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

        $groupedForms = $forms->groupBy(function ($item) {
            return $item->instrumen->standar->nama . ' - ' . $item->instrumen->kategori->nama;
        });

        $flatForms = $groupedForms->flatMap(function ($forms, $group) {
            return $forms->map(function ($form) use ($group) {
                return ['group' => $group, 'form' => $form];
            });
        });

        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $paginatedForms = new LengthAwarePaginator(
            $flatForms->forPage($currentPage, $perPage),
            $flatForms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url()]
        );


        $jawabanAuditee = JawabanAuditee::where('jadwal_audit_id', $jadwalAudit->id)
            ->where(get_type($type), $unit)
            ->get();

        $links = Link::where('jadwal_audit_id', $jadwalAudit->id)
            ->where(get_type($type), $unit)
            ->get();

        $jawabanAuditors = JawabanAuditor::where('jadwal_audit_id', $jadwalAudit->id)
            ->where(get_type($type), $unit)
            ->get();

        $notifikasi = Notifikasi::where('jadwal_audit_id', $jadwalAudit->id)
            ->where(get_type($type), $unit)
            ->get();

        $sessionFormData = session()->get('form_audit_dokumen-page_' . $currentPage . '-jadwalId_' . $jadwalAudit->id . '-unitId_' . $unit . '-auditorId_' . $auditor->id, []);

        // dd(session()->all());
        $data = [
            'title' => $jadwalAudit->jadwal,
            'paginatedForms' => $paginatedForms,
            'currentPage' => $currentPage,
            'jadwalAudit' => $jadwalAudit,
            'unitId' => $unit,
            'jawabanAuditee' => $jawabanAuditee,
            'jawabanAuditors' => $jawabanAuditors,
            'links' => $links,
            'sessionFormData' => $sessionFormData,
            'auditor' => $auditor,
            'type' => $type,
            'expired' => $jadwalAudit->expired,
            'notifikasi' => $notifikasi,
            'status' => $status,
        ];

        return view('auditor.dokumen.create', $data);
    }

    public function save(Request $request, string $jadwalAudit, string $unit, string $type): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];

                $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit])->first();

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'kriteria_') === 0) {
                        $id = substr($key, strlen('kriteria_'));
                        $kriteriaKey = 'kriteria_' . $id;
                        $catatanKey = 'catatan_' . $id;
                        $daftarTilikKey = 'daftar-tilik_' . $id;

                        $data = [
                            'jadwal_audit_id' => $jadwalAudit,
                            'form_id' => $id,
                            'auditor_id' => $auditor->id,
                            'kriteria_id' => $request->input($kriteriaKey),
                            'catatan' => $request->input($catatanKey, null),
                            'daftar_tilik' => $request->input($daftarTilikKey, null),
                            'ptk' => $request->input($daftarTilikKey, null),
                        ];

                        $data[get_type($type)] = $unit;

                        JawabanAuditor::updateOrCreate(
                            [
                                'jadwal_audit_id' => $jadwalAudit,
                                'form_id' => $id,
                                get_type($type) => $unit,
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

    // Save per nomor (backup)
    public function save_per_nomor(Request $request, string $jadwalAudit, string $unit, string $type, string $formId) {}

    public function store(Request $request, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit])->first();

        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = 'form_audit_dokumen-page_' . $i . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit . '-auditorId_' . $auditor->id;
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

                if (strpos($key, 'catatan_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    $kriteriaKey = 'kriteria_' . $id;
                    if (!array_key_exists($kriteriaKey, $data)) {
                        $rules[$kriteriaKey] = 'required';
                        $messages[$kriteriaKey . '.required'] = 'Jawaban harus diisi';
                        if (!isset($value) || $value === '') {
                            $missingFields[] = $kriteriaKey;
                            ++$nullCount;
                            if ($errorPage === null) {
                                $errorPage = $page;
                            }
                        }
                    }

                    $daftarTilikKey = 'daftar-tilik_' . $id;
                    if (!array_key_exists($daftarTilikKey, $data)) {
                        $rules[$daftarTilikKey] = 'required';
                        $messages[$daftarTilikKey . '.required'] = 'Daftar Tilik harus diisi';
                        if (!isset($value) || $value === '') {
                            $missingFields[] = $daftarTilikKey;
                            ++$nullCount;
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

            // dd($errors);
            return redirect()->route('auditor.dokumen.create', ['jadwalAudit' => $jadwalAudit, 'unit' => $unit, 'type' => $type, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;

                return redirect()->route('auditor.dokumen.create', ['jadwalAudit' => $jadwalAudit, 'unit' => $unit, 'type' => $type, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'kriteria_') === 0 || strpos($key, 'daftar-tilik_') === 0) {
                $id = substr($key, strpos($key, '_') + 1);
                $kriteriaKey = 'kriteria_' . $id;
                $catatanKey = 'catatan_' . $id;
                $daftarTilikKey = 'daftar-tilik_' . $id;

                $data = [
                    'jadwal_audit_id' => $jadwalAudit,
                    'form_id' => $id,
                    'auditor_id' => $auditor->id,
                    'kriteria_id' => $requestData[$kriteriaKey],
                    'catatan' => $requestData[$catatanKey] ?? null,
                    'daftar_tilik' => $requestData[$daftarTilikKey] ?? null,
                    'ptk' => $requestData[$daftarTilikKey] ?? null,
                ];

                $data[get_type($type)] = $unit;

                JawabanAuditor::updateOrCreate(
                    [
                        'jadwal_audit_id' => $jadwalAudit,
                        'form_id' => $id,
                        get_type($type) => $unit,
                    ],
                    $data
                );
            }
        }

        if ($requestData['final'] == 'final') {
            $status = StatusAuditAuditor::where('jadwal_audit_id', $jadwalAudit)
                ->where(get_type($type), $unit)
                ->first();
            // dd($status);

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditor.dokumen.show', $jadwalAudit)
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show_daftar_tilik(string $jadwalAudit, string $unit, string $type): View
    {
        $daftarTilik = JawabanAuditor::where('jadwal_audit_id', $jadwalAudit)
            ->where('daftar_tilik', 1)
            ->where(get_type($type), $unit)
            ->with([
                'form.instrumen',
                'form.jawaban_auditee' => function ($query) use ($unit, $jadwalAudit, $type) {
                    $query->where('jadwal_audit_id', $jadwalAudit);
                    $query->where(get_type($type), $unit);
                },
                'form.link' => function ($query) use ($unit, $type) {
                    $query->where(get_type($type), $unit);
                },
            ])
            ->orderBy('created_at', 'ASC')->get();

        $jadwal = JadwalAudit::findOrFail($jadwalAudit);

        if ($type === 'prodi') {
            $unit = Prodi::findOrFail($unit);
        } elseif ($type === 'fakultas') {
            $unit = Fakultas::findOrFail($unit);
        } elseif ($type === 'universitas') {
            $unit = Unit::findOrFail($unit);
        } else {
            abort(404);
        }

        $data = [
            'title' => 'Daftar Tilik',
            'daftarTilik' => $daftarTilik,
            'jadwal' => $jadwal,
            'unit' => $unit,
            'type' => $type,
            'expired' => $jadwal->expired,
        ];

        return view('auditor.dokumen.daftar_tilik', $data);
    }

    public function edit_hapus_ptk(Request $request, string $jawabanId): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $response = [];
                $jawaban = JawabanAuditor::findOrFail($jawabanId);

                $jawaban->update(['ptk' => $jawaban->ptk ? 0 : 1]);

                $response['message'] = $jawaban->ptk === 1 ? 'Instrumen berhasil ditambahkan ke Temuan Negatif!' : 'Instrumen berhasil dihapus dari Temuan Negatif!';

                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }
}
