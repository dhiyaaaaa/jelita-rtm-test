<?php

namespace App\Http\Controllers\admin\assessment;

use App\Http\Controllers\Controller;
use App\Models\AssessmentForm;
use App\Models\AssessmentJawaban;
use App\Models\AuditeeAuditor;
use App\Models\Auditor;
use App\Models\JadwalAudit;
use App\Models\Prodi;
use App\Models\StatusAssessment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class HasilAssessmentController extends Controller
{
    public function get_auditors(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                if ($request->jadwal !== null) {
                    $auditors = Auditor::where('jadwal_audit_id', $request->jadwal)->with(['user'])->select('id', 'user_id')->get();

                    return DataTables::of($auditors)
                        ->addColumn('nama', function ($row) {
                            return $row->user ? $row->user->name : 'User tidak ditemukan';
                        })
                        ->addColumn('action', function ($row) {
                            $btn = '<a href="' . route('hasil_assessment.show', $row->id) . '" class="btn btn-info">Lihat</a>';

                            return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }
        return response()->json([
            'error' => 'Invalid request.'
        ], 400);
    }

    public function index(): View
    {
        $jadwal = JadwalAudit::all();

        $data = [
            'title' => 'Peer Assessment',
            'jadwal' => $jadwal,
        ];

        return view('admin.assessment.hasil.index', $data);
    }

    public function show(Auditor $auditor): View
    {
        $auditee_auditor = AuditeeAuditor::where('auditor_id', $auditor->id)->get();

        $prodiids = $auditee_auditor->pluck('prodi_id')->unique();
        $fakultasids = $auditee_auditor->pluck('fakultas_id')->unique();
        $unitids = $auditee_auditor->pluck('unit_id')->unique();

        $auditors = AuditeeAuditor::where('jadwal_audit_id', $auditor->jadwal_audit_id)
            ->whereNotIn('auditor_id', [$auditor->id])
            ->where(function ($query) use ($prodiids, $fakultasids, $unitids) {
                $query->whereIn('prodi_id', $prodiids)
                    ->orWhereIn('fakultas_id', $fakultasids)
                    ->orWhereIn('unit_id', $unitids);
            })
            ->with(['auditor.user', 'prodi.jenjang', 'fakultas', 'unit', 'jadwal_audit'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($item) {
                return $item['auditor_id'] . $item['prodi_id'] . $item['fakultas_id'] . $item['unit_id'] . $item['jadwal_audit_id'];
            });

        $data = [
            'title' => 'Hasil Peer Assessment',
            'auditors' => $auditors,
            'auditor' => $auditor,
        ];

        return view('admin.assessment.hasil.show', $data);
    }

    public function assessment(string $auditorDinilai, string $auditorPenilai, string $unit, string $type): View| RedirectResponse
    {
        $auditor_dinilai = Auditor::findOrFail($auditorDinilai);

        $pertanyaan = AssessmentForm::where(['jadwal_audit_id' => $auditor_dinilai->jadwal_audit_id])->get();

        if ($pertanyaan->isEmpty()) {
            return back()->with('error', 'Belum ada pertanyaan.');
        }

        $jawabans = AssessmentJawaban::where(['jadwal_audit_id' => $auditor_dinilai->jadwal_audit_id, get_type($type) => $unit])->where('auditor_dinilai_id', $auditor_dinilai->id)->where('auditor_penilai_id', $auditorPenilai)->get();

        $status = StatusAssessment::where(['jadwal_audit_id' => $auditor_dinilai->jadwal_audit_id, get_type($type) => $unit])->where('auditor_dinilai_id', $auditor_dinilai->id)->where('auditor_penilai_id', $auditorPenilai)->first();

        $data = [
            'title' => 'Peer Assessment',
            'pertanyaan' => $pertanyaan,
            'jawabans' => $jawabans,
            'status' => $status,
            'auditorDinilai' => $auditor_dinilai,
        ];

        return view('admin.assessment.hasil.assessment', $data);
    }
}
