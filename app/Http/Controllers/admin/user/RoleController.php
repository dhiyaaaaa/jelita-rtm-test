<?php

namespace App\Http\Controllers\admin\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Role!';
        $text = "Apakah Anda yakin ingin menghapus role ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Role',
            'roles' => Role::all()
        ];
        return view('admin.user.role.index', $data);
    }

    /**
     * Display the specified resource.
     */
    public function get_users_by_role(Request $request, Role $role): JsonResponse
    {
        if ($request->ajax()) {
            try {
                $users = User::with(['jabatan', 'roles'])->whereHas('roles', function ($query) use ($role) {
                    $query->where('id', $role->id);
                })->select(['id', 'name', 'email']);

                return DataTables::of($users)
                    ->addColumn('jabatan', function ($row) {
                        return $row->jabatan->isNotEmpty() ? $row->jabatan->pluck('nama')->implode(', ') : '-';
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
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Error.'], 500);
            }
        }

        return response()->json(['error' => 'Invalid Request.'], 400);
    }

    public function show(Role $role): View
    {
        // Sweet Alert
        $title = 'Hapus User!';
        $text = "Apakah Anda yakin ingin menghapus user ini? Semua yang berkaitan dengan user akan terhapus";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Role ' . $role->nama,
            'role' => $role,
        ];

        return view('admin.user.role.show', $data);
    }
}
