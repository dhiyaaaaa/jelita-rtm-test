<?php

namespace App\Http\Controllers\auditeePtk\rtl;

use App\Http\Controllers\Controller;
use App\Models\JadwalAudit;
use App\Models\Auditee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TindakLanjutController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = $this->user->jabatan->isNotEmpty() ? $this->user->jabatan->first()->id : null;
    }

    public function index()
    {
        $fakultas = $this->user->fakultas->first();
        $unit = $this->user->unit->first();

        if (!$unit && !$fakultas){
            abort(403);
        }

        $auditees = Auditee::where('user_id', $this->user->id)->with(['fakultas', 'unit'])->get();
        $auditeeids = $auditees->pluck('id')->toArray();

        $jadwal = JadwalAudit::whereHas('form.jawaban_auditor', function ($query) use ($fakultas, $unit){
            $query->where(function($query) use ($fakultas, $unit){
                if ($fakultas) {
                    $query->where('fakultas_id', $fakultas->id);
                }elseif ($unit){
                    $query->where('unit_id', $unit->id);
                }
            });
        })->with([
            'rtl' =>function ($query) use ($fakultas, $unit){
                if ($fakultas){
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
            
            'rtl.auditee' => function ($query) use ($auditees, $auditeeids){
                $query->whereIn('auditee_id', $auditeeids);
            },
            'monitoring' =>function ($query) use ($fakultas, $unit){
                if ($fakultas){
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
            
            'monitoring.auditee' => function ($query) use ($auditees, $auditeeids){
                $query->whereIn('auditee_id', $auditeeids);
            },
            
        ])->orderBy('created_at', 'DESC')
            ->get();
        
        $data = [
            'title' => 'Tindak Lanjut Permintaan Tindakan Koreksi',
            'jadwal' => $jadwal,
            'unit' => $fakultas ?? $unit,
            'type' => ($fakultas ? 'fakultas' : ($unit ? 'unit' : null)),
        ];

        return view('dekan.rtl.index', $data);
    }
}
