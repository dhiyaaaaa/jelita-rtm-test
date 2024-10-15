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
    public function index(): View
    {
        $user = Auth::user();
        $jabatanUser = Auth::user()->jabatan->isNotEmpty() ? Auth::user()->jabatan->first()->id : null;;

        $auditees = Auditee::where('user_id', $user->id)->with(['prodi', 'fakultas', 'unit'])->get();
        $auditeeids = $auditees->pluck('id')->toArray();

        $jadwal = JadwalAudit::whereHas('form.instrumen', function ($query) use ($auditees, $jabatanUser) {
            $query->where(function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $query->orWhereHas('jenjang', function ($query) use ($auditee) {
                        if ($auditee->prodi_id) {
                            $query->where('prodi_id', $auditee->prodi_id)
                                ->orWhere('jenjang_id', $auditee->prodi->jenjang_id);
                        } elseif ($auditee->unit_id) {
                            $query->where('unit_id', $auditee->unit_id);
                        }
                    });
                }
            })->orWhereHas('jabatan', function ($query) use ($jabatanUser) {
                $query->where('jabatan_id', $jabatanUser);
            });
        })->with('auditee', function ($query) use ($auditeeids) {
            $query->whereIn('id', $auditeeids)->with(['auditor.user']);
        })->whereHas('auditee', function ($query) use ($auditeeids) {
            $query->whereIn('id', $auditeeids)->with(['auditor.user']);
        })->with([
            'status_audit_auditee' => function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $unit = get_type_model($auditee);
                    $query->where($unit['kolom'], $unit['value']);
                }
            },
            'berita_acara' => function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $unit = get_type_model($auditee);
                    $query->where($unit['kolom'], $unit['value']);
                }
            },
            'berita_acara.auditee' => function ($query) use ($auditees, $auditeeids) {
                $query->whereIn('auditee_id', $auditeeids);
            },
            'ptk' => function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $unit = get_type_model($auditee);
                    $query->where($unit['kolom'], $unit['value']);
                }
            },
            'ptk.auditee' => function ($query) use ($auditees, $auditeeids) {
                $query->whereIn('auditee_id', $auditeeids);
            },
            'laporan' => function ($query) use ($auditees) {
                foreach ($auditees as $auditee) {
                    $unit = get_type_model($auditee);
                    $query->where($unit['kolom'], $unit['value']);
                }
            },
            'laporan.auditee' => function ($query) use ($auditees, $auditeeids) {
                $query->whereIn('auditee_id', $auditeeids);
            },
        ])->orderBy('created_at', 'DESC')
            ->get();

        $data = [
            'title' => 'Audit Lapangan',
            'jadwal' => $jadwal,

        ];

        return view('auditee.lapangan.index', $data);
    }
}
