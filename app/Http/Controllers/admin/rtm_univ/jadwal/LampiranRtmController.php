<?php

namespace App\Http\Controllers\admin\rtm_univ\jadwal;

use App\Http\Controllers\Controller;
use App\Models\RtmLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LampiranRtmController extends Controller
{
    public function store(Request $request)
    {
        // Validasi dengan pesan yang konsisten
        $validatedData = $request->validate([
            'rtm_jadwal_id' => 'required|exists:rtm_jadwal,id',
            'undangan' => 'required|file|mimes:pdf|max:2048',
            'presensi' => 'required|file|mimes:pdf|max:2048',
            'dokumentasi' => 'required|file|mimes:pdf|max:5120',
        ], [
            'undangan.required' => 'The undangan field is required',
            'presensi.required' => 'The presensi field is required',
            'dokumentasi.required' => 'The dokumentasi field is required',
            // Tambahkan pesan validasi lainnya
        ]);

        Log::debug('Upload Request:', $request->all());

        try {
            $rtmJadwalId = $request->rtm_jadwal_id;
            $timestamp = now()->timestamp;

            // Simpan file
            $undanganPath = $request->file('undangan')->storeAs(
                'lampiran_rtm', "undangan_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $presensiPath = $request->file('presensi')->storeAs(
                'lampiran_rtm', "presensi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );
            $dokumentasiPath = $request->file('dokumentasi')->storeAs(
                'lampiran_rtm', "dokumentasi_rtm_{$rtmJadwalId}_{$timestamp}.pdf"
            );

            // Simpan ke database
            $lampiran = RtmLampiran::updateOrCreate(
                ['rtm_jadwal_id' => $rtmJadwalId],
                [
                    'undangan' => $undanganPath,
                    'presensi' => $presensiPath,
                    'dokumentasi' => $dokumentasiPath,
                ]
            );

            Log::debug('Lampiran Created:', $lampiran->toArray());

            // Response konsisten
            return response()->json([
                'message' => 'Lampiran berhasil disimpan!',
                'data' => $lampiran
            ], 200);

        } catch (\Exception $e) {
            Log::error('Upload Error:', ['error' => $e->getMessage()]);
            
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
