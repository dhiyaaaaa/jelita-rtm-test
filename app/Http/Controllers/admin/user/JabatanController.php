<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\JabatanStoreRequest;
use App\Http\Requests\JabatanUpdateRequest;
use App\Models\Jabatan;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JabatanController extends Controller
{
    // Ajax Request
    public function get_unit_by_type(Request $request, string $type): JsonResponse
    {
        if ($request->ajax()) {
            try {
                if ($type === 'universitas') {
                    $units = Unit::pluck('nama', 'id');
                } else {
                    $units = null;
                }

                return response()->json($units);
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
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Hapus Jabatan!';
        $text = "Apakah Anda yakin ingin menghapus jabatan ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Jabatan',
            'jabatans' => Jabatan::all(),
        ];
        return view('admin.user.jabatan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Jabatan',
        ];
        return view('admin.user.jabatan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JabatanStoreRequest $request): RedirectResponse
    {
        $jabatan = Jabatan::create([
            'nama' => $request->nama,
            'type' => $request->type,
            'unik' => $request->unik
        ]);

        Setting::create([
            'nama_setting' => $jabatan->type,
            'jabatan_id' => $jabatan->id,
        ]);

        if ($request->type === 'universitas') {
            $jabatan->unit()->attach($request->unit);
        }

        return redirect()->route('jabatan')->with('success', 'Jabatan berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan): View
    {
        // Sweet Alert
        $title = 'Hapus User!';
        $text = "Apakah Anda yakin ingin menghapus user ini? Semua yang berkaitan dengan user akan terhapus";
        confirmDelete($title, $text);

        $users = User::whereHas('jabatan', function ($query) use ($jabatan) {
            $query->where('id', $jabatan->id);
        })->with(['prodi.fakultas', 'fakultas', 'unit'])->get();

        $data = [
            'title' => 'Jabatan ' . $jabatan->nama,
            'jabatan' => $jabatan,
            'users' => $users,
        ];

        return view('admin.user.jabatan.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jabatan $jabatan): View
    {
        $data = [
            'title' => 'Edit Jabatan',
            'jabatan' => $jabatan,
        ];
        return view('admin.user.jabatan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JabatanUpdateRequest $request, Jabatan $jabatan): RedirectResponse
    {
        $jabatan->update([
            'nama' => $request->nama,
            'type' => $request->type,
            'unik' => $request->unik
        ]);

        if ($request->type === 'universitas') {
            $jabatan->unit()->sync($request->unit);
        } else {
            $jabatan->unit()->detach();
        }

        return redirect()->route('jabatan')->with('success', 'Jabatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();

        return back()->with('success', 'Jabatan berhasil dihapus!');
    }
}
