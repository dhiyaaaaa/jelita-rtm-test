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
use App\Models\Prodi;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RtmRtl $rtmRtl): RedirectResponse
    {
        $rtmRtl->delete();

        return back()->with('success', 'Rencana Tindak Lanjut berhasil dihapus.');
    }

    public function show(RtmRtl $rtmRtl)
    {
        $auditee = Auditee::where(['user_id' => $this->user->id])->first();
        $user = $this->user->id;
        
        $allKriteria = Kriteria::all();
        
        $rtmRtl = RtmRtl::with([
            'status_rtm_rtl.kriteria',
            'status_rtm_rtl_prodi',
            'jadwal_audit',
            'rtm_jadwal',
            'user',
        ])->findOrFail($rtmRtl->id);

        $approval = $rtmRtl->user->first() ? $rtmRtl->user->first()->pivot->approve : null;

        $data = [
            'title' => 'Rencana Tindak Lanjut Hasil Audit' . ' ' . $rtmRtl->jadwal_audit->jadwal,
            'rtmRtl' => $rtmRtl,
            'allKriteria' => $allKriteria, 
            'auditee' => $auditee,
            'user' => $user,
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

        return redirect()->route('dekan.rtm-rtl.form', [
                'rtmRtl' => $rtmRtl->id,
                'kriteria' => $kriteria->id
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
        $auditeeId = $auditee ? $auditee->id : 'no_auditee';

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        if ($unit) {
             $temuan = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->when($rtmRtl->unit_id, function ($query) use ($rtmRtl) {
                    return $query->where('unit_id', $rtmRtl->unit_id);
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
                ->orderBy('form.instrumen_id', 'asc')
                ->get();
        } else {
            $prodiList = $fakultas ? Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray() : [];

            $temuan = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
                ->where('kriteria_id', $kriteria->id)
                ->where('fakultas_id', $rtmRtl->fakultas_id)
                ->with([
                    'form.instrumen.jabatan',
                    'form.jawaban_auditee',
                    'form.ptk_form_deskripsi',
                    'form.laporan_form',
                    'form.rtm_tindak_lanjut',
                    'kriteria',
                    'prodi',
                    'fakultas'
                ])
                ->join('form', 'jawaban_auditor.form_id', '=', 'form.id');
                
            $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'form.instrumen_id')
                ->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
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
                
                $catatanAuditor = $items->map(function($item) {
                    $catatan = '';
                    if ($item->prodi) {
                        $catatan .= "{$item->prodi->jenjang->nama}{$item->prodi->nama}: ";
                    }
                    $catatan .= $item->catatan ?? 'Tidak ada catatan';
                    return $catatan;
                })->implode("\n");

                $PtkDeskripsi = $items->flatMap(function($item) {
                    return $item->form->ptk_form_deskripsi->map(function($deskripsi) use ($item) {
                        $deskripsiTemuan = '';
                        if ($item->prodi) {
                            $deskripsiTemuan .= "{$item->prodi->jenjang->nama}{$item->prodi->nama}: ";
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
                            $kelebihanTemuan .= "{$item->prodi->jenjang->nama}{$item->prodi->nama}: ";
                        }
                        $kelebihanTemuan .= $kelebihan->kelebihan;
                        return $kelebihanTemuan;
                    });
                })->implode("\n");

                $firstItem->catatan_auditor = $catatanAuditor;
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
        
        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
        ->where('kriteria_id', $kriteria->id)
        ->get();

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
        $sessionKey = 'form_rtm_rtl-page_' . $request->query('page', 1) . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditeeId . '-kriteriaId_' . $kriteria->id;
        $sessionFormData = session()->get($sessionKey, []);
        // Log::info('RTM controller session data', $sessionFormData);


        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)
        ->where('kriteria_id', $kriteria->id)
        ->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuan' => $paginatedTemuan,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $sessionFormData,
            'kriteria' => $kriteria,
            'status' => $status,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'auditeeId' => $auditeeId,
            'auditee' => $auditee,
            'picData' => $picData,
            'jawabanAuditor' => $jawaban_auditor,
            'isDisabled' => !$auditee,
        ];

        return view('dekan.rtm.rtm_rtl.form', $data);
    }

    public function save_form(Request $request, string $rtmRtl, string $auditee, string $kriteria): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        $currentPage = $request->input('currentPage', 1);
        $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;

        $auditee = Auditee::where('user_id', Auth::id())->first();
        $auditeeId = $auditee?->id;

        
        $kriteria = Kriteria::findOrFail($kriteria);
        $validationRules = [];
        $validationMessages = [];
        $hasData = false;
        $formIds = [];

        // Bangun aturan validasi dinamis dan kumpulkan formIds
        foreach ($request->all() as $key => $value) {
            if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                $formId = $matches[1];
                $formIds[] = $formId;
                $hasData = true;

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
            RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl)
                ->where('kriteria_id', $kriteria->id)
                ->whereIn('form_id', array_unique($formIds))
                ->delete();

            // Simpan data baru
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];

                    foreach ($value as $item) {
                        if (!empty($item['tindakan']) && !empty($item['pic']) && !empty($item['waktu'])) {
                            [$userId, $jabatanId] = explode('|', $item['pic']);

                            RtmTindakLanjut::create([
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                                'user_id' => $userId,
                                'jabatan_id' => $jabatanId,
                                'tindakan' => $item['tindakan'],
                                'waktu' => $item['waktu'],
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


    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee, string $kriteria): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $errorPage = null;
        $validationErrors = [];

        // Validasi semua halaman
        for ($page = 1; $page <= $totalPages; $page++) {
            $sessionKey = 'form_rtm_rtl-page_' . $page . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
            $pageData = session($sessionKey, []);

            foreach ($pageData as $key => $value) {
                if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                    $formId = $matches[1];
                    
                    foreach ($value as $index => $item) {
                        if (empty($item['tindakan'])) {
                            $validationErrors["tindakan_$formId.$index.tindakan"] = 'Rencana tindakan harus diisi';
                            $errorPage = $errorPage ?? $page;
                        }
                        
                        if (empty($item['pic']) || !preg_match('/^.+\|.+$/', $item['pic'])) {
                            $validationErrors["tindakan_$formId.$index.pic"] = 'PIC harus dipilih';
                            $errorPage = $errorPage ?? $page;
                        }
                        
                        if (empty($item['waktu'])) {
                            $validationErrors["tindakan_$formId.$index.waktu"] = 'Target waktu harus diisi';
                            $errorPage = $errorPage ?? $page;
                        }
                    }
                }
            }
        }

        if (!empty($validationErrors)) {
            return redirect()
                ->route('dekan.rtm-rtl.form', [
                    'rtmRtl' => $rtmRtl->id, 
                    'kriteria' => $kriteria,
                    'page' => $errorPage
                ])
                ->withErrors($validationErrors)
                ->with('error_message', 'Harap lengkapi semua field yang wajib diisi');
        }

        DB::beginTransaction();
        try {
            RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
                ->where('kriteria_id', $kriteria)
                ->delete();

            // Kemudian simpan yang baru
            for ($page = 1; $page <= $totalPages; $page++) {
                $sessionKey = 'form_rtm_rtl-page_' . $page . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
                $pageData = session($sessionKey, []);

                foreach ($pageData as $key => $value) {
                    if (preg_match('/^tindakan_([a-zA-Z0-9-]+)$/', $key, $matches)) {
                        $formId = $matches[1];

                        foreach ($value as $item) {
                            if (!empty($item['tindakan']) && !empty($item['pic']) && !empty($item['waktu'])) {
                                [$userId, $jabatanId] = explode('|', $item['pic']);
                                
                                RtmTindakLanjut::create([
                                    'rtm_rtl_id' => $rtmRtl->id,
                                    'form_id' => $formId,
                                    'kriteria_id' => $kriteria,
                                    'user_id' => $userId,
                                    'jabatan_id' => $jabatanId,
                                    'tindakan' => $item['tindakan'],
                                    'waktu' => $item['waktu'],
                                    'auditee_id' => $auditee
                                ]);
                            }
                        }
                    }
                }
            }

            // Update status
            StatusRtmRtl::updateOrCreate(
                ['rtm_rtl_id' => $rtmRtl->id, 'kriteria_id' => $kriteria],
                ['status' => 'completed']
            );

            DB::commit();

            // Bersihkan session
            for ($page = 1; $page <= $totalPages; $page++) {
                $sessionKey = 'form_rtm_rtl-page_' . $page . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee . '-kriteriaId_' . $kriteria;
                session()->forget($sessionKey);
            }

            return redirect()
                ->route('dekan.rtm-rtl.show', $rtmRtl)
                ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RTMRTL Store Error: '.$e->getMessage());
            
            return back()
                ->with('error', 'Gagal menyimpan data: '.$e->getMessage())
                ->withInput();
        }
    }
}