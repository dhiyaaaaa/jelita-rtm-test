<?php

namespace App\Http\Controllers\dekan;

use App\Http\Controllers\Controller;
use App\Models\RtmCatatan;
use App\Models\RtmJadwal;
use App\Models\StatusRtmCatatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RtmCatatanController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function isi_rtm_catatan(RtmJadwal $rtmJadwal){

        StatusRtmCatatan::updateOrCreate(
            [
                'rtm_jadwal_id' => $rtmJadwal->id,
            ],
            ['status' => 'in_progress']
        );

        return redirect()->route('dekan.rtm-catatan.form', [
            'rtmJadwal' => $rtmJadwal, 
        ]);
    }

    public function form(RtmJadwal $rtmJadwal)
    {
        $status = StatusRtmCatatan::where('rtm_jadwal_id', $rtmJadwal->id)->first();
        $rtmRtl = $rtmJadwal->rtm_rtl()->latest()->first();

        $dbCatatan = RtmCatatan::where('rtm_jadwal_id', $rtmJadwal->id)
                    ->get();
        $sessionData = session('rtm_catatan_data');
        
        $rtmCatatan = $sessionData && isset($sessionData['catatan']) 
            ? collect($sessionData['catatan'])
            : $dbCatatan;

        $data = [
            'title' => 'Catatan Laporan RTM',
            'rtmCatatan' => $rtmCatatan,
            'rtmJadwal' => $rtmJadwal,
            'rtmRtl' => $rtmRtl,
            'user' => $this->user,
            'status' => $status
        ];

        return view('dekan.rtm.catatan.form', $data);
    }

    public function store(Request $request, RtmJadwal $rtmJadwal)
    {
        // Validasi
        $request->validate([
            'catatan' => 'required|array',
            'catatan.*.judul' => 'required|string',
            'catatan.*.catatan' => 'required|string',
        ]);

        // Simpan ke DB
        foreach ($request->catatan as $catatanData) {
            RtmCatatan::updateOrCreate(
                [
                    'id' => $catatanData['id'] ?? null,
                    'rtm_jadwal_id' => $rtmJadwal->id,
                    'user_id' => $this->user->id,
                ],
                [
                    'judul' => $catatanData['judul'],
                    'catatan' => $catatanData['catatan']
                ]
            );
        }

        // Update status
        StatusRtmCatatan::updateOrCreate(
            ['rtm_jadwal_id' => $rtmJadwal->id],
            ['status' => 'completed']
        );

        $rtmRtl = $rtmJadwal->rtm_rtl()->latest()->first();

        return redirect()->route('dekan.rtm-rtl.show', ['rtmRtl' => $rtmRtl->id])->with('success', 'Data berhasil disimpan.');
    }
}
