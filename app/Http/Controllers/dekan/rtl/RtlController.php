<?php

namespace App\Http\Controllers\dekan\rtl;

use App\Http\Controllers\Controller;
use App\Models\JadwalAudit;
use App\Models\Rtl;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Unit;
use App\Models\Jabatan;
use App\Models\Auditee;
use App\Models\Kriteria;
use App\Models\JawabanAuditor;
use App\Models\RtlForm;
use App\Models\StatusRtl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class RtlController extends Controller
{
    protected $user;
    protected $jabatanUser;
    protected $auditee;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
        $this->auditee = Auditee::where(['user_id' => $this->user->id])->first();
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_audit_id' => 'required|exists:jadwal_audit,id',
        ]);
            
            $user = Auth::user();

            $fakultasId = $user->jabatan->first()->pivot->fakultas_id ?? null;
            $unitId = $user->jabatan->first()->pivot->unit_id ?? null;

            Rtl::updateOrCreate([
                'jadwal_audit_id' => $request->jadwal_audit_id,
            ],
            [
                'fakultas_id' => $fakultasId,
                'unit_id' => $unitId,
                'tgl' => Carbon::now()->toDateString(),
            ]);

        return response()->json([
            'success' => 'Tindak Lanjut RTL berhasil dibuat'
        ]);
    }

    public function show(Rtl $rtl)
    {
        $auditee = Auditee::where(['user_id' => $this->user->id])->first();
        $rtl = Rtl::with([
            'status_rtl.kriteria', 
            'auditee' => function ($query) use ($auditee) {
                $query->where('auditee_id', $auditee->id);
            },
            'jadwal_audit',
            'fakultas.prodi.auditee.user',
            'fakultas.prodi.auditor.user',
            'fakultas.auditor.user',
            'fakultas.auditee.user',
            'unit.auditor.user',
            'unit.auditee.user',
            ])->findOrFail($rtl->id);
        
        $items = [];

        if ($rtl->fakultas_id) {
            $items[] = [
                'nama' => $rtl->fakultas->nama,
                'auditee' => $rtl->fakultas->auditee->filter(function ($query) use ($rtl) {
                    return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                })->unique('id'),
                'auditor' => $rtl->fakultas->auditor->filter(function ($query) use ($rtl) {
                    return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                })->unique('id'),
            ];

            foreach ($rtl->fakultas->prodi as $prodi) {
                $items[] = [
                    'nama' => $prodi->nama,
                    'auditee' => $prodi->auditee->filter(function ($query) use ($rtl) {
                    return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                    })->unique('id'),
                    'auditor' => $prodi->auditor->filter(function ($query) use ($rtl) {
                        return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                    })->unique('id'),
                ]; 
            }
        } elseif ($rtl->unit_id) {
            $items[] = [
                'nama' => $rtl->unit->nama,
                'auditee' => $rtl->unit->auditee->filter(function ($query) use ($rtl) {
                    return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                })->unique('id'),
                'auditor' => $rtl->unit->auditor->filter(function ($query) use ($rtl) {
                    return $query->jadwal_audit_id == $rtl->jadwal_audit_id;
                })->unique('id'),
            ];
        }

        $data = [
            'title' => 'Tindak Lanjut Ptk',
            'rtl' => $rtl,
            'auditee' => $auditee,
            'items' => $items,
        ];

        return view('dekan.rtl.show', $data);
    }

    public function isi_rtl_form(Rtl $rtl, Kriteria $kriteria): RedirectResponse
    {

        $statusRtl = StatusRtl::updateOrCreate(
            [
                'rtl_id' => $rtl->id,
                'kriteria_id' => $kriteria->id,
            ],
            [
                'status' => 'in_progress',
            ]
        );

        return redirect()->route('dekan.rtl.form', ['rtl' => $rtl->id, 'kriteria' => $kriteria->id]);
    }

    public function form(Request $request, Rtl $rtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtl->jadwal_audit_id);

        $fakultas = $rtl->fakultas_id ? Fakultas::find($rtl->fakultas_id) : null;
        $unit = $rtl->unit_id ? Unit::find($rtl->unit_id) : null;

        $jabatanUserId = optional($this->user->jabatan->first())->id;
        $jabatanUser = Jabatan::find($jabatanUserId);

        $auditee = $this->auditee;
        $auditeeId = $auditee ? $auditee->id : 'no_auditee';

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtl->jadwal_audit_id])->first();

        $kriteria = $request->query('kriteria');
        $kriteriaTemuan = ['Belum Memenuhi', 'Memenuhi', 'Melampaui'];
        if (!in_array($kriteria, $kriteriaTemuan)) {
            return back()->with('error', 'Tidak ada temuan dengan kriteria ini.');
        }

        $unit = $rtl->unit_id ? Unit::find($rtl->unit_id) : null;

        if ($unit) {
            $temuan = JawabanAuditor::where('jadwal_audit_id', $rtl->jadwal_audit_id)
                ->when($rtl->unit_id, function ($query) use ($rtl) {
                    return $query->where('unit_id', $rtl->unit_id);
                })
                ->whereHas('kriteria', function ($query) use ($kriteria) {
                    $query->where('nama', $kriteria);
                })
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'kriteria',
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->orderByRaw("
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM instrumen_jabatan
                            WHERE instrumen_jabatan.jabatan_id = ?
                            AND instrumen_jabatan.instrumen_id = form.instrumen_id
                        ) THEN 1
                        ELSE 2
                    END
                ", [$jabatanUser->id])
                ->orderBy('kriteria_id', 'asc')
                ->orderBy('form.instrumen_id', 'asc')
                ->get();
        } else {
            $prodiList = $fakultas ? Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray() : [];

            $temuan = JawabanAuditor::where('jadwal_audit_id', $rtl->jadwal_audit_id)
                ->when($rtl->fakultas_id, function ($query) use ($rtl) {
                    return $query->where('fakultas_id', $rtl->fakultas_id);
                })
                ->whereHas('kriteria', function ($query) use ($kriteria) {
                    $query->where('nama', $kriteria);
                })
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'kriteria',
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->orderByRaw("
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM instrumen_jabatan
                            WHERE instrumen_jabatan.jabatan_id = ?
                            AND instrumen_jabatan.instrumen_id = form.instrumen_id
                        ) THEN 1
                        ELSE 2
                    END
                ", [$jabatanUser->id])
                ->orderBy('kriteria_id', 'asc')
                ->orderBy('form.instrumen_id', 'asc')
                ->get();

            $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'jawaban_auditor.catatan')
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
                ->where('jawaban_auditor.jadwal_audit_id', $rtl->jadwal_audit_id)
                ->whereIn('jawaban_auditor.prodi_id', $prodiList)
                ->whereHas('kriteria', function ($query) use ($kriteria) {
                    $query->where('nama', $kriteria);
                })
                ->with([
                    'prodi', 
                    'form.jawaban_auditor', 
                    'form.instrumen', 
                    'kriteria',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                ])
                ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
                ->orderBy('jawaban_auditor.kriteria_id', 'asc')
                ->get();

            $temuan = $temuan->merge($temuanProdi);
        }

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

        $jawabanRtl = RtlForm::where('rtl_id', $rtl->id)->get();

        $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditeeId;
        $sessionFormData = session()->get($sessionKey, []);

        // Struktur data yang lebih terorganisir
        $formData = [];
        foreach ($jawabanRtl as $jawaban) {
            if (!isset($formData[$jawaban->form_id])) {
                $formData[$jawaban->form_id] = [
                    'tindakan' => [],
                ];
            }
            $formData[$jawaban->form_id]['tindakan'][] = [
                'tindakan' => $jawaban->tindakan,
                'bukti' => $jawaban->bukti,
            ];
        }
        if (!empty($sessionFormData)) {
            $formData = array_merge($formData, $sessionFormData);
        }

        $kriteria = $request->query('kriteria');
        $kriteriaTemuan = ['Belum Memenuhi', 'Memenuhi', 'Melampaui'];

        $kriteriaTindakan = Kriteria::where('nama', $kriteria)->first();

        $status = StatusRtl::where('rtl_id', $rtl->id)
        ->where('kriteria_id', $kriteriaTindakan->id)
        ->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'jadwal' => $jadwal,
            'paginatedTemuan' => $paginatedTemuan,
            'temuan' => $temuan,
            'jawabanRtl' => $jawabanRtl,
            'sessionFormData' => $formData,
            'currentPage'=> $currentPage,
            'kriteriaTemuan' => $kriteria,
            'kriteria' => $kriteriaTindakan,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'auditee' => $auditee,
            'auditeeId' => $auditeeId,
            'status' => $status,
            'rtl' => $rtl,
            'jawabanAuditor' => $jawaban_auditor,
            'isDisabled' => !$this->auditee,
        ];

        return view('dekan.rtl.form', $data);
    }


    public function save_form(Request $request, string $rtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        try {
            $currentPage = $request->input('currentPage', 1);
            $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl . '-auditeeId_' . $auditee;

            $errors = [];
            $hasData = false;

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^(tindakan_|bukti_)([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[2];

                    // Validasi Item
                    foreach ($value as $index => $item) {
                        if (empty($item['tindakan']) || empty($item['bukti'])) {
                            $errors["tindakan_{$formId}.{$index}.tindakan"] = ['Tindakan dan bukti harus diisi.'];
                            continue;
                        }
                        if (!empty($item['bukti']) && !filter_var($item['bukti'], FILTER_VALIDATE_URL)) {
                            $errors["tindakan_{$formId}.{$index}.bukti"] =['Bukti harus berupa URL valid (contoh: https://example.com)'];
                                continue;
                        }
                        $hasData = true;
                    }
                }
            }
            if (!empty($errors)) {
                return response()->json([
                    'message' => 'Data tidak lengkap',
                    'errors' => $errors
                ], 422);
            }
    
            if (!$hasData) {
                return response()->json([
                    'message' => 'Tidak ada data yang valid untuk disimpan.'
                ], 422);
            }

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^(tindakan_|bukti_)([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[2];

                    $kriteria = Kriteria::whereHas('jawaban_auditor', function ($query) use ($formId) {
                        $query->where('form_id', $formId);
                    })->first();

                    $existingData = RtlForm::where('rtl_id', $rtl)
                        ->where('form_id', $formId)
                        ->get();
                    
                    $submittedData = collect($value)->filter(fn($item) => 
                        !empty($item['tindakan']) && !empty($item['bukti'])
                    );

                    // Hapus data yang tidak ada di submit
                    $toDelete = $existingData->filter(function($item) use ($submittedData) {
                        return !$submittedData->contains('tindakan', $item->tindakan);
                    });

                    foreach ($toDelete as $item) {
                        $item->delete();
                    }

                    // Simpan/update data
                    foreach ($submittedData as $item) {
                        RtlForm::updateOrCreate(
                            [
                                'rtl_id' => $rtl,
                                'form_id' => $formId,
                                'tindakan' => $item['tindakan'],
                            ],
                            [
                                'auditee_id' => $auditee,
                                'kriteria_id' => $kriteria->id,
                                'bukti' => $item['bukti'],
                            ]
                        );
                    }

                }
            }
            // Hapus session setelah berhasil disimpan ke database
            session()->forget($sessionKey);

            return response()->json(['message' => 'Data berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            Log::error('Error saving RTL form: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store_form(Request $request, Rtl $rtl, string $auditee): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $kriteriaId = $request->input('kriteria');
        $allData = [];
        $rules = [];
        $messages = [];
        $missingFields = [];

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_rtl-page_' . $i . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditee;
            $pageData = session($sessionKey, []);


            foreach ($pageData as $formId => $formData) {
                if (!isset($allData[$formId])) {
                    $allData[$formId] = ['tindakan' => []];
                }

                foreach ($formData['tindakan'] as $index => $tindakan) {
                    $rules["tindakan_{$formId}.{$index}.tindakan"] = 'required';
                    $rules["tindakan_{$formId}.{$index}.bukti"] = 'required|url';

                    $messages["tindakan_{$formId}.{$index}.tindakan.required"] = 'Tindakan harus diisi';
                    $messages["tindakan_{$formId}.{$index}.bukti.required"] = 'Bukti harus diisi';
                    $messages["tindakan_{$formId}.{$index}.bukti.url"] = 'Bukti harus berupa URL valid';

                    if (empty($tindakan['tindakan'])) {
                        $missingFields[] = "Tindakan di halaman $i kosong";
                    }
                    if (empty($tindakan['bukti']) || !filter_var($tindakan['bukti'], FILTER_VALIDATE_URL)) {
                        $missingFields[] = "Bukti di halaman $i tidak valid";
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

        // Simpan data
        DB::beginTransaction();
        try {
            foreach ($allData as $formId => $formData) {
                // Hapus data lama
                RtlForm::where('rtl_id', $rtl->id)
                    ->where('form_id', $formId)
                    ->delete();

                // Simpan data baru
                foreach ($formData['tindakan'] as $tindakan) {
                    RtlForm::create([
                        'rtl_id' => $rtl->id,
                        'form_id' => $formId,
                        'auditee_id' => $auditee,
                        'kriteria_id' => $kriteriaId,
                        'tindakan' => $tindakan['tindakan'],
                        'bukti' => $tindakan['bukti']
                    ]);
                }
            }

            // Update status
            StatusRtl::updateOrCreate(
                [
                    'rtl_id' => $rtl->id,
                    'kriteria_id' => $kriteriaId
                ],
                ['status' => 'completed']
            );

            DB::commit();

            // Hapus semua session setelah simpan sukses
            for ($i = 1; $i <= $totalPages; $i++) {
                $sessionKey = 'form_rtl-page_' . $i . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditee;
                session()->forget($sessionKey);
            }

            return redirect()->route('dekan.rtl.index')
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving RTL data: ' . $e->getMessage());

            return redirect()
                ->route('dekan.rtl.form', [
                    'rtl' => $rtl->id,
                    'kriteria' => $kriteriaId
                ])
                ->with('error_message', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}