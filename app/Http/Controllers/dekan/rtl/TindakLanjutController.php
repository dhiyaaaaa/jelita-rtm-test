<?php

namespace App\Http\Controllers\dekan\rtl;

use App\Http\Controllers\Controller;
use App\Models\Auditee;
use App\Models\JadwalAudit;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class TindakLanjutController extends Controller
{
    protected $user;
    protected $jabatanUser;

    public function __construct()
    {
        $this->user = Auth::user();
        $this->jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;
    }

    public function index(): View
    {
        $title = 'Hapus!';
        $text = "Apakah Anda yakin ingin menghapus?";
        confirmDelete($title, $text);
        
        $user = Auth::user();
    

        $prodi = $user->prodi->first();
        $fakultas = $user->fakultas->first();
        $unit = $user->unit->first(); 

        if (!$prodi && !$fakultas && !$unit) {
            abort(403);
        }


        $auditee = Auditee::where('user_id', $user->id)->with(['prodi', 'fakultas', 'unit'])->first();
    
        if (!$auditee) {
            abort(403, 'Data auditee tidak ditemukan.');
        }

        $jadwal = JadwalAudit::whereHas('ptk', function ($query) use ($prodi, $fakultas, $unit) {
            if ($prodi) {
                $query->where('prodi_id', $prodi->id);
            }
            if ($fakultas) {
                $query->where('fakultas_id', $fakultas->id);
            }
            if ($unit) {
                $query->where('unit_id', $unit->id);
            }
        })->with([
            'rtl',
            'rtl.auditee',
            'rtl.status_rtl_auditee',
            'monitoring',
            'monitoring.auditee',
        ])->orderBy('created_at', 'DESC')->get();
    
        $data = [
            'title' => 'Tindakan Koreksi',
            'jadwal' => $jadwal,
            'auditee' => $auditee,
        ];

        return view('auditee.tindak_lanjut.rtl.index', $data);
    }

}
