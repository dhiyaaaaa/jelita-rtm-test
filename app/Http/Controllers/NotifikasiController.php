<?php

namespace App\Http\Controllers;

use App\Models\JawabanAuditor;
use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function kirim_notifikasi_auditor(Request $request, string $jadwalId, string $unitId, string $type, string $instrumenId, string $auditorId): JsonResponse
    {
        if ($request->ajax()) {
            $response = [];

            try {
                $pesan = JawabanAuditor::where('jadwal_audit_id', $jadwalId)
                    ->where(function ($query) use ($unitId) {
                        $query->where('prodi_id', $unitId)
                            ->orWhere('fakultas_id', $unitId)
                            ->orWhere('unit_id', $unitId);
                    })
                    ->where('form_id', $instrumenId)
                    ->pluck('catatan')
                    ->first();

                if (!$pesan) {
                    throw new \Exception('Catatan auditor kosong. Silahkan isi dan save jawaban terlebih dahulu');
                }

                if ($type === 'prodi') {
                    Notifikasi::create([
                        'jadwal_audit_id' => $jadwalId,
                        'prodi_id' => $unitId,
                        'form_id' => $instrumenId,
                        'auditor_id' => $auditorId,
                        'pesan' => $pesan,
                        'status' => 'terkirim',
                    ]);
                } elseif ($type === 'fakultas') {
                    Notifikasi::create([
                        'jadwal_audit_id' => $jadwalId,
                        'fakultas_id' => $unitId,
                        'form_id' => $instrumenId,
                        'auditor_id' => $auditorId,
                        'pesan' => $pesan,
                        'status' => 'terkirim',
                    ]);
                } elseif ($type === 'universitas') {
                    Notifikasi::create([
                        'jadwal_audit_id' => $jadwalId,
                        'unit_id' => $unitId,
                        'form_id' => $instrumenId,
                        'auditor_id' => $auditorId,
                        'pesan' => $pesan,
                        'status' => 'terkirim',
                    ]);
                } else {
                    throw new \Exception('Tipe notifikasi tidak valid.');
                }

                $response['message'] = 'Notifikasi berhasil terkirim!';
            } catch (\Exception $e) {
                $response['message'] = 'Notifikasi gagal terkirim : ' . $e->getMessage();
                // $response['message'] = 'Terjadi kesalahan saat mengirim notifikasi! Harap isi kolom jawaban auditor dan daftar tilik kemudian simpan jawaban terlebih dahulu';

                return response()->json($response, 500);
            }

            return response()->json($response);
        }

        return response()->json([
            'error' => 'Invalid request.',
        ], 400);
    }

    public function kirim_notifikasi_auditee(Request $request, Notifikasi $notifikasi, string $auditee): JsonResponse
    {
        if ($request->ajax()) {
            $response = [];

            try {

                if (!$notifikasi) {
                    throw new \Exception('Notifikasi tidak ditemukan.');
                }

                $notifikasi->update([
                    'auditee_id' => $auditee,
                    'status' => 'diterima',
                ]);

                $response['message'] = 'Notifikasi berhasil diterima!';
            } catch (\Exception $e) {
                if ($auditee === 'null') throw new \Exception('Anda tidak terdaftar sebagai auditan.');

                $response['message'] = 'Error!';

                return response()->json($response, 500);
            }

            return response()->json($response);
        }

        return response()->json([
            'error' => 'Invalid request.',
        ], 400);
    }

    public function notifikasi_selesai(Request $request, Notifikasi $notifikasi): JsonResponse
    {
        if ($request->ajax()) {
            $response = [];

            try {
                if (!$notifikasi) {
                    throw new \Exception('Notifikasi tidak ditemukan.');
                }

                $notifikasi->update([
                    'status' => 'selesai',
                ]);

                $response['message'] = 'Notifikasi selesai!';
            } catch (\Exception $e) {
                // $response['message'] = 'Notifikasi gagal tersimpan : ' . $e->getMessage();
                $response['message'] = 'Terjadi kesalahan!';

                return response()->json($response, 500);
            }

            return response()->json($response);
        }

        return response()->json([
            'error' => 'Invalid request.',
        ], 400);
    }
}
