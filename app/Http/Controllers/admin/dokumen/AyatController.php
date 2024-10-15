<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\AyatStoreUpdateRequest;
use App\Models\Ayat;
use App\Models\Pasal;
use App\Models\Peraturan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AyatController extends Controller
{
    public function get_pasal_by_peraturan(Request $request, string $peraturan): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $pasal = Pasal::where('peraturan_id', $peraturan)->pluck('pasal', 'id');

                return response()->json($pasal);
            } catch (\Exception $e) {

                return response()->json([
                    'error' => 'Error'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Invalid Request.'
        ], 400);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Ayat',
            'peraturan' => Peraturan::where('status', 1)->get(),
            'pasal' => Pasal::all(),
        ];
        return view('admin.dokumen.peraturan.ayat.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AyatStoreUpdateRequest $request): RedirectResponse
    {
        $ayat = Ayat::where('peraturan_id', $request->peraturan)->where('pasal_id', $request->pasal)->where('ayat', $request->ayat)->first();

        if ($ayat) {
            return back()->with('error', 'Ayat sudah ada!');
        }

        Ayat::create([
            'ayat' => $request->ayat,
            'isi' => $request->isi,
            'pasal_id' => $request->pasal,
            'peraturan_id' => $request->peraturan,
        ]);

        return redirect()->route('pasal.show', $request->pasal)->with('success', 'Ayat berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ayat $ayat): View
    {
        $data = [
            'title' => 'Edit Ayat',
            'ayat' => $ayat,
            'peraturan' => Peraturan::where('status', 1)->get(),
            'pasal' => Pasal::all(),
        ];
        return view('admin.dokumen.peraturan.ayat.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AyatStoreUpdateRequest $request, Ayat $ayat): RedirectResponse
    {
        $ayat->update([
            'ayat' => $request->ayat,
            'isi' => $request->isi,
            'pasal_id' => $request->pasal,
            'peraturan_id' => $request->peraturan,
        ]);

        return redirect()->route('pasal.show', $request->pasal)->with('success', 'Ayat berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ayat $ayat): RedirectResponse
    {
        $ayat->delete();

        return back()->with('success', 'Ayat berhasil dihapus!');
    }
}
