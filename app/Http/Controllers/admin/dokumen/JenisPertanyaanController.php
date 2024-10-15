<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisPertanyaanStoreUpdateRequest;
use App\Models\JenisPertanyaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenisPertanyaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Jenis Pertanyaan!';
        $text = "Apakah Anda yakin ingin menghapus jenis pertanyaan ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Jenis Pertanyaan',
            'jenisPertanyaan' => JenisPertanyaan::all(),
        ];

        return view('admin.dokumen.jenis_pertanyaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Jenis Pertanyaan',
        ];

        return view('admin.dokumen.jenis_pertanyaan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JenisPertanyaanStoreUpdateRequest $request): RedirectResponse
    {
        JenisPertanyaan::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('jenis_pertanyaan')->with('success', 'Jenis Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisPertanyaan $jenisPertanyaan): View
    {
        $data = [
            'title' => 'Edit JenisPertanyaan',
            'jenisPertanyaan' => $jenisPertanyaan,
        ];

        return view('admin.dokumen.jenis_pertanyaan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JenisPertanyaanStoreUpdateRequest $request, JenisPertanyaan $jenisPertanyaan): RedirectResponse
    {
        $jenisPertanyaan->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('jenis_pertanyaan')->with('success', 'Jenis Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisPertanyaan $jenisPertanyaan): RedirectResponse
    {
        $jenisPertanyaan->delete();

        return redirect()->route('jenis_pertanyaan')->with('success', 'Jenis Pertanyaan berhasil dihapus!');
    }
}
