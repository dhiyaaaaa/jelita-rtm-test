<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeraturanStoreUpdateRequest;
use App\Models\Pasal;
use App\Models\Peraturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeraturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Peraturan!';
        $text = "Apakah Anda yakin ingin menghapus peraturan ini?";
        confirmDelete($title, $text);

       $data = [
           'title' => 'Peraturan',
           'peraturan' => Peraturan::get()->all(),
       ];
       return view('admin.dokumen.peraturan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Peraturan',
        ];
        return view('admin.dokumen.peraturan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeraturanStoreUpdateRequest $request): RedirectResponse
    {
        Peraturan::create([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'status' => $request->status,
        ]);

        return redirect()->route('peraturan')->with('success', 'Peraturan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peraturan $peraturan): View
    {
        // Sweet Alert
        $title = 'Hapus Pasal!';
        $text = "Apakah Anda yakin ingin menghapus pasal ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Peraturan',
            'peraturan' => $peraturan,
            'pasal' => Pasal::where('peraturan_id', $peraturan->id)->get()
        ];
        return view('admin.dokumen.peraturan.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peraturan $peraturan): View
    {
        $data = [
            'title' => 'Edit Peraturan',
            'peraturan' => $peraturan
        ];

        return view('admin.dokumen.peraturan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeraturanStoreUpdateRequest $request, Peraturan $peraturan): RedirectResponse
    {
        $peraturan->update([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'status' => $request->status,
        ]);

        return redirect()->route('peraturan')->with('success', 'Peraturan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peraturan $peraturan): RedirectResponse
    {
        $peraturan->delete();

        return redirect()->route('peraturan')->with('success', 'Peraturan berhasil dihapus!');
    }
}
