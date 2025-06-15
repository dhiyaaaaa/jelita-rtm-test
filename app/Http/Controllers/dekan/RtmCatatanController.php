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

    // public function isi_rtm_catatan(RtmJadwal $rtmJadwal){

    //     StatusRtmCatatan::updateOrCreate(
    //         [
    //             'rtm_jadwal_id' => $rtmJadwal->id,
    //         ],
    //         ['status' => 'in_progress']
    //     );

    //     return redirect()->route('dekan.rtm-catatan.form', [
    //         'rtmJadwal' => $rtmJadwal, 
    //     ]);
    // }

    public function form(RtmJadwal $rtmJadwal)
    {
        // Sweet Alert
        $title = 'Hapus Narasi RTM!';
        $text = "Apakah Anda yakin ingin menghapus narasi ini?";
        confirmDelete($title, $text);

        $rtmCatatan = RtmCatatan::where('rtm_jadwal_id', $rtmJadwal->id)
                    ->get();
        $rtmRtl = $rtmJadwal->rtm_rtl()->latest()->first();

        $sessionKey = 'session_catatan_' . $rtmJadwal->id;
        $sessionCatatan = session()->get($sessionKey, []);

        $data = [
            'title' => 'Narasi Laporan RTM',
            'rtmCatatan' => $rtmCatatan,
            'rtmJadwal' => $rtmJadwal,
            'sessionCatatan' => $sessionCatatan,
            'rtmRtl' => $rtmRtl,
            'user' => $this->user,
            //'status' => $status
        ];

        return view('dekan.rtm.catatan.form', $data);
    }

    public function store(Request $request, RtmJadwal $rtmJadwal)
    {
        // Validasi
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
        ], [
            'judul.required' => 'Judul isian wajib diisi',
            'isi.required' => 'Isian narasi wajib diisi!',
        ]);

        RtmCatatan::create([
            'rtm_jadwal_id' => $rtmJadwal->id,
            'user_id' => $this->user->id,
            'judul' => $request->judul,
            'isi' => $request->isi,
        ]);

        $sessionKey = 'session_catatan_' . $rtmJadwal->id;
        session()->forget($sessionKey);
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function update(Request $request, RtmCatatan $rtmCatatan)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
        ]);

        $rtmCatatan->update($validated);

        return redirect()->back()->with('success', 'Catatan berhasil diperbarui.');
    }


    public function destroy(RtmCatatan $rtmCatatan)
    {
        $rtmJadwalId = $rtmCatatan->rtm_jadwal_id;
        $rtmCatatan->delete();

        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }
}
