<?php

namespace App\Http\Controllers\auditor;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentForm;
use App\Models\AssessmentJawaban;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\JadwalAudit;
use App\Models\StatusAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PeerAssessmentController extends Controller
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
        $auditor = Auditor::where('user_id', $this->user->id)->pluck('id');

        $auditee_auditor = AuditeeAuditor::whereIn('auditor_id', $auditor);

        $prodiids = $auditee_auditor->pluck('prodi_id')->unique();
        $fakultasids = $auditee_auditor->pluck('fakultas_id')->unique();
        $unitids = $auditee_auditor->pluck('unit_id')->unique();

        $auditors = AuditeeAuditor::whereNotIn('auditor_id', $auditor)
            ->where(function ($query) use ($prodiids, $fakultasids, $unitids) {
                $query->whereIn('prodi_id', $prodiids)
                    ->orWhereIn('fakultas_id', $fakultasids)
                    ->orWhereIn('unit_id', $unitids);
            })
            ->with(['auditor.user', 'prodi.jenjang', 'fakultas', 'unit', 'jadwal_audit'])
            ->orderBy('jadwal_audit_id', 'desc')
            ->get()
            ->unique(function ($item) {
                return $item['auditor_id'] . $item['prodi_id'] . $item['fakultas_id'] . $item['unit_id'] . $item['jadwal_audit_id'];
            });

        $jadwal = JadwalAudit::whereIn('id', $auditee_auditor->pluck('jadwal_audit_id')->unique()->toArray())
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'title' => 'Peer Assessment',
            'auditors' => $auditors,
            'jadwal' => $jadwal,
        ];

        return view('auditor.assessment.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $auditor, string $jadwalAudit, string $unit, string $type): View|RedirectResponse
    {
        $user = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit])
            ->first();

        $pertanyaan = AssessmentForm::where(['jadwal_audit_id' => $jadwalAudit])->get();

        if ($pertanyaan->isEmpty()) {
            return back()->with('error', 'Belum ada pertanyaan.');
        }

        $sessionFormData = session()->get('peer_assessment-auditor_' .  $auditor . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit, []);

        $jawabans = AssessmentJawaban::where(['jadwal_audit_id' => $jadwalAudit, get_type($type) => $unit])->where('auditor_dinilai_id', $auditor)->where('auditor_penilai_id', $user->id)->get();

        $status = StatusAssessment::where(['jadwal_audit_id' => $jadwalAudit, get_type($type) => $unit])->where('auditor_dinilai_id', $auditor)->where('auditor_penilai_id', $user->id)->first();

        $data = [
            'title' => 'Peer Assessment',
            'pertanyaan' => $pertanyaan,
            'auditorId' => $auditor,
            'jadwalId' => $jadwalAudit,
            'unitId' => $unit,
            'type' => $type,
            'sessionFormData' => $sessionFormData,
            'status' => $status,
            'jawabans' => $jawabans,
        ];

        return view('auditor.assessment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $auditor, string $jadwalAudit, string $unit, string $type): RedirectResponse
    {
        $user = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit])
            ->first();

        $sessionFormData = session()->get('peer_assessment-auditor_' .  $auditor . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit, []);

        $ids = AssessmentForm::where('jadwal_audit_id', $jadwalAudit)->pluck('id');
        $rules = [];
        $messages = [];

        foreach ($sessionFormData as $key => $value) {
            // if (strpos($key, 'jawaban_') === 0) {
            foreach ($ids as $id) {
                $rules['jawaban_' . $id] = 'required';
                $messages['jawaban_' . $id . '.required'] = 'Jawaban harus diisi.';
            }
            // }
        }

        $validator = Validator::make($sessionFormData, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        foreach ($sessionFormData as $key => $value) {
            if (strpos($key, 'jawaban_') === 0) {
                $pertanyaanid = str_replace('jawaban_', '', $key);
                AssessmentJawaban::updateOrCreate([
                    'jadwal_audit_id' => $jadwalAudit,
                    get_type($type) => $unit,
                    'assessment_form_id' => $pertanyaanid,
                    'auditor_penilai_id' => $user->id,
                    'auditor_dinilai_id' => $auditor,
                    'jawaban' => $value,
                ]);
            }
        }

        if ($request->status === 'selesai') {
            StatusAssessment::updateOrCreate([
                'jadwal_audit_id' => $jadwalAudit,
                get_type($type) => $unit,
                'auditor_penilai_id' => $user->id,
                'auditor_dinilai_id' => $auditor,
                'status' => 'selesai',
            ]);

            return redirect()->route('auditor.peer-assessment')->with('success', 'Jawaban berhasil disimpan!');
        } else {
            return back()->with('error', 'Terjadi kesalahan!');
        }
    }
}
