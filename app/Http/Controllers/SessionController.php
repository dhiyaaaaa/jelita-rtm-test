<?php

namespace App\Http\Controllers;

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

    //session RTL Auditee
    // public function session_rtl_auditee(Request $request, string $rtl, string $auditee): JsonResponse
    // {
    //     if ($request->ajax()) {
    //         try {
    //             $totalPages = $request->input('totalPage');
    //             for ($i = 1; $i <= $totalPages; $i++) {
    //                 $page = $request->input(('page_' . $i));

    //                 if (empty($page)) {
    //                     continue;
    //                 }

    //                 $sessionKey = 'form_rtl_auditee-page_' .  $page . '-rtlId_' . $rtl . '-auditeeId_' . $auditee;

    //                 $formData = $request->all();
    //                 $formData[('page_' . $i)] = $page;

    //                 // Debugging: Periksa data yang akan disimpan di session
    //                 Log::info('Menyimpan data ke session:', [$sessionKey => $formData]);
    //                 session([$sessionKey => $formData]);
    //             }

    //             return response()->json(['success' => true]);
    //         } catch (\Exception $e) {
    //             return response()->json(['success' => false, 'message' => 'Error.'], 500);
    //         }
    //     }

    //     return response()->json([
    //         'error' => 'Invalid Request.'
    //     ], 400);
    // }

    // // Session RTL Auditee per Nomor (backup)
    // public function session_rtl_auditee_per_nomor(Request $request, string $rtl, string $auditee, string $formId) {}

    // //
    // public function session_monitoring_auditor(Request $request, string $monitoring, string $auditor): JsonResponse
    // {
    //     if ($request->ajax()) {
    //         try {
    //             $totalPages = $request->input('totalPage');
    //             for ($i = 1; $i <= $totalPages; $i++) {
    //                 $page = $request->input(('page_' . $i));

    //                 if (empty($page)) {
    //                     continue;
    //                 }

    //                 $sessionKey = 'form_monitoring-page_' . $i . '-monitoringId_' . $monitoring . '-auditorId_' . $auditor;

    //                 $formData = $request->all();
    //                 $formData[('page_' . $i)] = $page;
    //                 Log::info('Menyimpan data ke session:', [$sessionKey => $formData]);
    //                 session([$sessionKey => $formData]);
    //             }

    //             return response()->json(['success' => true]);
    //         } catch (\Exception $e) {
    //             return response()->json(['success' => false, 'message' => 'Error.'], 500);
    //         }
    //     }

    //     return response()->json(['error' => 'Invalid Request.'], 400);
    // }

    //public function session_monitoring_auditor_per_nomor(Request $request, string $monitoring, string $auditorId, string $formId) {}

    public function session_rtm_rtl_dekan(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        // Validate that the request is an AJAX request
        if (!$request->ajax()) {
            return response()->json(['error' => 'Invalid Request.'], 400);
        }

        try {
            // Log::info($request->all());
            // Get the current page number, default to 1 if not provided
            $currentPage = $request->input('currentPage', 1);

            // Create a unique session key based on current page, RTM RTL ID, and Auditee ID
            $sessionKey = 'form_rtm_rtl-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;

            $formIds = json_decode($request->input('formIds'), true);

            $sessionData = [];

            // Process each form ID
            foreach ($formIds as $formId) {
                // Decode tindakan (action), pic (person in charge), and waktu (time) for each form
                $tindakan = json_decode($request->input('rows_' . $formId), true);

                // Initialize data for this form
                $sessionData[$formId] = [
                    'tindakan' => [],
                ];

                // Process each row of actions for this form
                if (is_array($tindakan)) {
                    foreach ($tindakan as $row) {
                        // Only add non-empty rows
                        if (!empty($row['tindakan']) || !empty($row['pic']) || !empty($row['waktu'])) {
                            $sessionData[$formId]['tindakan'][] = [
                                'tindakan' => $row['tindakan'] ?? null,
                                'pic' => $row['pic'] ?? null,
                                'waktu' => $row['waktu'] ?? null,
                            ];
                        }
                    }
                }
            }

            // Save the processed data to the session
            session([$sessionKey => $sessionData]);

            Log::info('Session Controller sessionData', $sessionData);

            // Return success response
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Log any errors and return error response
            Log::error('Error saving session:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan session.'], 500);
        }
    }

    public function session_rtm_rtl_prodi(Request $request, string $rtmRtl, string $auditee): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $formData = $request->all();
                $currentPage = $request->input('currentPage');
    
                if (empty($currentPage)) {
                    return response()->json(['success' => false, 'message' => 'Halaman tidak valid.'], 422);
                }
    
                // Pastikan $formData tidak null
                if (empty($formData)) {
                    return response()->json(['success' => false, 'message' => 'Data tidak boleh kosong.'], 422);
                }
    
                $sessionKey = 'form_rtm_rtl_prodi-page_' . $currentPage . '-rtmRtlId_' . $rtmRtl . '-auditeeId_' . $auditee;
                session([$sessionKey => $formData]);
    
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Error.'], 500);
            }
        }
    
        return response()->json(['error' => 'Invalid Request.'], 400);
    }
}
