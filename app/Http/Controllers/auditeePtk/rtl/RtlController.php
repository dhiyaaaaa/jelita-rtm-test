<?php

namespace App\Http\Controllers\auditeePtk\rtl;

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

    public function destroy(Rtl $rtl): RedirectResponse
    {
        $rtl->delete();

        return back()->with('success', 'Form tindak lanjut berhasil dihapus.');
    }

    public function show(Rtl $rtl)
    {
        $auditee = Auditee::where(['user_id' => $this->user->id])->first();
        $fakultas = $this->user->fakultas->first();
        $unit = $this->user->unit->first();

        $rtl = Rtl::with([
            'status_rtl.kriteria', 
            'auditee',
            'jadwal_audit',
            'fakultas.prodi.auditee.user',
            'fakultas.prodi.auditor.user',
            'fakultas.auditor.user',
            'fakultas.auditee.user',
            'unit.auditor.user',
            'unit.auditee.user',
            ])->findOrFail($rtl->id);
        
        $items = [];

        $auditorPtk = collect();
        if ($rtl->jadwal_audit->auditee_auditor_ptk->isNotEmpty()) {
            $auditorPtk = $rtl->jadwal_audit->auditee_auditor_ptk
                ->when($fakultas, function($collection) use ($fakultas) {
                    return $collection->where('fakultas_id', $fakultas->id);
                })
                ->when($unit, function($collection) use ($unit) {
                    return $collection->where('unit_id', $unit->id);
                });
        }

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

        $approval = $rtl->auditee->first() ? $rtl->auditee->first()->pivot->approve : null;


        $allKriteria = Kriteria::all();

        $data = [
            'title' => 'Tindak Lanjut Ptk',
            'rtl' => $rtl,
            'auditee' => $auditee,
            'items' => $items,
            'allKriteria' => $allKriteria,
            'auditorPtk' => $auditorPtk,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'approval' => $approval,
        ];

        return view('dekan.rtl.show', $data);
    }

    public function isi_rtl_form(Rtl $rtl, $kriteria)
    {
        $kriteria = Kriteria::findOrFail($kriteria);

        StatusRtl::updateOrCreate(
            [
                'rtl_id' => $rtl->id,
                'kriteria_id' => $kriteria->id,
            ],
            ['status' => 'in_progress']
        );
        
        return redirect()->route('dekan.rtl.form', [
                'rtl' => $rtl->id,
                'kriteria' => $kriteria->id
            ]);
    }

    public function form(Request $request, Rtl $rtl, $kriteria): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtl->jadwal_audit_id);

        $jabatanUserId = optional($this->user->jabatan->first())->id;
        $jabatanUser = Jabatan::find($jabatanUserId);

        $auditee = $this->auditee;
        $auditeeId = $auditee ? $auditee->id : 'no_auditee';

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtl->jadwal_audit_id])->first();

        $kriteria = Kriteria::findOrFail($kriteria);
        $auditee = $this->auditee;

        $fakultas = $rtl->fakultas_id ? Fakultas::find($rtl->fakultas_id) : null;
        $unit = $rtl->unit_id ? Unit::find($rtl->unit_id) : null;

        if ($unit) {
            $temuan = JawabanAuditor::where('jadwal_audit_id', $rtl->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->when($rtl->unit_id, function ($query) use ($rtl) {
                    return $query->where('unit_id', $rtl->unit_id);
                })
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'form.rtm_rtl_form',
                    'kriteria',
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->orderBy('form.instrumen_id', 'asc')
                ->get();
        } else {
            $prodiList = $fakultas ? Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray() : [];

            $temuan = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $rtl->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->where('fakultas_id', $rtl->fakultas_id)
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'form.rtm_rtl_form',
                    'kriteria',
                    'prodi',
                    'fakultas'
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id');
                
            $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $rtl->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->whereIn('prodi_id', $prodiList)
                ->with([
                    'prodi', 
                    'form.jawaban_auditor', 
                    'form.instrumen', 
                    'kriteria',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'form.rtm_rtl_form',
                    'fakultas'
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
                ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id');

            $allTemuan = $temuan->union($temuanProdi)
            ->orderBy('instrumen_id', 'asc')
            ->get();

            $groupedTemuan = $allTemuan->groupBy('form_id');
            $temuan = collect();

            foreach ($groupedTemuan as $formId => $items) {
                $firstItem = $items->first();
                
                // $catatanAuditor = $items->map(function($item) {
                //     $catatan = '';
                //     if ($item->prodi) {
                //         $catatan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                //     }
                //     $catatan .= $item->catatan ?? 'Tidak ada catatan';
                //     return $catatan;
                // })->implode("\n");

                $PtkDeskripsi = $items->flatMap(function($item) {
                    return $item->form->ptk_form_deskripsi->map(function($deskripsi) use ($item) {
                        $deskripsiTemuan = '';
                        if ($item->prodi) {
                            $deskripsiTemuan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                        }
                        $deskripsiTemuan .= $deskripsi->deskripsi;
                        if ($deskripsi->form->ptk_form->first()) {
                            $deskripsiTemuan .= " ({$deskripsi->form->ptk_form->first()->kategori_temuan})";
                        }
                        return $deskripsiTemuan;
                    });
                })->implode("\n");

                $LaporanKelebihan = $items->flatMap(function($item) {
                    return $item->form->laporan_form->map(function($kelebihan) use ($item) {
                        $kelebihanTemuan = '';
                        if ($item->prodi) {
                            $kelebihanTemuan .= "{$item->prodi->jenjang->nama} {$item->prodi->nama}: ";
                        }
                        $kelebihanTemuan .= $kelebihan->kelebihan;
                        return $kelebihanTemuan;
                    });
                })->implode("\n");

                //$firstItem->catatan_auditor = $catatanAuditor;
                $firstItem->deskripsi = $PtkDeskripsi;
                $firstItem->kelebihan = $LaporanKelebihan;
                
                $temuan->push($firstItem);
            }
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

        $jawabanRtl = RtlForm::where('rtl_id', $rtl->id)
        ->where('kriteria_id', $kriteria->id)
        ->get();

        $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditeeId . '-kriteriaId_' . $kriteria->id;
        $sessionFormData = session()->get($sessionKey, []);

        // $formData = [];
        // foreach ($jawabanRtl as $jawaban) {
        //     if (!isset($formData[$jawaban->form_id])) {
        //         $formData[$jawaban->form_id] = [
        //             'tindakan' => [],
        //         ];
        //     }
        //     $formData[$jawaban->form_id]['tindakan'][] = [
        //         'tindakan' => $jawaban->tindakan,
        //         'bukti' => $jawaban->bukti,
        //     ];
        // }
        // if (!empty($sessionFormData)) {
        //     $formData = array_merge($formData, $sessionFormData);
        // }

        $status = StatusRtl::where('rtl_id', $rtl->id)
        ->where('kriteria_id', $kriteria->id)
        ->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit',
            'jadwal' => $jadwal,
            'paginatedTemuan' => $paginatedTemuan,
            'temuan' => $temuan,
            'jawabanRtl' => $jawabanRtl,
            'sessionFormData' => $sessionFormData,
            'currentPage'=> $currentPage,
            'kriteria' => $kriteria,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'auditee' => $auditee,
            'auditeeId' => $auditeeId,
            'status' => $status,
            'rtl' => $rtl,
            'jawabanAuditor' => $jawaban_auditor,
            'isDisabled' => !$auditee,
        ];

        return view('dekan.rtl.form', $data);
    }


    public function save_form(Request $request, string $rtl, string $auditee, string $kriteria): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $currentPage = $request->input('currentPage', 1);
        $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;;

        $auditee = Auditee::where('user_id', Auth::id())->first();
        $auditeeId = $auditee?->id;

        $kriteria = Kriteria::findOrFail($kriteria);

        $validationRules = [];
        $validationMessages = [];
        $hasData = false;
        $formIds = [];

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    $formIds[] = $formId;
                    $hasData = true;

                    foreach ($value as $index => $item) {
                        $validationRules["$key.$index.tindakan"] = 'required';
                        $validationRules["$key.$index.bukti"] = 'required';

                        $validationMessages["$key.$index.tindakan.required"] = 'Rencana tindakan harus diisi';
                        $validationMessages["$key.$index.bukti.required"] = 'Bukti harus diisi';
                    }
                }
            }

            if (!$hasData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data yang valid untuk disimpan.'
                ], 422);
            }

            $validator = Validator::make($request->all(), $validationRules, $validationMessages);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();
            try {
                // Hapus semua tindakan untuk kriteria ini dan form yang terkait
                RtlForm::where('rtl_id', $rtl)
                    ->where('kriteria_id', $kriteria->id)
                    ->whereIn('form_id', array_unique($formIds))
                    ->delete();

                // Simpan data baru
                foreach ($request->all() as $key => $value) {
                    if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                        $formId = $matches[1];

                        foreach ($value as $item) {
                            if (!empty($item['tindakan']) && !empty($item['bukti'])) {
                                
                                RtlForm::create([
                                    'rtl_id' => $rtl,
                                    'form_id' => $formId,
                                    'tindakan' => $item['tindakan'],
                                    'bukti' => $item['bukti'],
                                    'kriteria_id' => $kriteria->id,
                                    'auditee_id' => $auditeeId
                                ]);
                            }
                        }
                    }
                }

                DB::commit();

                // Update session setelah penyimpanan berhasil
                $request->session()->put($sessionKey, $request->all());

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

    public function store_form(Request $request, Rtl $rtl, string $auditee, string $kriteria): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $errorPage = null;
        $validationErrors = [];
        //dd(session()->all());

        for ($page = 1; $page <= $totalPages; $page++) {
            $sessionKey = 'form_rtl-page_' . $page . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
            $pageData = session($sessionKey, []);


            foreach ($pageData as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];

                    foreach ($value as $index => $item) {
                        if (empty($item['tindakan'])) {
                            $validationErrors["tindakan_$formId.$index.tindakan"] = 'Harus mengisi tindakan yang sudah dilakukan.';
                            $errorPage = $errorPage ?? $page;
                        }
                        
                        if (empty($item['bukti']) || !preg_match('/^(http|https):\/\/.+/', $item['bukti'])) {
                            $validationErrors["tindakan_$formId.$index.bukti"] = 'Harus mengisi bukti(link) tindakan yang sesuai (harus dimulai dengan http:// atau https://)';
                            $errorPage = $errorPage ?? $page;
                        }
                    }
                }
            }
        }

        if (!empty($validationErrors)) {
            return redirect()
                ->route('dekan.rtl.form', [
                    'rtl' => $rtl->id, 
                    'kriteria' => $kriteria,
                    'page' => $errorPage
                ])
                ->withErrors($validationErrors)
                ->with('error_message', 'Harap lengkapi semua field yang wajib diisi');
        }

        // Simpan data
        DB::beginTransaction();
        try {
            RtlForm::where('rtl_id', $rtl->id)
                ->where('kriteria_id', $kriteria)
                ->delete();

            for ($page = 1; $page <= $totalPages; $page++) {
                $sessionKey = 'form_rtl-page_' . $page . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
                $pageData = session($sessionKey, []);

                // Log::debug('Page Data:', [$sessionKey => $pageData]); // Debugging

                foreach ($pageData as $key => $value) {
                    if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                        $formId = $matches[1];

                        foreach ($value as $item) {
                            if (!empty($item['tindakan']) && !empty($item['bukti'])) {
                                
                                RtlForm::create([
                                    'rtl_id' => $rtl->id,
                                    'form_id' => $formId,
                                    'kriteria_id' => $kriteria,
                                    'tindakan' => $item['tindakan'],
                                    'bukti' => $item['bukti'],
                                    'auditee_id' => $auditee
                                ]);
                            }
                        }
                    }
                }
            }

            // Update status
            StatusRtl::updateOrCreate(
                [
                    'rtl_id' => $rtl->id,
                    'kriteria_id' => $kriteria,
                ],
                ['status' => 'completed']
            );

            DB::commit();

            for ($page = 1; $page <= $totalPages; $page++) {
                $sessionKey = 'form_rtl-page_' . $page . '-rtlId_' . $rtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
                session()->forget($sessionKey);
            }

            return redirect()->route('dekan.rtl.show', $rtl)
                ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving RTL data: ' . $e->getMessage());

            return back()
                ->with('error', 'Gagal menyimpan data: '.$e->getMessage())
                ->withInput();
        }
    }
}