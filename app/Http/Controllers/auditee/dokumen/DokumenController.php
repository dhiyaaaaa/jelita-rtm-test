<?php

namespace App\Http\Controllers\auditee\dokumen;

use App\Http\Controllers\Controller;
use App\Models\Auditee;
use App\Models\Form;
use App\Models\JadwalAudit;
use App\Models\JawabanAuditee;
use App\Models\JawabanAuditor;
use App\Models\Level;
use App\Models\Link;
use App\Models\Notifikasi;
use App\Models\Prodi;
use App\Models\StatusAuditAuditee;
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

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $prodi = $this->user->prodi->first();
        $fakultas = $this->user->fakultas->first();
        $unit = $this->user->unit->first();

        if (!$prodi && !$fakultas && !$unit) {
            abort(403);
        }

        $jadwal = JadwalAudit::whereHas('form.instrumen', function ($query) use ($prodi, $unit) {
            $query->where(function ($query) use ($prodi, $unit) {
                $query->orWhereHas('jenjang', function ($query) use ($prodi, $unit) {
                    if ($prodi) {
                        $query->where('prodi_id', $prodi->id)
                            ->orWhere('jenjang_id', $prodi->jenjang->id);
                    } elseif ($unit) {
                        $query->where('unit_id', $unit->id);
                    }
                });
            })->orWhereHas('jabatan', function ($query) {
                $query->where('jabatan_id', $this->jabatanUser);
            });
        })->with([
            'status_audit_auditee' => function ($query) use ($prodi, $fakultas, $unit) {
                if ($prodi) {
                    $query->where('prodi_id', $prodi->id);
                } elseif ($fakultas) {
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
        ])
            ->with(['auditee_auditor' => function ($q) use ($prodi, $fakultas, $unit) {
                if ($prodi) {
                    $q->where('prodi_id', $prodi->id)->with(['auditor.user'])->distinct('auditor_id');
                } elseif ($fakultas) {
                    $q->where('fakultas_id', $fakultas->id)->with(['auditor.user'])->distinct('auditor_id');
                } elseif ($unit) {
                    $q->where('unit_id', $unit->id)->with(['auditor.user'])->distinct('auditor_id');
                }
            }])->orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Audit Dokumen',
            'jadwal' => $jadwal,
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'unit' => $unit,
        ];

        return view('auditee.dokumen.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function isi_audit(JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        if ($this->user->no_telepon == '' || $this->user->no_telepon == null) {
            return redirect()->route('profile')->with('error', 'Harap isi no telepon terlebih dahulu.');
        }

        StatusAuditAuditee::updateOrCreate(
            ['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit],
            ['status' => 'in_progress']
        );

        return redirect()->route('auditee.dokumen.create', [
            'jadwalAudit' => $jadwalAudit->id,
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

        if (!$this->user->roles->contains(function ($role) {
            return in_array($role->name, ['pj_universitas', 'pj_fakultas', 'pj_prodi', 'gkm']);
        })) {
            abort(403);
        }


        $status = StatusAuditAuditee::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

        $auditee = Auditee::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

        if (!$auditee) {
            $auditee = null;
        }

        $jabatanUser = $this->jabatanUser;

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

        // Jawaban dan Link Auditee
        $jawabanAuditee = JawabanAuditee::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $links = Link::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $sessionFormData = $auditee
            ? session()->get('form_data-page_' . $currentPage . '-jadwalId_' . $jadwalAudit->id . '-unitId_' . $unit . '-auditeeId_' . $auditee->id, [])
            : [];

        $jawabanAuditors = JawabanAuditor::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $notifikasi = Notifikasi::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->get();

        $data = [
            'title' => $jadwalAudit->jadwal,
            'paginatedForms' => $paginatedForms,
            'sessionFormData' => $sessionFormData,
            'jawabanAuditee' => $jawabanAuditee,
            'links' => $links,
            'currentPage' => $currentPage,
            'jadwal' => $jadwalAudit,
            'unitId' => $unit,
            'auditee' => $auditee,
            'jabatanUser' => $jabatanUser,
            'status' => $status,
            'type' => $type,
            'expired' => $jadwalAudit->expired,
            'jawabanAuditors' => $jawabanAuditors,
            'notifikasi' => $notifikasi,
        ];

        return view('auditee.dokumen.create', $data);
    }

    public function save(Request $request, JadwalAudit $jadwalAudit, string $unit, string $type): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $auditee = Auditee::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

                if (!$auditee) {
                    return response()->json(['success' => false, 'message' => 'Anda belum ditambahkan sebagai auditan sehingga tidak bisa mengisi form'], 403);
                }

                $rules = [];
                $messages = [];
                foreach ($request->all() as $key => $value) {
                    if (preg_match('/^link_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                        $rules[$key . '.*'] = 'nullable|url';
                        $messages[$key . '.*.url'] = 'URL tidak valid';
                    }
                }

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    $firstError = $validator->errors()->first();
                    return response()->json(['message' => $firstError], 422);
                }

                $response = [];

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'instrumen_') === 0) {
                        $formId = str_replace('instrumen_', '', $key);
                        if ($value !== null && $value !== '') {
                            $jawabanAuditeeData = [
                                'jadwal_audit_id' => $jadwalAudit->id,
                                'form_id' => $formId,
                                'auditee_id' => $auditee->id,
                                'jawaban' => $value,
                            ];

                            $jawabanAuditeeData[get_type($type)] = $unit;

                            JawabanAuditee::updateOrCreate(
                                [
                                    'jadwal_audit_id' => $jadwalAudit->id,
                                    'form_id' => $formId,
                                ],
                                $jawabanAuditeeData
                            );
                        }
                    }

                    if (preg_match('/^link_([^-]+-[^-]+-[^-]+-[^-]+-[^-]+)$/', $key, $matches)) {
                        $formId = $matches[1];

                        $existLink = Link::where(['jadwal_audit_id' => $jadwalAudit->id, 'form_id' => $formId, get_type($type) => $unit])->pluck('link', 'id')->toArray();

                        $submittedLinks = $value;

                        if (!is_array($submittedLinks)) {
                            return response()->json(['message' => 'Invalid input data format'], 422);
                        }

                        $submittedLinks = array_filter($submittedLinks);

                        $currentSubmittedLinks = array_map(function ($link) {
                            return $link;
                        }, $submittedLinks);

                        foreach ($existLink as $id => $link) {
                            if (!in_array($link, $currentSubmittedLinks)) {
                                Link::where('id', $id)->delete();
                            }
                        }

                        foreach ($submittedLinks as $link) {
                            if (!in_array($link, $existLink)) {
                                Link::updateOrCreate(
                                    [
                                        'jadwal_audit_id' => $jadwalAudit->id,
                                        'form_id' => $formId,
                                        'link' => $link,
                                        get_type($type) => $unit
                                    ],
                                    [
                                        'auditee_id' => $auditee->id,
                                        get_type($type) => $unit
                                    ]
                                );
                            }
                        }
                    }
                }

                $response['message'] = 'Jawaban berhasil disimpan.';

                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    public function store(Request $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $auditee = Auditee::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

        if (!$auditee) {
            return redirect()->route('auditee.dokumen.create', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unit, 'type' => $type])->with('error_message', 'Anda belum ditambahkan sebagai auditan sehingga tidak bisa mengisi form.');
        }

        $totalPages = $request->input('totalPage');
        $sessionFormData = [];
        $errorPage = null;

        for ($i = 1; $i <= $totalPages; ++$i) {
            $sessionKey = "form_data-page_{$i}-jadwalId_{$jadwalAudit->id}-unitId_{$unit}-auditeeId_{$auditee->id}";
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

        $rules = [];
        $messages = [];
        $missingFields = [];
        $formId = [];
        $requestData = [];
        $nullCount = 0;
        $threshold = 1;

        // dd($sessionFormData);
        foreach ($sessionFormData as $page => $data) {
            foreach ($data as $key => $value) {
                $requestData[$key] = $value;

                if (strpos($key, 'instrumen_') === 0) {
                    $id = substr($key, strrpos($key, '_') + 1);
                    $formId[] = $id;

                    $rules[$key] = 'required';
                    $messages[$key . '.required'] = 'Instrumen harus diisi';

                    if (!isset($value) || $value === '') {
                        $missingFields[] = $key;
                        ++$nullCount;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                } elseif (strpos($key, 'link_') === 0) {
                    if (is_array($value) && !empty($value)) {
                        foreach ($value as $index => $link) {
                            if (!empty($link)) {
                                $rules[$key . '.' . $index] = 'url';
                                $messages[$key . '.' . $index . '.url'] = 'URL tidak valid.';
                                if (!filter_var($link, FILTER_VALIDATE_URL)) {
                                    $missingFields[] = $key . '.' . $index;
                                    ++$nullCount;
                                    if ($errorPage === null) {
                                        $errorPage = $page;
                                    }
                                }
                            } else {
                                $rules[$key . '.' . $index] = 'required';
                                $messages[$key . '.' . $index . '.required'] = 'Link harus diisi.';
                                $missingFields[] = $key . '.' . $index;
                                ++$nullCount;
                                if ($errorPage === null) {
                                    $errorPage = $page;
                                }
                            }
                        }
                    } elseif (empty($value) || $value[0] === null || $value[0] === '') {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = 'Link harus diisi.';
                        $missingFields[] = $key;
                        ++$nullCount;
                        if ($errorPage === null) {
                            $errorPage = $page;
                        }
                    }
                }
            }
        }

        // dd($sessionFormData);
        $customErrorMessage = '';
        if ($nullCount >= $threshold) {
            $customErrorMessage = 'Harap isi semua jawaban atau jawaban Anda ada yang tidak sesuai.';
        }

        $validator = Validator::make($requestData, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessage = $customErrorMessage;

            if (!$customErrorMessage) {
                $errorMessage = $errors->first();
            }

            return redirect()->route('auditee.dokumen.create', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unit, 'type' => $type, 'page' => $errorPage])
                ->withErrors($errors)
                ->withInput()
                ->with('error_message', $errorMessage)
                ->with('missing_fields', $missingFields);
        }

        for ($i = 1; $i <= $totalPages; ++$i) {
            if (empty($sessionFormData[$i])) {
                $errorPage = $i;

                return redirect()->route('auditee.dokumen.create', ['jadwalAudit' => $jadwalAudit->id, 'unit' => $unit, 'type' => $type, 'page' => $errorPage])
                    ->with('error_message', 'Data tidak ditemukan. Silakan periksa halaman tersebut.')
                    ->withInput();
            }
        }

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'instrumen_') === 0) {
                $id = substr($key, strlen('instrumen_'));
                $newFormIds[] = $id;

                $jawabanAuditee = JawabanAuditee::updateOrCreate(
                    [
                        'jadwal_audit_id' => $jadwalAudit->id,
                        'form_id' => $id,
                        get_type($type) => $unit,
                    ],
                    [
                        'auditee_id' => $auditee->id,
                        'jawaban' => $value,
                    ]
                );

                if (isset($requestData['link_' . $id])) {
                    $existingLinks = Link::where(['jadwal_audit_id' => $jadwalAudit->id, 'form_id' => $id, get_type($type) => $unit])->pluck('link', 'id')->toArray();
                    // dd($requestData);

                    $submittedLinks = array_filter($requestData['link_' . $id]);
                    if (!is_array($submittedLinks)) {
                        return response()->json(['message' => 'Invalid input data format'], 422);
                    }

                    foreach ($existingLinks as $idLink => $link) {
                        if (!in_array($link, $submittedLinks)) {
                            Link::where('id', $idLink)->delete();
                        }
                    }

                    foreach ($submittedLinks as $link) {
                        Link::updateOrCreate(
                            [
                                'jadwal_audit_id' => $jadwalAudit->id,
                                'form_id' => $id,
                                'link' => $link,
                            ],
                            [
                                'auditee_id' => $auditee->id,
                                get_type($type) => $unit
                            ]
                        );
                    }
                }
            }
        }

        if ($requestData['final'] == 'final') {
            $status = StatusAuditAuditee::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
            }
        }

        return redirect()->route('auditee.dokumen')
            ->with('success', 'Jawaban berhasil disimpan.');
    }

    // Import Jawaban
    public function import(JadwalAudit $jadwalAudit, string $unit, string $type): View
    {
        if (!$jadwalAudit->import) {
            abort(403);
        }

        $jadwals = JadwalAudit::where('created_at', '<', $jadwalAudit->created_at)->orderBy('created_at', 'DESC')->get();

        $data = [
            'title' => 'Import Jawaban',
            'jadwal' => $jadwalAudit,
            'unitId' => $unit,
            'jadwals' => $jadwals,
            'type' => $type,
        ];

        return view('auditee.dokumen.import', $data);
    }

    public function import_store(Request $request, JadwalAudit $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        try {

            $validated = $request->validate([
                'jadwal' => 'required|uuid|exists:jadwal_audit,id',
            ]);

            $auditee = Auditee::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->first();

            $forms = collect();
            $currentForms = collect();

            if ($type === 'prodi') {
                $prodi = Prodi::findOrFail($unit);
                $forms = $forms->merge(
                    Form::where('jadwal_id', $validated['jadwal'])
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 1);
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
                        ->get()
                        ->pluck('instrumen.id')
                );
                $currentForms = $currentForms->merge(
                    Form::where('jadwal_id', $jadwalAudit->id)
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 1);
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
                        ->get()
                        ->pluck('instrumen.id')
                );
            } elseif ($type === 'fakultas') {
                $forms = $forms->merge(
                    Form::where('jadwal_id', $validated['jadwal'])
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 2);
                        })
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->get()
                        ->pluck('instrumen.id')
                );
                $currentForms = $currentForms->merge(
                    Form::where('jadwal_id', $jadwalAudit->id)
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 2);
                        })
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->get()
                        ->pluck('instrumen.id')
                );
            } elseif ($type === 'universitas') {
                $forms = $forms->merge(
                    Form::where('jadwal_id', $validated['jadwal'])
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 3);
                        })
                        ->where(function ($query) use ($unit) {
                            $query->whereDoesntHave('instrumen.unit')
                                ->orWhereHas('instrumen.unit', function ($query) use ($unit) {
                                    $query->where('unit_id', $unit);
                                });
                        })
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->get()
                        ->pluck('instrumen.id')
                );
                $currentForms = $currentForms->merge(
                    Form::where('jadwal_id', $jadwalAudit->id)
                        ->whereHas('instrumen', function ($query) {
                            $query->where('level_id', 3);
                        })
                        ->where(function ($query) use ($unit) {
                            $query->whereDoesntHave('instrumen.unit')
                                ->orWhereHas('instrumen.unit', function ($query) use ($unit) {
                                    $query->where('unit_id', $unit);
                                });
                        })
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->with(['instrumen.standar', 'instrumen.kategori', 'instrumen.jenis_pertanyaan'])
                        ->get()
                        ->pluck('instrumen.id')
                );
            } else {
                abort(404);
            }

            $intersect = $forms->intersect($currentForms);

            $jawabanAuditee = JawabanAuditee::where(['jadwal_audit_id' => $validated['jadwal'], get_type($type) => $unit])
                ->whereHas('form.instrumen', function ($query) use ($intersect) {
                    $query->whereIn('id', $intersect);
                })
                ->with(['form.instrumen'])->get();

            foreach ($jawabanAuditee as $jawaban) {
                $formid = Form::where(['instrumen_id' => $jawaban->form->instrumen->id, 'jadwal_id' => $jadwalAudit->id])->firstOrFail()->id;

                $cari = [
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'form_id' => $formid,
                ];

                $cari[get_type($type)] = $unit;

                $data = [
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'form_id' => $formid,
                    'jawaban' => $jawaban->jawaban,
                    'auditee_id' => $auditee->id,
                ];

                $data[get_type($type)] = $unit;

                JawabanAuditee::updateOrCreate($cari, $data);
            }

            Link::where(['jadwal_audit_id' => $jadwalAudit->id, get_type($type) => $unit])->delete();

            $links = Link::where(['jadwal_audit_id' => $validated['jadwal'], get_type($type) => $unit])
                ->whereHas('form.instrumen', function ($query) use ($intersect) {
                    $query->whereIn('id', $intersect);
                })
                ->with(['form.instrumen'])->get();

            foreach ($links as $link) {
                $formid = Form::where(['instrumen_id' => $link->form->instrumen->id, 'jadwal_id' => $jadwalAudit->id])->firstOrFail()->id;

                $data = [
                    'jadwal_audit_id' => $jadwalAudit->id,
                    'form_id' => $formid,
                    'link' => $link->link,
                    'auditee_id' => $auditee->id,
                ];

                $data[get_type($type)] = $unit;

                Link::updateOrCreate($data);
            }

            $sessionPrefix = 'form_data-page_';
            $jadwalKey = '-jadwalId_' . $jadwalAudit->id;
            $unitKey = '-unitId_' . $unit;
            $auditeeKey = '-auditeeId_' . $auditee->id;

            foreach (session()->all() as $key => $value) {
                if (
                    str_starts_with($key, $sessionPrefix) &&
                    str_contains($key, $jadwalKey) &&
                    str_contains($key, $unitKey) &&
                    str_contains($key, $auditeeKey)
                ) {
                    session()->forget($key);
                }
            }

            return redirect()->route('auditee.dokumen')
                ->with('success', 'Import jawaban berhasil.');
        } catch (\Exception $e) {
            return redirect()->route('auditee.dokumen')
                ->with('error', 'Terjadi kesalahan saat mengimport jawaban: ' . $e->getMessage());
        }
    }
}
