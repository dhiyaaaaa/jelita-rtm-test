<?php

namespace App\Http\Controllers;

use App\Models\RtmJadwal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class SessionController extends Controller
{
    public function session_auditee(Request $request, string $jadwalAudit, string $unit, string $auditee): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_data-page_' .  $page . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit . '-auditeeId_' . $auditee;

                    $formData = $request->all();

                    $formData[('page_' . $i)] = $page;

                    session([$sessionKey => $formData]);
                }
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Session Auditee per Nomor (backup)
    public function session_auditee_per_nomor(Request $request, string $jadwalAudit, string $unit, string $auditee, string $formId) {}

    public function session_auditor(Request $request, string $jadwalAudit, string $unit, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_audit_dokumen-page_' .  $page . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit . '-auditorId_' . $auditor;

                    $formData[('page_' . $i)] = $page;
                    $formData = $request->all();
                }
                session([$sessionKey => $formData]);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Session Auditor per Nomor (backup)
    public function session_auditor_per_nomor(Request $request, string $jadwalAudit, string $unit, string $auditee, string $formId) {}

    public function session_ptk_auditor(Request $request, string $ptk, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_ptk_auditor-page_' .  $page . '-ptkId_' . $ptk . '-auditorId_' . $auditor;

                    $formData = $request->all();
                    $formData[('page_' . $i)] = $page;

                    session([$sessionKey => $formData]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Session PTK Auditor per Nomor (backup)
    public function session_ptk_auditor_per_nomor(Request $request, string $ptk, string $auditor, string $formId) {}

    public function session_ptk_auditee(Request $request, string $ptk, string $auditeeId): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_ptk_auditee-page_' .  $page . '-ptkId_' . $ptk . '-auditeeId_' . $auditeeId;

                    $formData = $request->all();
                    $formData[('page_' . $i)] = $page;

                    session([$sessionKey => $formData]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Session PTK Auditee per Nomor (backup)
    public function session_ptk_auditee_per_nomor(Request $request, string $ptk, string $auditeeId, string $formId) {}

    public function session_laporan_auditor(Request $request, string $laporan, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');

                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_laporan-page_' .  $page . '-laporanId_' . $laporan . '-auditorId_' . $auditor;

                    $formData = $request->all();
                    $formData[('page_' . $i)] = $page;

                    session([$sessionKey => $formData]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    // Session Laporan Auditor per Nomor (backup)
    public function session_laporan_auditor_per_nomor(Request $request, string $laporan, string $auditor, string $formId) {}

    public function session_peer_assessment(Request $request, string $auditor, string $jadwalAudit, string $unit): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $sessionKey = 'peer_assessment-auditor_' .  $auditor . '-jadwalId_' . $jadwalAudit . '-unitId_' . $unit;

                $formData = $request->all();
                session([$sessionKey => $formData]);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    public function session_rtm_rtl_dekan(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            $currentPage = $request->input('currentPage', 1);
            $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;

            $formData = $request->all();
            session([$sessionKey => $formData]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error saving RTL session:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
        }
    }

    // public function session_rtm_rtl_prodi(Request $request, string $rtmRtl, string $auditee): JsonResponse
    // {
    //     if (!$request->ajax()) {
    //         return response()->json(['error' => 'Invalid Request.'], 400);
    //     }

    //     try {
    //         $currentPage = $request->input('currentPage', 1);
    //         $sessionKey = 'form_rtm_rtl_prodi-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;

    //         $formData = [];
    //         $formIds = json_decode($request->input('formIds', '[]'), true);
            
    //         foreach ($formIds as $formId) {
    //             $kriteriaIds = json_decode($request->input("kriteriaIds_{$formId}", '[]'), true);
                
    //             foreach ($kriteriaIds as $kriteriaId) {
    //                 $tindakanKey = "tindakan_{$formId}_{$kriteriaId}";
    //                 $tindakanData = json_decode($request->input($tindakanKey, '[]'), true);
                    
    //                 if (is_array($tindakanData)) {
    //                     if (!isset($formData[$formId])) {
    //                         $formData[$formId] = [];
    //                     }
    //                     $formData[$formId][$kriteriaId] = [
    //                         'tindakan' => array_filter($tindakanData, function($item) {
    //                             return !empty($item['tindakan']) || !empty($item['pic']) || !empty($item['waktu']);
    //                         })
    //                     ];
    //                 }
    //             }
    //         }

    //         session([$sessionKey => $formData]);

    //         return response()->json(['success' => true, 'message' => 'Data berhasil disimpan sementara']);
    //     } catch (\Exception $e) {
    //         Log::error('Error saving RTL Prodi session:', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
    //     }
    // }
    

    public function session_rtm_catatan(Request $request, RtmJadwal $rtmJadwal)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {

            $sessionKey = 'session_catatan_' . $rtmJadwal->id;

             session([$sessionKey => $request->only(['judul', 'isi'])]);
            
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan sementara']);

        } catch (\Exception $e) {
            Log::error('Error saving RTM Catatan session:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
        }
    }
    
    public function session_rtm_rtl_univ(Request $request, string $rtmRtlUniv): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }
                    $sessionKey = 'form_rtmRtl_univ-page_' . $page . '-rtmRtlUnivId_' . $rtmRtlUniv;
    
                    $formData = $request->all();
                    $formData[('page_' . $i)] = $page;

                    session([$sessionKey => $formData]);
                }
            
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }
        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }

    public function session_rtl_auditee(Request $request, string $rtl, string $auditee): JsonResponse
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            $currentPage = $request->input('currentPage', 1);
            $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl . '-auditeeId_' . $auditee;

            $formData = $request->all();
            session([$sessionKey => $formData]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error saving RTL session:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
        }
    }

    // public function session_rtl_auditee(Request $request, string $rtl, string $auditee): JsonResponse
    // {
    //     if (!$request->ajax()) {
    //         return response()->json(['error' => 'Invalid Request.'], 400);
    //     }

    //     try {
    //         $currentPage = $request->input('currentPage', 1);
    //         $sessionKey = 'form_rtl-page_' . $currentPage . '-rtlId_' . $rtl . '-auditeeId_' . $auditee;

    //         $formData = [];
    //         $formIds = json_decode($request->input('formIds'), true);

    //         foreach ($formIds as $formId) {
    //             $tindakan = json_decode($request->input('tindakan_' . $formId), true);
                
    //             $formData[$formId] = [
    //                 'tindakan' => [],
    //             ];

    //             if (is_array($tindakan)) {
    //                 foreach ($tindakan as $row) {
    //                     if (!empty($row['tindakan']) || !empty($row['bukti'])) {
    //                         $formData[$formId]['tindakan'][] = [
    //                             'tindakan' => $row['tindakan'] ?? null,
    //                             'bukti' => $row['bukti'] ?? null,
    //                         ];
    //                     }
    //                 }
    //             }
    //         }

    //         session([$sessionKey => $formData]);

    //         return response()->json(['success' => true]);
    //     } catch (\Exception $e) {
    //         Log::error('Error saving RTL session:', ['error' => $e->getMessage()]);
    //         return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
    //     }
    // }

    public function session_monitoring_auditor(Request $request, string $monitoring, string $auditor): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $totalPages = $request->input('totalPage');
                for ($i = 1; $i <= $totalPages; $i++) {
                    $page = $request->input(('page_' . $i));

                    if (empty($page)) {
                        continue;
                    }

                    $sessionKey = 'form_monitoring-page_' . $i . '-monitoringId_' . $monitoring . '-auditorId_' . $auditor;

                    $formData = $request->all();
                    $formData[('page_' . $i)] = $page;
                    Log::info('Menyimpan data ke session:', [$sessionKey => $formData]);
                    session([$sessionKey => $formData]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }

        return response()->json(['error' => 'Invalid Request.'], 400);
    }

    public function session_monitoring_auditor_per_nomor(Request $request, string $monitoring, string $auditorId, string $formId) {}

}
