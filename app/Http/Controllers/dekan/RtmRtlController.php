<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RtmRtl;
use App\Models\RtmTindakLanjut;
use App\Models\StatusRtmRtl;
use App\Models\Kriteria;
use App\Models\JadwalAudit;
use App\Models\Fakultas;
use App\Models\JawabanAuditor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

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
            'fakultas_id' => 'required|exists:fakultas,id',
            'jadwal_audit_id' => 'required|exists:jadwal_audit,id',
        ]);

        RtmRtl::create([
            'rtm_jadwal_id' => $request->jadwal_id,
            'fakultas_id' => $request->fakultas_id,
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
        $kriteriaList = Kriteria::whereIn('slug', ['belum-memenuhi', 'memenuhi', 'melampaui'])->pluck('id')->toArray();

        $jadwal = JadwalAudit::findOrFail($rtmRtl->jadwal_audit_id);
        if (!$rtmRtl->jadwal_audit_id) {
            return redirect()->back()->with('error', 'Jadwal audit tidak ditemukan.');
        }
        
        $fakultas = $rtmRtl->fakultas;
        
        // Temuan Fakultas
        $temuanFakultas = JawabanAuditor::select('form_id', 'kriteria_id', 'catatan')
        ->where('jadwal_audit_id', $rtmRtl->jadwal_audit_id)
        ->where('fakultas_id', $fakultas->id)
        ->whereIn('kriteria_id', $kriteriaList)
        ->with(['form:id,instrumen_id', 'form.instrumen:id,kode,pernyataan', 'kriteria:id,slug'])
        ->get()
        ->groupBy(fn($temuan) =>  optional($temuan->kriteria)->slug ?? 'tanpa-kriteria');

        $temuanArray = $temuanFakultas->toArray();

        // Ambil halaman saat ini dari request (default: 1)
        $page = request()->input('page', 1);
        $perPage = 1; 
        $offset = ($page - 1) * $perPage;
        
        $items = array_slice($temuanArray, $offset, $perPage, true);
     
        $paginatedTemuan = new LengthAwarePaginator(
            $items,
            count($temuanArray),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        if (empty($temuanArray)) {
            $paginatedTemuanFakultas = new LengthAwarePaginator([], 0, $perPage, $page, [
                'path' => request()->url(), 'query' => request()->query()
            ]);
        } else {
            $items = array_slice($temuanArray, $offset, $perPage, true);
            $paginatedTemuanFakultas = new LengthAwarePaginator($items, count($temuanArray), $perPage, $page, [
                'path' => request()->url(), 'query' => request()->query()
            ]);
        }        
        
        
        if ($request->wantsJson()) {
            return response()->json($paginatedTemuan);
        }

        $jawabanTindakLanjut = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->get();
        $sessionFormData = session()->get("form_rtm_rtl-page_{$page}-rtmRtlId_{$rtmRtl->id}-fakultasId_{$fakultas->id}", []);
        $status = StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->first();

        $data = [
            'title' => 'Tindak Lanjut Hasil Audit Fakultas',
            'rtmRtl' => $rtmRtl,
            'paginatedTemuanFakultas' => $paginatedTemuanFakultas,
            'jawabanTindakLanjut' => $jawabanTindakLanjut,
            'sessionFormData' => $sessionFormData,
            'status' => $status,
            'fakultas' => $fakultas,
            'temuanFakultas' => $temuanFakultas,
            'page' => $page,
        ];

        return view('dekan.rtm.rtm_rtl.form', $data);
    }
    
    public function save_form(Request $request, RtmRtl $rtmRtl, Fakultas $fakultas)
    {
        if ($request->ajax()) {
            try {
                $tindakanArray = $request->input('tindakan');

                if (!is_array($tindakanArray)) {
                    return response()->json(['message' => 'Format tindakan tidak valid.'], 400);
                }

                foreach ($tindakanArray as $formId => $tindakan) {
                    $data = [
                        'rtm_rtl_id' => $rtmRtl->id,
                        'form_id' => $formId,
                        'fakultas_id' => $fakultas->id,
                        'kriteria_id' => $request->input("kriteria.$formId", null),
                        'tindakan' => $tindakan,
                        'catatan' => $request->input("catatan.$formId", null),
                    ];

                    RtmTindakLanjut::updateOrCreate(
                        ['rtm_rtl_id' => $rtmRtl->id, 'form_id' => $formId],
                        $data
                    );
                }

                return response()->json(['message' => 'Jawaban berhasil disimpan.']);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat menyimpan jawaban.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        return response()->json(['error' => 'Invalid Request.'], 400);
    }


    public function store_form(Request $request, RtmRtl $rtmRtl, string $fakultas): RedirectResponse
    {
        $totalPages = $request->input('totalPage', 1);
        $sessionFormData = [];

        for ($i = 1; $i <= $totalPages; $i++) {
            $sessionKey = "form_rtm_rtl-page_{$i}-rtmRtlId_{$rtmRtl->id}-fakultasId_{$fakultas}";
            $sessionFormData[$i] = session()->get($sessionKey, []);
        }

        $rules = [];
        $messages = [];
        foreach ($sessionFormData as $page => $data) {
            foreach ($data as $key => $value) {
                if (strpos($key, 'tindakan_') === 0) {
                    $kriteria = $data['kriteria_' . substr($key, 9)] ?? null;
                    if (in_array($kriteria, ['belum-memenuhi', 'melampaui'])) {
                        $rules[$key] = 'required|string';
                        $messages[$key . '.required'] = 'Tindakan wajib diisi untuk kriteria ' . ucfirst(str_replace('-', ' ', $kriteria)) . '.';
                    }
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id])
                ->withErrors($validator)
                ->withInput()
                ->with('error_message', $validator->errors()->first());
        }

        // Simpan Data ke Database
        $existingForms = RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)->pluck('form_id')->toArray();
        $newFormIds = [];

        foreach ($sessionFormData as $pageData) {
            foreach ($pageData as $key => $value) {
                if (strpos($key, 'tindakan_') === 0) {
                    $id = substr($key, 9);
                    $newFormIds[] = $id;

                    $data = [
                        'rtm_rtl_id' => $rtmRtl->id,
                        'form_id' => $id,
                        'fakultas_id' => $fakultas,
                        'kriteria_id' => $pageData['kriteria_' . $id] ?? null,
                        'tindakan' => $value,
                        'catatan' => $pageData['catatan_' . $id] ?? null,
                    ];

                    RtmTindakLanjut::updateOrCreate(
                        ['rtm_rtl_id' => $rtmRtl->id, 'form_id' => $id],
                        $data
                    );
                }
            }
        }

        // Hapus Data Lama yang Tidak Ada dalam Input Baru
        $formsToDelete = array_diff($existingForms, $newFormIds);
        if (!empty($formsToDelete)) {
            RtmTindakLanjut::where('rtm_rtl_id', $rtmRtl->id)
                ->whereIn('form_id', $formsToDelete)
                ->delete();
        }

        // Finalisasi Status
        if ($request->has('final') && $request->input('final') === 'final') {
            StatusRtmRtl::where('rtm_rtl_id', $rtmRtl->id)->update(['status' => 'completed']);
        }

        return redirect()->route('dekan.rtm-rtl.form', ['rtmRtl' => $rtmRtl->id])
            ->with('success', 'Data berhasil disimpan.');
    }

}