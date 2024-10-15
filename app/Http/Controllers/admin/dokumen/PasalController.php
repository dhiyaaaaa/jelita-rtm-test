<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasalStoreUpdateRequest;
use App\Models\Ayat;
use App\Models\Pasal;
use App\Models\Peraturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasalController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Pasal',
            'peraturan' => Peraturan::where('status', 1)->get(),
        ];
        return view('admin.dokumen.peraturan.pasal.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PasalStoreUpdateRequest $request): RedirectResponse
    {
        $pasal = Pasal::where('peraturan_id', $request->peraturan)->where('pasal', $request->pasal)->first();

        if ($pasal) {
            return back()->with('error', 'Pasal sudah ada!');
        }

        Pasal::create([
            'pasal' => $request->pasal,
            'isi' => $request->isi,
            'peraturan_id' => $request->peraturan,
        ]);

        return redirect()->route('peraturan')->with('success', 'Pasal berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasal $pasal): View
    {
        // Sweet Alert
        $title = 'Hapus Ayat!';
        $text = "Apakah Anda yakin ingin menghapus ayat ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Pasal' . ' ' . $pasal->pasal,
            'pasal' => $pasal,
            'ayat' => Ayat::where('pasal_id', $pasal->id)->get()
        ];

        return view('admin.dokumen.peraturan.pasal.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasal $pasal): View
    {
        $data = [
            'title' => 'Edit Pasal',
            'peraturan' => Peraturan::where('status', 1)->get(),
            'pasal' => $pasal
        ];
        return view('admin.dokumen.peraturan.pasal.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PasalStoreUpdateRequest $request, Pasal $pasal): RedirectResponse
    {
        $pasal->update([
            'pasal' => $request->pasal,
            'isi' => $request->isi,
            'peraturan_id' => $request->peraturan,
        ]);

        return redirect()->route('pasal.show', $pasal->id)->with('success', 'Pasal berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasal $pasal): RedirectResponse
    {
        $pasal->delete();

        return redirect()->route('peraturan')->with('success', 'Pasal berhasil dihapus!');
    }
}
