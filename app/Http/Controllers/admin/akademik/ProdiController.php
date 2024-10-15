<?php

namespace App\Http\Controllers\admin\akademik;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdiStoreUpdateRequest;
use App\Models\Fakultas;
use App\Models\Jenjang;
use App\Models\Prodi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get_prodi(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {

                $prodis = Prodi::with(['fakultas', 'jenjang'])
                    ->select(['nama', 'id', 'fakultas_id', 'jenjang_id']);

                return DataTables::of($prodis)
                    ->addColumn('fakultas', fn($row) => ucwords($row->fakultas->nama))
                    ->addColumn('jenjang', fn($row) => ucwords($row->jenjang->nama))
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                            <a class="dropdown-item" href="' . route('prodi.edit', $row->id) . '">Edit</a>
                            <a class="dropdown-item" href="' . route('prodi.delete', $row->id) . '" data-confirm-delete="true">Hapus</a>
                        </div>
                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search.value')) {
                            $searchValue = $request->input('search.value');
                            $query->where('nama', 'ilike', "%{$searchValue}%")
                                ->orWhereHas('fakultas', function ($query) use ($searchValue) {
                                    $query->where('nama', 'ilike', "%{$searchValue}%");
                                })
                                ->orWhereHas('jenjang', function ($query) use ($searchValue) {
                                    $query->where('nama', 'ilike', "%{$searchValue}%");
                                });
                        }
                    })
                    ->make(true);
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

    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Prodi!';
        $text = "Apakah Anda yakin ingin menghapus prodi ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Program Studi',
        ];
        return view('admin.akademik.prodi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Prodi',
            'fakultas' => Fakultas::get()->all(),
            'jenjang' => Jenjang::get()->all(),
        ];
        return view('admin.akademik.prodi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdiStoreUpdateRequest $request): RedirectResponse
    {
        Prodi::create([
            'jenjang_id' => $request->jenjang,
            'fakultas_id' => $request->fakultas,
            'nama' => $request->nama,
        ]);

        return redirect()->route('prodi')->with('success', 'Prodi berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi): View
    {
        $data = [
            'title' => 'Edit Prodi',
            'prodi' => $prodi,
            'fakultas' => Fakultas::get()->all(),
            'jenjang' => Jenjang::get()->all(),
        ];
        return view('admin.akademik.prodi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdiStoreUpdateRequest $request, Prodi $prodi): RedirectResponse
    {
        $prodi->update([
            'jenjang_id' => $request->jenjang,
            'fakultas_id' => $request->fakultas,
            'nama' => $request->nama,
        ]);

        return redirect()->route('prodi')->with('success', 'Prodi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prodi $prodi): RedirectResponse
    {
        $prodi->delete();

        return back()->with('success', 'Prodi berhasil dihapus!');
    }
}
