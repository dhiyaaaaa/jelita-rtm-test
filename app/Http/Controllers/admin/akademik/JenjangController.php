<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenjangStoreUpdateRequest;
use App\Models\Jenjang;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JenjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Jenjang!';
        $text = "Apakah Anda yakin ingin menghapus jenjang ini?";
        confirmDelete($title, $text);

        $jenjangs = Jenjang::all();

        $data = [
            'title' => 'Jenjang',
            'jenjangs' => $jenjangs,
        ];

        return view('admin.akademik.jenjang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Jenjang',
        ];

        return view('admin.akademik.jenjang.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JenjangStoreUpdateRequest $request): RedirectResponse
    {
        Jenjang::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('jenjang')->with('success', 'Jenjang berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jenjang $jenjang): View
    {
        // Sweet Alert
        $title = 'Hapus Prodi!';
        $text = "Apakah Anda yakin ingin menghapus prodi ini?";
        confirmDelete($title, $text);

        $prodis = Prodi::with(['fakultas', 'jenjang'])->where('jenjang_id', $jenjang->id)->get();

        $data = [
            'title' => 'Jenjang' . ' ' . $jenjang->nama,
            'jenjang' => $jenjang,
            'prodis' => $prodis,
        ];
        return view('admin.akademik.jenjang.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jenjang $jenjang): View
    {
        $data = [
            'title' => 'Edit Jenjang',
            'jenjang' => $jenjang,
        ];
        return view('admin.akademik.jenjang.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JenjangStoreUpdateRequest $request, Jenjang $jenjang): RedirectResponse
    {
        $jenjang->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('jenjang.show', $jenjang->id)->with('success', 'Jenjang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jenjang $jenjang): RedirectResponse
    {
        $jenjang->delete();

        return redirect()->route('jenjang')->with('success', 'Jenjang berhasil dihapus!');
    }
}
