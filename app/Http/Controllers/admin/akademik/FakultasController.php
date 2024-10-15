<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\FakultasStoreUpdateRequest;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FakultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Fakultas!';
        $text = "Apakah Anda yakin ingin menghapus fakultas ini?";
        confirmDelete($title, $text);

        $fakultas = Fakultas::all();

        $data = [
            'title' => 'Fakultas',
            'fakultas' => $fakultas,
        ];
        return view('admin.akademik.fakultas.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Fakultas',
        ];
        return view('admin.akademik.fakultas.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FakultasStoreUpdateRequest $request): RedirectResponse
    {
        Fakultas::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('fakultas')->with('success', 'Fakultas berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fakultas $fakultas): View
    {
        // Sweet Alert
        $title = 'Hapus Prodi!';
        $text = "Apakah Anda yakin ingin menghapus prodi ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Fakultas' . ' ' . $fakultas->nama,
            'fakultas' => $fakultas,
            'prodis' => Prodi::with(['fakultas', 'jenjang'])->where('fakultas_id', $fakultas->id)->get(),
        ];
        return view('admin.akademik.fakultas.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fakultas $fakultas): View
    {
        $data = [
            'title' => 'Edit Fakultas',
            'fakultas' => $fakultas,
        ];
        return view('admin.akademik.fakultas.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FakultasStoreUpdateRequest $request, Fakultas $fakultas): RedirectResponse
    {
        $fakultas->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('fakultas.show', $fakultas->id)->with('success', 'Fakultas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        $fakultas->delete();

        return redirect()->route('fakultas')->with('success', 'Fakultas berhasil dihapus!');
    }
}
