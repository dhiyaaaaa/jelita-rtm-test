<?php

namespace App\Http\Controllers\auditee\lapangan;

use App\Http\Controllers\Controller;
use App\Models\Auditee;
use App\Models\JadwalAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LapanganController extends Controller
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
        $prodi = $this->user->prodi->first();
        $fakultas = $this->user->fakultas->first();
        $unit = $this->user->unit->first();

        if (!$prodi && !$fakultas && !$unit) {
            abort(403);
        }

        $auditees = Auditee::where('user_id', $this->user->id)->with(['prodi', 'fakultas', 'unit'])->get();
        $auditeeids = $auditees->pluck('id')->toArray();

        $jadwal = JadwalAudit::whereHas('form.instrumen', function ($query) use ($prodi, $unit) {
            $query->where(function ($query) use ($prodi, $unit) {
                $query->orWhereHas('jenjang', function ($query) use ($prodi, $unit) {
                    if ($prodi) {
                        $query->where('prodi_id', $prodi->id)
                            ->orWhere('jenjang_id', $prodi->jenjang->id);
                    } elseif ($unit) {
                        $query->where('unit_id', $unit->id);
                    }
                });
            })->orWhereHas('jabatan', function ($query) {
                $query->where('jabatan_id', $this->jabatanUser);
            });
        })->with([
            'berita_acara' => function ($query) use ($prodi, $fakultas, $unit) {
                if ($prodi) {
                    $query->where('prodi_id', $prodi->id);
                } elseif ($fakultas) {
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
            'ptk' => function ($query) use ($prodi, $fakultas, $unit) {
                if ($prodi) {
                    $query->where('prodi_id', $prodi->id);
                } elseif ($fakultas) {
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
            'laporan' => function ($query) use ($prodi, $fakultas, $unit) {
                if ($prodi) {
                    $query->where('prodi_id', $prodi->id);
                } elseif ($fakultas) {
                    $query->where('fakultas_id', $fakultas->id);
                } elseif ($unit) {
                    $query->where('unit_id', $unit->id);
                }
            },
            'status_audit_auditee' => function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $unit = get_type_model($auditee);
                    $query->where($unit['kolom'], $unit['value']);
                }
            },
            // 'berita_acara.auditee' => function ($query) use ($auditees, $auditeeids) {
            //     $query->whereIn('auditee_id', $auditeeids);
            // },
            // 'ptk.auditee' => function ($query) use ($auditees, $auditeeids) {
            //     $query->whereIn('auditee_id', $auditeeids);
            // },
            // 'laporan.auditee' => function ($query) use ($auditees, $auditeeids) {
            //     $query->whereIn('auditee_id', $auditeeids);
            // },
        ])->orderBy('created_at', 'DESC')
            ->get();

        $data = [
            'title' => 'Audit Lapangan',
            'jadwal' => $jadwal,

        ];

        return view('auditee.lapangan.index', $data);
    }
}
