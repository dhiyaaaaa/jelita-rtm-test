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

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
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

    public function isi_rtm_rtl(string $rtmRtl)
    {
        StatusRtmRtl::updateOrCreate(
            ['rtm_rtl_id' => $rtmRtl],
            ['status' => 'in_progress']
        );

        return redirect()->route('dekan.rtm-rtl.form', [
            'rtmRtl' => $rtmRtl,
        ]);
    }

    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);

        $fakultas = $rtmRtl->fakultas_id ? Fakultas::find($rtmRtl->fakultas_id) : null;
        $unit = $rtmRtl->unit_id ? Unit::find($rtmRtl->unit_id) : null;

        $jabatanUserId = optional($this->user->jabatan->first())->id;
        $jabatanUser = Jabatan::find($jabatanUserId);
        $auditee = Auditee::where(['user_id' => $this->user->id])->first();

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $temuanFakultas = JawabanAuditor::where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->when($rtmRtl->fakultas_id, function ($query) use ($rtmRtl) {
                return $query->where('fakultas_id', $rtmRtl->fakultas_id);
            })
            ->when($rtmRtl->unit_id, function ($query) use ($rtmRtl) {
                return $query->where('unit_id', $rtmRtl->unit_id);
            })
            ->with([
                'form.instrumen.jabatan',
                'form.jawaban_auditee',
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


        $perPage = 10;
        $currentPage = $request->query('page', 1);

        $paginatedTemuanFakultas = new LengthAwarePaginator(
            $temuanFakultas->forPage($currentPage, $perPage),
            $temuanFakultas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->get();

        // Ambil data dari session
        $sessionKey = 'form_rtm_rtl-page_' . $request->query('page', 1) . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee->id;
        $sessionFormData = session()->get($sessionKey, []);
        Log::info('RTM controller session data', $sessionFormData);
        // Gabungkan data dari database dan session
        $formData = [];

        // If sessionFormData is not empty, use it
        if (!empty($sessionFormData)) {
            $formData = $sessionFormData;
        } else {
            // If sessionFormData is empty, use data from the database
            foreach ($jawabanTindakLanjut as $jawaban) {
                if (!isset($formData[$jawaban->form_id])) {
                    $formData[$jawaban->form_id] = [
                        'tindakan' => [],
                    ];
                }
                $formData[$jawaban->form_id]['tindakan'][] = [
                    'tindakan' => $jawaban->tindakan,
                    'pic' => $jawaban->pic,
                    'waktu' => $jawaban->waktu,
                ];
            }
        }

        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $formData,
            'status' => $status,
            'fakultas' => $fakultas,
            'unit' => $unit,
            'auditee' => $auditee,
            'temuanFakultas' => $temuanFakultas,
            'jawabanAuditor' => $jawaban_auditor,
        ];

        return view('dekan.rtm.rtm_rtl.form', $data);
    }

    public function save_form(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        $currentPage = $request->input('currentPage', 1);

        $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;
        Log::info($sessionKey);
        try {
            foreach ($request->all() as $key => $value) {

                if (preg_match('/^(tindakan_|pic_|waktu_)([a-zA-Z0-9-]+)$/', $key, $matches)) {

                    $formId = $matches[2];
                    $existRtm = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl)
                        ->where('form_id', $formId)
                        ->pluck('tindakan', 'id')
                        ->toArray();

                    // dd($existRtm);
                    $kriteria = Kriteria::whereHas('jawaban_auditor', function ($query) use ($formId) {
                        $query->where('form_id', $formId);
                    })->first();

                    // dd($kriteria);
                    if (!is_array($value)) {
                        return response()->json(['message' => 'Invalid input data format'], 422);
                    }

                    $values = array_filter($value, fn($item) => isset($item['tindakan'], $item['pic'], $item['waktu']));

                    if (empty($values)) {
                        return response()->json(['message' => 'Data tidak boleh kosong.'], 422);
                    }

                    foreach ($existRtm as $id => $tindakan) {
                        if (!in_array($tindakan, $values)) {
                            RtmTindakLanjut::where('id', $id)->delete();
                        }
                    }

                    foreach ($values as $index => $item) {
                        $tindakan = RtmTindakLanjut::updateOrCreate(
                            [
                                'rtm_rtl_id' => $rtmRtl,
                                'form_id' => $formId,
                                'tindakan' => $item['tindakan'],
                            ],
                            [
                                'auditee_id' => $auditee,
                                'kriteria_id' => $kriteria->id,  // Menggunakan kriteria_id yang telah diambil
                                'pic' => $item['pic'],
                                'waktu' => $item['waktu'],
                            ]
                        );
                    }
                }
            }
            session()->forget($sessionKey);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Data berhasil disimpan.'], 200);
    }

    public function save_form_per_nomor(Request $request, string $rtmRtl, string $auditee) {}

    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee): RedirectResponse
    {
        // dd(session()->all());
        // dd($request->all());
        // Validasi data
        $rules = [];
        $messages = [];

        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'tindakan_') === 0) {
                $rules[$key] = 'required|array';
                $messages[$key . '.required'] = 'Data tindakan tidak boleh kosong.';
                $messages[$key . '.array'] = 'Format data tindakan tidak valid.';

                $picKey = 'pic_' . substr($key, 9);
                $waktuKey = 'waktu_' . substr($key, 9);

                foreach ($value as $index => $tindakanData) {
                    $rules[$key . '.' . $index . '.tindakan'] = 'required';
                    $rules[$picKey . '.' . $index . '.pic'] = 'required';
                    $rules[$waktuKey . '.' . $index . '.waktu'] = 'required';

                    $messages[$key . '.' . $index . '.tindakan.required'] = 'Tindakan tidak boleh kosong.';
                    $messages[$picKey . '.' . $index . '.pic.required'] = 'PIC tidak boleh kosong.';
                    $messages[$waktuKey . '.' . $index . '.waktu.required'] = 'Waktu tidak boleh kosong.';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        // dd($validator->errors());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_message', 'Harap periksa kembali data yang diinput.');
        }

        // Simpan data dari request
        $formIds = $request->input('formIds');
        foreach ($formIds as $formId) {
            $tindakan = $request->input('tindakan_' . $formId);
            $pic = $request->input('pic_' . $formId);
            $waktu = $request->input('waktu_' . $formId);


            // Ambil kriteria_id berdasarkan form_id
            $kriteria = Kriteria::whereHas('jawaban_auditor', function ($query) use ($formId) {
                $query->where('form_id', $formId);
            })->first();

            $kriteriaId = $kriteria ? $kriteria->id : null;

            if (is_array($tindakan) && is_array($pic) && is_array($waktu)) {
                foreach ($tindakan as $index => $tindakanItem) {
                    RtmTindakLanjut::updateOrCreate(
                        [
                            'rtm_rtl_id' => $rtmRtl->id,
                            'form_id' => $formId,
                            'tindakan' => $tindakanItem,
                        ],
                        [
                            'kriteria_id' => $kriteriaId,
                            'auditee_id' => $auditee,
                            'pic' => $pic[$index]['pic'],
                            'waktu' => $waktu[$index]['waktu'],
                        ]
                    );
                }
            }
        }

        // Simpan data dari session
        // $totalPages = $request->input('totalPage');
        // for ($i = 1; $i <= $totalPages; $i++) {
        //     $sessionKey = 'form_rtm_rtl-page_' . $i . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee;
        //     $sessionData = session($sessionKey);

        //     if (!empty($sessionData)) {
        //         foreach ($sessionData['tindakan'] as $tindakan) {

        //             RtmTindakLanjut::updateOrCreate(
        //                 [
        //                     'rtm_rtl_id' => $rtmRtl->id,
        //                     'form_id' => $formId,
        //                     'tindakan' => $tindakan['tindakan'],
        //                 ],
        //                 [
        //                     'kriteria_id' => $kriteriaId,
        //                     'auditee_id' => $auditee,
        //                     'pic' => $tindakan['pic'],
        //                     'waktu' => $tindakan['waktu'],
        //                 ]
        //             );
        //         }
        //     }
        // }

        $totalPages = $request->input('totalPage');

        // Hapus session setelah data disimpan ke database
        for ($i = 1; $i <= $totalPages; $i++) {
            // dd(session($sessionKey));
            $sessionKey = 'form_rtm_rtl-page_' . $i . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee;
            session()->forget($sessionKey);
        }

        return redirect()->route('dekan.jadwal-rtm.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
