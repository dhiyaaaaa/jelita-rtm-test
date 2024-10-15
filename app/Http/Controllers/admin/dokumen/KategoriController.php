<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriStoreUpdateRequest;
use App\Models\Kategori;
use App\Models\Standar;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori',
            'standar' => Standar::all(),
        ];

        return view('admin.dokumen.standar.kategori.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KategoriStoreUpdateRequest $request)
    {
        $exists = Kategori::where('standar_id', $request->standar)
            ->where('kode', $request->kode)
            ->exists();

        if ($exists) {
            $standarName = Standar::where('id', $request->standar)->value('nama');
            return back()->withErrors(['kode' => "Kode sudah digunakan untuk standar {$standarName}."])->withInput();
        }

        Kategori::create([
            'nama' => $request->nama,
            'standar_id' => $request->standar,
            'kode' => strtoupper($request->kode),
        ]);

        return redirect()->route('standar.show', $request->standar)->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        $data = [
            'title' => 'Edit Kategori',
            'kategori' => $kategori,
            'standar' => Standar::all(),
        ];

        return view('admin.dokumen.standar.kategori.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KategoriStoreUpdateRequest $request, Kategori $kategori)
    {
        $exists = Kategori::where('standar_id', $request->standar)
            ->where('kode', $request->kode)
            ->where('id', '!=', $kategori->id)
            ->exists();

        if ($exists) {
            $standarName = Standar::where('id', $request->standar)->value('nama');
            return back()->withErrors(['kode' => "Kode sudah digunakan untuk standar {$standarName}."])->withInput();
        }

        $kategori = Kategori::findOrFail($kategori->id);

        $kategori->update([
            'nama' => $request->nama,
            'standar_id' => $request->standar,
            'kode' => strtoupper($request->kode),
        ]);

        return redirect()->route('standar.show', $request->standar)->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
