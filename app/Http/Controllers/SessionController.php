<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
