<?php

namespace App\Http\Controllers\admin\rtm_univ\jadwal;

use App\Http\Controllers\Controller;
use App\Models\RtmLampiran;
use Illuminate\Http\Request;

class LampiranRtmController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'rtm_jadwal_id' =>'required|exists:rtm_jadwal,id',
            'undangan' => 'required|file|mimes:pdf|max:2048',
            'presensi' => 'required|file|mimes:pdf|max:2048',
            'dokumentasi' => 'required|file|mimes:pdf|max:5120',
        ]);

        try {
            $rtmJadwalId = $request->rtm_jadwal_id;
            $timestamp = now()->timestamp;

            $undanganPath = $request->file('undangan')->storeAs(
                'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $presensiPath = $request->file('presensi')->storeAs(
                'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $dokumentasiPath = $request->file('dokumentasi')->storeAs(
                'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );


        $lampiran = RtmLampiran::updateOrCreate(
            ['rtm_jadwal_id' => $rtmJadwalId],
            [
                'undangan' => $undanganPath,
                'presensi' => $presensiPath,
                'dokumentasi' => $dokumentasiPath,
            ]
        );
            return response()->json([
                'message' => 'Lampiran RTM berhasil disimpan',
                'data' => $lampiran
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan lampiran RTM',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($rtmJadwal)
    {
         $lampiran = RtmLampiran::where('rtm_jadwal_id', $rtmJadwal)->first();

        return response()->json($lampiran ? $lampiran : null);
    }
}
