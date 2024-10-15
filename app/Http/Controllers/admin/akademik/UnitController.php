<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitStoreUpdateRequest;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Unit!';
        $text = "Apakah Anda yakin ingin menghapus unit ini?";
        confirmDelete($title, $text);

        $units = Unit::all();
        $data = [
            'title' => 'Unit',
            'units' => $units,
        ];
        return view('admin.akademik.unit.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Unit',
        ];
        return view('admin.akademik.unit.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreUpdateRequest $request): RedirectResponse
    {
        Unit::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('unit')->with('success', 'Unit berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        $data = [
            'title' => 'Edit Unit',
            'unit' => $unit,
        ];
        return view('admin.akademik.unit.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitStoreUpdateRequest $request, Unit $unit): RedirectResponse
    {
        $unit->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('unit')->with('success', 'Unit berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('unit')->with('success', 'Unit berhasil dihapus!');
    }
}
