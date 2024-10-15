<?php

namespace App\Http\Controllers\admin\audit;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessmentFormStoreUpdateRequest;
use App\Models\AssessmentForm;
use App\Models\AssessmentPertanyaan;
use App\Models\JadwalAudit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class AssessmentController extends Controller
{
    // Ajax Request
    public function get_assessment_pertanyaan(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $pertanyaan = AssessmentPertanyaan::select(['id', 'pertanyaan']);

                return DataTables::of($pertanyaan)
                    ->addColumn('checkbox', function ($item) {
                        return $item->id;
                    })
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error',
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid request.',
        ], 400);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(JadwalAudit $jadwalAudit): View
    {
        $data = [
            'title' => 'Tambah Assessment',
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.audit.jadwal_audit.assessment.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssessmentFormStoreUpdateRequest $request, JadwalAudit $jadwalAudit): RedirectResponse
    {
        foreach ($request->pertanyaan as $pertanyaan) {
            AssessmentForm::create([
                'jadwal_audit_id' => $jadwalAudit->id,
                'assessment_pertanyaan_id' => $pertanyaan,
            ]);
        }

        return redirect()->route('jadwal_audit')->with('success', 'Assessment berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalAudit $jadwalAudit): View
    {
        $pertanyaan = AssessmentPertanyaan::all();
        $pertanyaanSelected = AssessmentForm::where('jadwal_audit_id', $jadwalAudit->id)->pluck('assessment_pertanyaan_id')->toArray();

        $data = [
            'title' => 'Ubah Assessment',
            'pertanyaan' => $pertanyaan,
            'pertanyaanSelected' => $pertanyaanSelected,
            'jadwalAudit' => $jadwalAudit,
        ];

        return view('admin.audit.jadwal_audit.assessment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssessmentFormStoreUpdateRequest $request, JadwalAudit $jadwalAudit): RedirectResponse
    {
        $lama = AssessmentForm::where('jadwal_audit_id', $jadwalAudit->id)->pluck('assessment_pertanyaan_id')->toArray();

        $hapus = array_diff($lama, $request->pertanyaan);
        $tambah = array_diff($request->pertanyaan, $lama);

        AssessmentForm::where('jadwal_audit_id', $jadwalAudit->id)->whereIn('assessment_pertanyaan_id', $hapus)->delete();

        foreach ($tambah as $pertanyaan) {
            AssessmentForm::create([
                'jadwal_audit_id' => $jadwalAudit->id,
                'assessment_pertanyaan_id' => $pertanyaan,
            ]);
        }

        return redirect()->route('jadwal_audit')->with('success', 'Assessment berhasil diperbarui!');
    }
}
