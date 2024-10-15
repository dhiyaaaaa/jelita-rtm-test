<?php

namespace App\Http\Controllers\admin\assessment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PertanyaanStoreUpdateRequest;
use App\Models\AssessmentPertanyaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PertanyaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Pertanyaan!';
        $text = 'Apakah Anda yakin ingin menghapus pertanyaan ini?';
        confirmDelete($title, $text);

        $pertanyaan = AssessmentPertanyaan::all();
        $data = [
            'title' => 'Pertanyaan',
            'pertanyaan' => $pertanyaan,
        ];

        return view('admin.assessment.pertanyaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Pertanyaan',
        ];

        return view('admin.assessment.pertanyaan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PertanyaanStoreUpdateRequest $request): RedirectResponse
    {
        AssessmentPertanyaan::create([
            'pertanyaan' => $request->pertanyaan,
        ]);

        return redirect()->route('pertanyaan')->with('success', 'Pertanyaan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssessmentPertanyaan $pertanyaan): View
    {
        $data = [
            'title' => 'Edit Pertanyaan',
            'pertanyaan' => $pertanyaan,
        ];

        return view('admin.assessment.pertanyaan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PertanyaanStoreUpdateRequest $request, AssessmentPertanyaan $pertanyaan): RedirectResponse
    {
        $pertanyaan->update([
            'pertanyaan' => $request->pertanyaan,
        ]);

        return redirect()->route('pertanyaan')->with('success', 'Pertanyaan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssessmentPertanyaan $pertanyaan): RedirectResponse
    {
        $pertanyaan->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus');
    }
}
