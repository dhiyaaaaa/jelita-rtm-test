<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    // Request Ajax
    // Get Users
    public function get_users(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $users = User::select(['id', 'name', 'email'])
                    ->with(['jabatan', 'roles', 'prodi.jenjang', 'fakultas', 'unit'])
                    ->orderBy('created_at', 'DESC');

                return DataTables::of($users)
                    ->addColumn('jabatan', function ($row) {
                        return $row->jabatan->isNotEmpty() ? $row->jabatan->pluck('nama')->implode(', ') : '-';
                    })
                    ->addColumn('prodi_fakultas_unit', function ($row) {
                        if ($row->prodi->isNotEmpty()) {
                            return "Prodi " . $row->prodi->pluck('nama')->implode(', ') . ' ' . $row->prodi->first()->jenjang->nama;
                        } elseif ($row->fakultas->isNotEmpty()) {
                            return "Fakultas " . $row->fakultas->pluck('nama')->implode(', ');
                        } elseif ($row->unit->isNotEmpty()) {
                            return $row->unit->pluck('nama')->implode(', ');
                        }
                        return '';
                    })

                    ->addColumn('role', function ($row) {
                        return $row->roles->isNotEmpty()
                            ? $row->roles->pluck('name')->map(function ($name) {
                                $badgeClass = match ($name) {
                                    'pj_prodi' => 'badge-primary',
                                    'pj_fakultas' => 'badge-custom-purple',
                                    'pj_universitas' => 'badge-custom-fuchsia',
                                    'gkm' => 'badge-custom-orange',
                                    'gpm' => 'badge-info',
                                    'auditor' => 'badge-secondary',
                                    'pusjamu' => 'badge-danger',
                                    default => 'badge-ligth',
                                };
                                return "<span class='badge {$badgeClass}'>" . strtoupper($name) . "</span>";
                            })->implode(' ')
                            : '-';
                    })

                    ->addColumn('action', function ($row) {
                        $btn = '<div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $row->id . '">
                    <a class="dropdown-item" href="' . route('user.edit', $row->id) . '">Edit</a>
                    <a class="dropdown-item" href="' . route('user.delete', $row->id) . '" data-confirm-delete="true">Hapus</a>
                    </div>
                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['role', 'action'])
                    ->filter(function ($query) use ($request) {
                        if ($request->has('search.value')) {
                            $searchValue = $request->input('search.value');
                            $query->where('name', 'ilike', "%{$searchValue}%")
                                ->orWhere('email', 'ilike', "%{$searchValue}%")
                                ->orWhereHas('jabatan', function ($query) use ($searchValue) {
                                    $query->where('nama', 'ilike', "%{$searchValue}%");
                                })
                                ->orWhereHas('roles', function ($query) use ($searchValue) {
                                    $query->where('name', 'ilike', "%{$searchValue}%");
                                })
                                ->orWhereHas('prodi', function ($query) use ($searchValue) {
                                    $query->where('nama', 'ilike', "%{$searchValue}%");
                                })
                                ->orWhereHas('fakultas', function ($query) use ($searchValue) {
                                    $query->where('nama', 'ilike', "%{$searchValue}%");
                                })
                                ->orWhereHas('unit', function ($query) use ($searchValue) {
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

    // Get Prodi By Fakultas
    public function get_prodi_by_fakultas(Request $request, string $fakultas): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $prodis = Prodi::where('fakultas_id', $fakultas)->pluck('nama', 'id');
                return response()->json($prodis);
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

    // Get Unit By Jabatan
    public function get_unit_by_jabatan(Request $request, string $jabatan): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $jabatan = Jabatan::with('unit')->findOrFail($jabatan);
                $units = $jabatan->unit->pluck('nama', 'id');

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
        // Sweet Alert
        $title = 'Hapus User!';
        $text = "Apakah Anda yakin ingin menghapus user ini? Semua yang berkaitan dengan user akan terhapus";
        confirmDelete($title, $text);

        $data = [
            'title' => 'User',
            'roles' => Role::where('id', '!=', 1)->get()
        ];
        return view('admin.user.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah User',
            'jabatans' => Jabatan::all(),
            'fakultas' => Fakultas::all(),
            'prodis' => Prodi::with(['jenjang'])->get(),
            'roles' => Role::all(),
            'units' => Unit::all(),
        ];
        return view('admin.user.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role) {
            foreach ($request->role as $item) {
                $role = Role::findById($item);

                $user->assignRole($role);
            }
        }

        $jabatan = Jabatan::findOrFail($request->jabatan);

        $exist = User::whereHas('jabatan', function ($query) use ($jabatan, $request) {
            $query->where('jabatan_id', $jabatan->id);
            if ($jabatan->type === 'prodi') {
                $query->where('prodi_id', $request->prodi);
            } elseif ($jabatan->type === 'fakultas') {
                $query->where('fakultas_id', $request->fakultas);
            } elseif ($jabatan->type === 'universitas') {
                $query->where('unit_id', $request->unit);
            }
        })->first();

        if ($exist && $jabatan->unik) {
            return redirect()->route('user')->with('error', $jabatan->nama . ' dengan ' . ($jabatan->type == 'prodi' ? 'prodi' : ($jabatan->type == 'fakultas' ? 'fakultas' : 'unit')) . ' yang sama sudah ada.');
        }

        $user->jabatan()->attach($jabatan->id, [
            'fakultas_id' => $jabatan->type === 'fakultas' ? $request->fakultas : null,
            'prodi_id' => $jabatan->type === 'prodi' ? $request->prodi : null,
            'unit_id' => $jabatan->type === 'universitas' ? $request->unit : null,
        ]);


        return redirect()->route('user')->with('success', 'User berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'jabatans' => Jabatan::all(),
            'fakultas' => Fakultas::all(),
            'prodis' => Prodi::with(['jenjang'])->get(),
            'roles' => Role::all(),
            'units' => Unit::all(),
        ];
        return view('admin.user.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        $roles = [];

        if ($request->role) {
            foreach ($request->role as $item) {
                $roles[] = Role::findById($item);
            }
        }

        $user->syncRoles($roles);

        $jabatan = Jabatan::findOrFail($request->jabatan);

        $exist = User::where('id', '!=', $user->id)->whereHas('jabatan', function ($query) use ($jabatan, $request) {
            $query->where('jabatan_id', $jabatan->id);
            if ($jabatan->type === 'prodi') {
                $query->where('prodi_id', $request->prodi);
            } elseif ($jabatan->type === 'fakultas') {
                $query->where('fakultas_id', $request->fakultas);
            } elseif ($jabatan->type === 'universitas') {
                $query->where('unit_id', $request->unit);
            }
        })->first();

        if ($exist && $jabatan->unik) {
            return redirect()->route('user')->with('error', $jabatan->nama . ' dengan ' . ($jabatan->type == 'prodi' ? 'prodi' : ($jabatan->type == 'fakultas' ? 'fakultas' : 'unit')) . ' yang sama sudah ada.');
        }

        $user->jabatan()->detach();

        $user->jabatan()->attach($jabatan->id, [
            'fakultas_id' => $jabatan->type === 'fakultas' ? $request->fakultas : null,
            'prodi_id' => $jabatan->type === 'prodi' ? $request->prodi : null,
            'unit_id' => $jabatan->type === 'universitas' ? $request->unit : null,
        ]);

        return redirect()->route('user')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('user')->with('success', 'User berhasil dihapus!');
    }

    public function create_user_role(): View
    {
        $users = User::select(['id', 'name', 'email'])
            ->with(['jabatan', 'roles', 'prodi.jenjang', 'fakultas', 'unit'])
            ->orderBy('created_at', 'DESC')
            ->get();

        $data = [
            'title' => 'Tambah Role Untuk User',
            'roles' => Role::all(),
            'users' => $users
        ];

        return view('admin.user.user.create_user_role', $data);
    }

    public function store_user_role(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,id',
            'user' => 'required|array'
        ]);

        $role = Role::findById($validated['role']);
        foreach ($validated['user'] as $userId) {
            $user = User::findOrFail($userId);
            $user->assignRole($role);
        }

        return redirect()->route('user')->with('success', 'Role berhasil ditambahkan!');
    }
}
