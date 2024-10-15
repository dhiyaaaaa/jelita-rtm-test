<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\StandarStoreUpdateRequest;
use App\Models\Kategori;
use App\Models\Peraturan;
use App\Models\Standar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StandarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Standar!';
        $text = "Apakah Anda yakin ingin menghapus standar ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Standar',
            'standar' => Standar::with(['peraturan'])->get(),
        ];

        return view('admin.dokumen.standar.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Standar',
            'peraturan' => Peraturan::where('status', 1)->get(),
        ];

        return view('admin.dokumen.standar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StandarStoreUpdateRequest $request): RedirectResponse
    {
        $standar = Standar::where('peraturan_id', $request->peraturan)->where('kode', $request->kode)->first();

        if ($standar) {
            return back()->with('error', 'Standar dengan peraturan dan kode yang sama sudah ada!');
        }

        Standar::create([
            'nama' => $request->nama,
            'peraturan_id' => $request->peraturan,
            'kode' => strtoupper($request->kode),
        ]);

        return redirect()->route('standar')->with('success', 'Standar berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Standar $standar): View
    {
        // Sweet Alert
        $title = 'Hapus Kategori!';
        $text = "Apakah Anda yakin ingin menghapus kategori ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => $standar->nama,
            'standar' => $standar,
            'kategori' => Kategori::where('standar_id', $standar->id)->get(),
        ];

        return view('admin.dokumen.standar.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Standar $standar): View
    {
        $data = [
            'title' => 'Tambah Standar',
            'standar' => $standar,
            'peraturan' => Peraturan::where('status', 1)->get(),
        ];

        return view('admin.dokumen.standar.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StandarStoreUpdateRequest $request, Standar $standar): RedirectResponse
    {
        $standar->update([
            'nama' => $request->nama,
            'peraturan_id' => $request->peraturan,
            'kode' => strtoupper($request->kode),
        ]);

        return redirect()->route('standar')->with('success', 'Standar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Standar $standar): RedirectResponse
    {
        $standar->delete();

        return redirect()->route('standar')->with('success', 'Standar berhasil dihapus!');
    }
}
