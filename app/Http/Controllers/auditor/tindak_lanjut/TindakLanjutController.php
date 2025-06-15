<?php

namespace App\Http\Controllers\Auditor\tindak_lanjut;

use App\Http\Controllers\Controller;
use App\Models\AuditeeAuditor;
use App\Models\JadwalAudit;
use App\Models\Kriteria;
use App\Models\Fakultas;
use App\Models\Unit;
use App\Models\Auditor;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index(): View
    {
        $jadwal = Auditor::with('jadwal_audit')->where('user_id', $this->user->id)->orderBy('created_at', 'DESC')->get();
        $data = [
            'title' => 'Monitoring Tindak Lanjut PTK',
            'jadwal' => $jadwal,
        ];

        return view('auditor.tindak_lanjut.index', $data);
    }

    public function show(JadwalAudit $jadwalAudit): View
    {   
        //Alert
        $title = 'Hapus!';
        $text = "Apakah Anda yakin ingin menghapus?";
        confirmDelete($title, $text);

        $auditor = Auditor::where(['user_id' => $this->user->id, 'jadwal_audit_id' => $jadwalAudit->id])->first();

        $fakultas = Fakultas::whereHas('auditee.auditorPtk', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor_ptk.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'monitoring' => function ($query) use ($jadwalAudit){
                $query->where('jadwal_audit_id', $jadwalAudit->id)
                ->with(['status_monitoring.kriteria']);
            },
            'monitoring.auditor' => function ($query) use ($auditor){
                $query->where('auditor_id', $auditor->id);
            },
            'rtl.status_rtl',
        ])->get();

        $unit = Unit::whereHas('auditee.auditorPtk', function ($query) use ($jadwalAudit, $auditor) {
            $query->where('auditor.id', $auditor->id)
                ->where('auditee_auditor_ptk.jadwal_audit_id', $jadwalAudit->id);
        })->with([
            'user',
            'monitoring' => function ($query) use ($jadwalAudit){
                $query->where('jadwal_audit_id', $jadwalAudit->id)
                ->with(['status_monitoring.kriteria']);
            },
            'monitoring.auditor' => function ($query) use ($auditor){
                $query->where('auditor_id', $auditor->id);
            },
            'rtl.status_rtl',
            'monitoring.status_monitoring'
        ])->get();


        $auditee = ($fakultas)->merge($unit);
        $allKriteria = Kriteria::all();

        $data = [
            'title' => 'Monitoring Tindak Lanjut',
            'auditee' => $auditee,
            'jadwal' => $jadwalAudit,
            'auditor' => $auditor,
            'allKriteria' => $allKriteria,
        ];

        return view('auditor.tindak_lanjut.show', $data);
    }

    public function auditeeAuditorAima(Request $request)
    {
        $itemId = $request->input('item_id');
        $type = $request->input('type');
        $jadwalId = $request->input('jadwal_id');

        $query = AuditeeAuditor::with([
            'auditee.user',
            'auditor.user',
            'fakultas',
            'prodi',
            'unit'
        ])
        ->where('jadwal_audit_id', $jadwalId);

        if ($type === 'fakultas') {
            $query->where(function($q) use ($itemId) {
                $q->where('fakultas_id', $itemId)
                ->orWhereHas('prodi', function($prodiQuery) use ($itemId) {
                    $prodiQuery->where('fakultas_id', $itemId);
                });
            });
        } else {
            $query->where('unit_id', $itemId);
        }

        //Grup Fakultas
        $groupedData = $query->get()->groupBy(function($item) {
            if ($item->prodi_id) {
                return $item->prodi->jenjang-> nama . ' ' .  $item->prodi->nama;
            } elseif ($item->fakultas_id) {
                return 'Fakultas' . ' ' . $item->fakultas->nama;
            } else {
                return $item->unit->nama;
            }
        });

        $result = [];
        foreach ($groupedData as $unit => $items ) {
            $auditees = $items->unique('auditee_id')
                        ->map(function($item) {
                            return $item->auditee->user->name ?? '-';
                        })
                        ->implode('<br>');
            $auditors = $items->unique('auditor_id')
                        ->map(function($item) {
                            return $item->auditor->user->name ?? '-';
                        })
                        ->implode('<br>');
            
            $result[] = [
                'unit' => $unit,
                'auditees' => $auditees,
                'auditors' => $auditors,
            ];
        }

        return response()->json([
        'success' => true,
        'data' => $result
        ]);
    }
}
