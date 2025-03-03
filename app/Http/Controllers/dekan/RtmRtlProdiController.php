<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtl;
use App\Models\Auditee;
use App\Models\Kriteria;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\JawabanAuditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

class RtmRtlProdiController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
    }

    public function form(Request $request, RtmRtl $rtmRtl): View|RedirectResponse
    {
        $jadwal = JadwalAudit::findOrfail($rtmRtl->jadwal_audit_id);
        $fakultas = $rtmRtl->fakultas;

        $jawaban_auditor = JawabanAuditor::where(['jadwal_audit_id' => $rtmRtl->jadwal_audit_id])->first();

        $auditee = Auditee::where(['user_id' => $this->user->id])->first();

        $prodiList = Prodi::where('fakultas_id', $fakultas->id)->pluck('id')->toArray();

        $temuanProdi = JawabanAuditor::select('jawaban_auditor.*', 'jawaban_auditor.catatan')
            ->join('form', 'jawaban_auditor.form_id', '=', 'form.id')
            ->join('instrumen', 'form.instrumen_id', '=', 'instrumen.id')
            ->where('jawaban_auditor.jadwal_audit_id', $rtmRtl->jadwal_audit_id)
            ->whereIn('jawaban_auditor.prodi_id', $prodiList)
            ->orderByRaw("REGEXP_REPLACE(instrumen.kode, '[^0-9]', '', 'g')::int NULLS FIRST, REGEXP_REPLACE(instrumen.kode, '[0-9]', '', 'g') ASC")
            ->orderBy('jawaban_auditor.kriteria_id', 'asc')
            ->with(['prodi', 'form.jawaban_auditor', 'form.instrumen', 'kriteria'])
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

        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
            ->with(['kriteria'])
            ->get()
            ->groupBy('form_id');

        $sessionFormData = session()->get('form_rtm_rtl_prodi-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee->id, []);

        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first();


        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Prodi',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanProdi' => $paginatedTemuanProdi,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $sessionFormData,
            'groupedTemuanProdi' => $groupedTemuanProdi,
            'temuanProdi' => $temuanProdi,
            'jawabanAuditor' => $jawaban_auditor,
            'status' => $status,
            'auditee' => $auditee,
        ];

        return view('dekan.rtm.rtm_rtl.form_prodi', $data);
    }


    public function save_form(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        Log::info('Data received:', $request->all());

        if (empty($request->all())) {
            Log::error('Data tidak boleh kosong');
            return response()->json(['message' => 'Data tidak boleh kosong.'], 422);
        }

        try {
            $formId = $request->input('formId');
            $kriteriaList = $request->input('kriteria');

            if (empty($formId) || empty($kriteriaList)) {
                Log::error('Form ID atau kriteria tidak boleh kosong');
                return response()->json(['message' => 'Form ID atau kriteria tidak boleh kosong.'], 422);
            }

            // Hapus data lama yang tidak ada dalam request
            RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl)
                ->where('form_id', $formId)
                ->whereNotIn('id', collect($kriteriaList)->pluck('id')->filter())
                ->delete();

            foreach ($kriteriaList as $kriteria) {
                $kriteriaId = $kriteria['kriteriaId'];
                $tindakanList = $kriteria['tindakan'];

                if (empty($kriteriaId) || empty($tindakanList)) {
                    Log::error('Kriteria ID atau tindakan tidak boleh kosong');
                    continue;
                }

                $kriteria = Kriteria::where('id', $kriteriaId)->first();

                if (!$kriteria) {
                    Log::error('Kriteria tidak ditemukan:', ['kriteriaId' => $kriteriaId]);
                    continue;
                }

                foreach ($tindakanList as $tindakan) {
                    RtmTindakLanjut::updateOrCreate(
                        [
                            'rtm_rtl_id' => $rtmRtl,
                            'form_id' => $formId,
                            'kriteria_id' => $kriteria->id,
                            'tindakan' => $tindakan['tindakan'],
                        ],
                        [
                            'auditee_id' => $auditee,
                            'pic' => $tindakan['pic'],
                            'waktu' => $tindakan['waktu'],
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving data:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }

        Log::info('Data saved successfully');
        return response()->json(['message' => 'Data berhasil disimpan.'], 200);
    }

    
    public function store_form(Request $request, RtmRtl $rtmRtl, string $auditee): RedirectResponse
    {
        $totalPages = $request->input('totalPage');
        $sessionFormData = [];

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = 'form_rtm_rtl_prodi-page_' . $i . '-rtmRtlId_' . $rtmRtl->id . '-auditeeId_' . $auditee;
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

        if (empty($sessionFormData)) {
            return redirect()->back()->with('error', 'Tidak ada data yang ditemukan.');
        }

        $rules = [];
        $messages = [];
        $requestData = [];

        foreach ($sessionFormData as $page => $data) {
            if (!is_array($data)) {
                continue;
            }

            foreach ($data as $key => $value) {
                $requestData[$key] = $value;

                if (preg_match('/^(tindakan_|pic_|waktu_)(\d+)$/', $key, $matches)) {
                    $id = $matches[2];

                    if (empty($value) || (is_array($value) && $value[0] === null)) {
                        $rules[$key] = 'required';
                        $messages[$key . '.required'] = ucfirst($matches[1]) . ' harus diisi.';
                    }
                }
            }
        }

        $validator = Validator::make($requestData, $rules, $messages);
        if ($validator->fails()) {
            Log::error('Validasi gagal:', $validator->errors()->all());
            return redirect()->route('dekan.rtm-rtl.form_prodi', ['rtmRtl' => $rtmRtl->id])
                ->withErrors($validator)
                ->withInput();
        }

        foreach ($requestData as $key => $value) {
            if (preg_match('/^(tindakan_|pic_|waktu_)(\d+)$/', $key, $matches)) {
                $id = $matches[2];
                $tindakan = $value;
                $pic = $requestData['pic_' . $id] ?? null;
                $waktu = $requestData['waktu_' . $id] ?? null;

                $kriteria = Kriteria::find($id);

                if ($kriteria) {
                    Log::info('Menyimpan data:', [
                        'rtm_rtl_id' => $rtmRtl->id,
                        'form_id' => $id,
                        'tindakan' => $tindakan,
                        'auditee_id' => $auditee,
                        'kriteria_id' => $kriteria->id,
                        'pic' => $pic,
                        'waktu' => $waktu,
                    ]);

                    RtmTindakLanjut::updateOrCreate(
                        [
                            'rtm_rtl_id' => $rtmRtl->id,
                            'form_id' => $id,
                            'tindakan' => $tindakan,
                        ],
                        [
                            'auditee_id' => $auditee,
                            'kriteria_id' => $kriteria->id,
                            'pic' => $pic,
                            'waktu' => $waktu,
                        ]
                    );

                    Log::info('Data berhasil disimpan.');
                } else {
                    Log::error('Kriteria tidak ditemukan:', ['kriteriaId' => $id]);
                }
            }
        }

        if (isset($requestData['final']) && $requestData['final'] == 'final') {
            Log::info('Final submission detected.');
            $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first();

            if ($status && $status->status == 'in_progress') {
                $status->status = 'completed';
                $status->save();
                Log::info('Status diperbarui ke completed.');
            }
        }

        return redirect()->route('dekan.jadwal-rtm.index')
            ->with('success', 'Data berhasil disimpan.');
    }
}
