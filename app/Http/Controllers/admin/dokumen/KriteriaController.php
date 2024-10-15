<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\KriteriaStoreUpdateRequest;
use App\Models\Kriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Kriteria!';
        $text = "Apakah Anda yakin ingin menghapus kriteria ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Kriteria',
            'kriteria' => Kriteria::all(),
        ];

        return view('admin.dokumen.kriteria.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Kriteria',
        ];

        return view('admin.dokumen.kriteria.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KriteriaStoreUpdateRequest $request): RedirectResponse
    {
        Kriteria::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('kriteria')->with('success', 'Kriteria berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria): View
    {
        $data = [
            'title' => 'Edit Kriteria',
            'kriteria' => $kriteria,
        ];

        return view('admin.dokumen.kriteria.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KriteriaStoreUpdateRequest $request, Kriteria $kriteria): RedirectResponse
    {
        $kriteria->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('kriteria')->with('success', 'Kriteria berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria): RedirectResponse
    {
        $kriteria->delete();

        return redirect()->route('kriteria')->with('success', 'Kriteria berhasil dihapus!');
    }
}
