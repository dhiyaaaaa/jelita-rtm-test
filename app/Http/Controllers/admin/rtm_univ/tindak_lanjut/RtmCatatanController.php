<?php

namespace App\Http\Controllers\admin\rtm_univ\tindak_lanjut;

use App\Http\Controllers\Controller;
use App\Models\RtmCatatan;
use App\Models\RtmJadwal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RtmCatatanController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function form(RtmJadwal $rtmJadwal)
    {
        // Sweet Alert (pastikan fungsi confirmDelete() tersedia secara global atau diimport)
        $title = 'Hapus Narasi RTM!';
        $text = "Apakah Anda yakin ingin menghapus narasi ini?";
        confirmDelete($title, $text);

        $rtmCatatan = RtmCatatan::where('rtm_jadwal_id', $rtmJadwal->id)->get();
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
        ];

        return view('admin.rtm_univ.rtm_catatan.form', $data);
    }

    public function store(Request $request, RtmJadwal $rtmJadwal)
    {
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
        session()->forget($sessionKey); // Hapus sesi setelah data berhasil disimpan

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function edit(RtmCatatan $rtmCatatan)
    {
        $sessionKey = 'session_catatan_' . $rtmCatatan->rtm_jadwal_id;

        session([$sessionKey => [
            'id' => $rtmCatatan->id,
            'judul' => $rtmCatatan->judul,
            'isi' => $rtmCatatan->isi
        ]]);

        return redirect()->back();
    }

    public function update(Request $request, RtmCatatan $rtmCatatan)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
        ]);

        $rtmCatatan->update($validated);

        $sessionKey = 'session_catatan_' . $rtmCatatan->rtm_jadwal_id;
        session()->forget($sessionKey); // Hapus sesi setelah data berhasil diperbarui

        return redirect()->route('admin.rtm-catatan.form', ['rtmJadwal' => $rtmCatatan->rtm_jadwal_id])
           ->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(RtmCatatan $rtmCatatan)
    {
       $rtmCatatan->delete();

        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }

    
    public function cancelEdit(RtmJadwal $rtmJadwal)
    {
        $sessionKey = 'session_catatan_' . $rtmJadwal->id;
        Session::forget($sessionKey); // Hapus sesi
        return redirect()->route('admin.rtm-catatan.form', ['rtmJadwal' => $rtmJadwal]);
    }
}