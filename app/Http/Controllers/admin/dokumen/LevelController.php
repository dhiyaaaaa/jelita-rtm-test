<?php

namespace App\Http\Controllers\admin\dokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\LevelStoreUpdateRequest;
use App\Models\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Level!';
        $text = "Apakah Anda yakin ingin menghapus level ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Level',
            'level' => Level::all(),
        ];

        return view('admin.dokumen.level.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Level',
        ];

        return view('admin.dokumen.level.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LevelStoreUpdateRequest $request): RedirectResponse
    {
        Level::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('level')->with('success', 'Level berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level): View
    {
        $data = [
            'title' => 'Edit Level',
            'level' => $level
        ];

        return view('admin.dokumen.level.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LevelStoreUpdateRequest $request, Level $level): RedirectResponse
    {
        $level->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('level')->with('success', 'Level berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level): RedirectResponse
    {
        if ($level->slug === 'prodi' || $level->slug === 'fakultas' || $level->slug === 'universitas') {
            return back()->with('error', 'Level ini tidak bisa dihapus!');
        }

        $level->delete();

        return back()->with('success', 'Level berhasil dihapus!');
    }
}
