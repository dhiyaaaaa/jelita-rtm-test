<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtlProdi;
use App\Models\Auditee;
use App\Models\Kriteria;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\JawabanAuditor;
use App\Models\User;
use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

class RtmRtlProdiController extends Controller
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

    public function isi_rtm_rtl_prodi(RtmRtl $rtmRtl): RedirectResponse
    {
        StatusRtmRtlProdi::updateOrCreate(
            [
                'rtm_rtl_id' => $rtmRtl->id,
            ],
            ['status' => 'in_progress']
        );

        return redirect()->route('dekan.rtm-rtl.form', [
            'rtmRtl' => $rtmRtl, 
        ]);
    }

    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrfail($rtmRtl->jadwal_audit_id);
        $fakultas = $rtmRtl->fakultas;
        
        $selectedKriteria = $request->input('kriteria_ids', []);
        $kriteriaOptions = Kriteria::all();

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $auditee = $this->auditee;
        $auditeeId = $auditee ? $auditee->id : 'no_auditee';
        $prodiList = Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray();

        $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'jawaban_auditor.catatan')
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->where('jawaban_auditor.jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->whereIn('jawaban_auditor.prodi_id', $prodiList)
            ->when(!empty($selectedKriteria), function($query) use ($selectedKriteria) {
                return $query->whereIn('jawaban_auditor.kriteria_id', $selectedKriteria);
            })
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->orderBy('jawaban_auditor.kriteria_id', 'asc')
            ->with([
                'prodi', 
                'form.jawaban_auditor', 
                'form.instrumen', 
                'kriteria',
                'form.ptk_form_deskripsi',
                'form.laporan_form'])
            ->get();

        $groupedTemuanProdi = $temuanProdi->groupBy('form.id');
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedTemuanProdi = new LengthAwarePaginator(
            $temuanProdi->slice($offset, $perPage),
            $temuanProdi->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        // Cek jika ada kriteria dipilih tapi tidak ada hasil
        if (!empty($selectedKriteria) && $temuanProdi->isEmpty()) {
            return redirect()
                ->route('dekan.rtm-rtl.form_prodi', $rtmRtl->id)
                ->with('warning_message', 'Tidak ditemukan temuan yang sesuai dengan kriteria yang dipilih');
        }

        //PIC data 
        $picData = DB::table('jabatan_user')
        ->join('jabatan', 'jabatan_user.jabatan_id', '=', 'jabatan.id')
        ->join('users', 'jabatan_user.user_id', '=', 'users.id')
        ->leftJoin('prodi', 'jabatan_user.prodi_id', '=', 'prodi.id')
        ->leftJoin('fakultas', 'jabatan_user.fakultas_id', '=', 'fakultas.id')
        ->leftJoin('unit', 'jabatan_user.unit_id', '=', 'unit.id')
        ->where(function ($query) use ($fakultas) {
            if ($fakultas) {
                $query->where(function ($q) use ($fakultas) {
                    $q->where('prodi.fakultas_id', $fakultas->id)
                    ->orWhere('jabatan_user.fakultas_id', $fakultas->id);
                });
                $query->orWhereNotNull('jabatan_user.unit_id');
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


        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
            ->with(['kriteria', 'user', 'jabatan'])
            ->get()
            ->groupBy('form_id');

        $sessionKey = 'form_rtm_rtl_prodi-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditeeId;
        $sessionFormData = session()->get($sessionKey, []);

        $formData = [];
        
        foreach ($jawabanTindakLanjut as $formId => $items) {
            foreach ($items as $item) {
                if (!isset($formData[$formId][$item->kriteria_id])) {
                    $formData[$formId][$item->kriteria_id] = ['tindakan' => []];
                }
                
                $formData[$formId][$item->kriteria_id]['tindakan'][] = [
                    'tindakan' => $item->tindakan,
                    'pic' => $item->user_id . '|' . $item->jabatan_id, // Format konsisten
                    'waktu' => $item->waktu
                ];
            }
        }
        
        foreach ($sessionFormData as $formId => $kriteriaData) {
            if (!isset($formData[$formId])) {
                $formData[$formId] = [];
            }
            
            foreach ($kriteriaData as $kriteriaId => $data) {
                $formData[$formId][$kriteriaId] = $data;
            }
        }      

        $status = StatusRtmRtlProdi::where('rtm_rtl_id', $rtmRtl->id)->first();


        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Prodi',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanProdi' => $paginatedTemuanProdi,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $formData,
            'groupedTemuanProdi' => $groupedTemuanProdi,
            'temuanProdi' => $temuanProdi,
            'jawabanAuditor' => $jawaban_auditor,
            'status' => $status,
            'auditee' => $auditee,
            'auditeeId' => $auditeeId,
            'isDisabled' => !$auditee,
            'kriteriaOptions' => $kriteriaOptions,
            'selectedKriteria' => $selectedKriteria,
            'picData' => $picData,
        ];

        return view('dekan.rtm.rtm_rtl.form_prodi', $data);
    }

    public function save_form(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }
    
        try {
            $currentPage = $request->input('currentPage', 1);
            $auditee = Auditee::where('user_id', $this->user->id)->first();
            $auditeeId = $auditee ? $auditee->id : null;
            $formId = $request->input('formId');
            $kriteriaIds = json_decode($request->input('kriteriaIds', '[]'), true);
            $kriteriaIds = is_array($kriteriaIds) ? array_map('intval', $kriteriaIds) : [];

            // Validasi kriteriaIds
            if (empty($kriteriaIds)) {
                return response()->json(['error' => 'Kriteria IDs tidak valid'], 400);
            }

            $rules = [];
            $messages = [];
    
            // Validate all tindakan inputs
            foreach ($kriteriaIds as $kriteriaId) {
                $keyPrefix = "tindakan_{$formId}_{$kriteriaId}";
                
                if ($request->has($keyPrefix) && is_array($request->input($keyPrefix))) {
                    foreach ($request->input($keyPrefix) as $index => $item) {
                        $rules["{$keyPrefix}.{$index}.tindakan"] = 'required';
                        $rules["{$keyPrefix}.{$index}.pic"] = ['required', 'regex:/^.+\|.+$/'];
                        $rules["{$keyPrefix}.{$index}.waktu"] = 'required';
    
                        $messages["{$keyPrefix}.{$index}.tindakan.required"] = "Rencana tindakan untuk kriteria {$kriteriaId} pada baris ke-" . ($index + 1) . " wajib diisi.";
                        $messages["{$keyPrefix}.{$index}.pic.required"] = "PIC untuk kriteria {$kriteriaId} pada baris ke-" . ($index + 1) . " wajib dipilih.";
                        $messages["{$keyPrefix}.{$index}.pic.regex"] = "Format PIC tidak valid.";
                        $messages["{$keyPrefix}.{$index}.waktu.required"] = "Waktu untuk kriteria {$kriteriaId} pada baris ke-" . ($index + 1) . " wajib diisi.";
                    }
                }
            }
            
            // Validate input
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            DB::beginTransaction();

            foreach ($kriteriaIds as $kriteriaId) {
                // Delete existing actions for this form and criteria
                RtmTindakLanjut::where([
                    'rtm_rtl_id' => $rtmRtl,
                    'form_id' => $formId,
                    'kriteria_id' => $kriteriaId
                ])->delete();
    
                // Process tindakan input for this specific form and criteria
                $inputKey = "tindakan_{$formId}_{$kriteriaId}";
                $tindakanData = $request->input($inputKey, []);
        
                
                    foreach ($tindakanData as $item) {
                        // Skip empty entries
                        if (empty($item['tindakan']) || empty($item['pic']) || empty($item['waktu'])) {
                            continue;
                        }
        
                            [$userId, $jabatanId] = explode('|', $item['pic']);

                            // Create the action record
                            RtmTindakLanjut::create([
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                                'kriteria_id' => $kriteriaId,
                                'auditee_id' => $auditeeId,
                                'tindakan' => $item['tindakan'],
                                'user_id' => $userId,
                                'jabatan_id' => $jabatanId,
                                'waktu' => $item['waktu'],
                            ]);
                        
                    }
                
            }
    
            DB::commit();
    
            return response()->json(['message' => 'Data berhasil disimpan.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saving RTL data: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $auditeeId = $request->input('auditee');
        $allData = [];
        $missingFields = [];

        // Kumpulkan semua data dari session
        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_rtm_rtl_prodi-page_' . $i . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee;
            $pageData = session($sessionKey, []);

            foreach ($pageData as $formKriteriaKey => $formData) {
                [$formId, $kriteriaId] = explode('_', $formKriteriaKey);
                
                if (!isset($allData[$formId])) {
                    $allData[$formId] = [];
                }
                
                $allData[$formId][$kriteriaId] = $formData['tindakan'] ?? [];
            }
        }

        // Validasi
        foreach ($allData as $formId => $kriteriaData) {
            foreach ($kriteriaData as $kriteriaId => $tindakanData) {
                foreach ($tindakanData as $index => $tindakan) {
                    if (empty($tindakan['tindakan'])) {
                        $missingFields[] = "Rencana tindakan kosong untuk Form $formId, Kriteria $kriteriaId, Baris " . ($index + 1);
                    }
                    if (empty($tindakan['pic'])) {
                        $missingFields[] = "PIC kosong untuk Form $formId, Kriteria $kriteriaId, Baris " . ($index + 1);
                    }
                    if (empty($tindakan['waktu'])) {
                        $missingFields[] = "Target waktu kosong untuk Form $formId, Kriteria $kriteriaId, Baris " . ($index + 1);
                    }
                }
            }
        }

        if (!empty($missingFields)) {
            return redirect()
                ->back()
                ->with('error_message', 'Ada data yang belum lengkap: ' . implode(', ', $missingFields));
        }

        DB::beginTransaction();
        try {
            // Hapus semua data lama untuk RTM ini
            RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->delete();

            // Simpan semua data baru
            foreach ($allData as $formId => $kriteriaData) {
                foreach ($kriteriaData as $kriteriaId => $tindakanData) {
                    foreach ($tindakanData as $tindakan) {
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
                            'user_id' => $picUserId,
                            'jabatan_id' => $picJabatanId,
                            'waktu' => $tindakan['waktu'],
                        ]);
                    }
                }
            }

            // Update status menjadi completed
            StatusRtmRtlProdi::updateOrCreate(
                ['rtm_rtl_id' => $rtmRtl->id],
                ['status' => 'completed']
            );

            DB::commit();

            // Hapus semua session data
            for ($i = 1; $i <= $totalPages; $i++) {
                $sessionKey = 'form_rtm_rtl_prodi-page_' . $i . '-rtmRtlId_' . $rtmRtl->id;
                session()->forget($sessionKey);
            }

            return redirect()
                ->route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id])
                ->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving Tindak Lanjut Prodi data: ' . $e->getMessage());

            return redirect()
                ->route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id])
                ->with('error_message', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}