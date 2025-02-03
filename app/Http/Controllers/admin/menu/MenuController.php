<?php

namespace App\Http\Controllers\admin\menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuStoreUpdateRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Submenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Menu!';
        $text = "Apakah Anda yakin ingin menghapus menu ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Menu',
            'menus' => Menu::orderBy('id', 'asc')->get()
        ];

        return view('admin.menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $data = [
            'title' => 'Tambah Menu',
            'roles' => Role::all()
        ];

        return view('admin.menu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuStoreUpdateRequest $request): RedirectResponse
    {
        $menu = Menu::create([
            'menu' => $request->menu,
            'status' => $request->status,
        ]);

        $menu->role()->attach($request->roles);

        return redirect()->route('menu')->with('success', 'Menu berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu): View
    {
        // Sweet Alert
        $title = 'Hapus Submenu!';
        $text = "Apakah Anda yakin ingin menghapus submenu ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Lihat Menu',
            'menu' => $menu,
            'roles' => Role::whereHas('menu', function ($query) use ($menu) {
                $query->where('id', $menu->id);
            })->get(),
            'submenus' => Submenu::where('menu_id', $menu->id)->get()
        ];
        return view('admin.menu.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu): View
    {
        $data = [
            'title' => 'Edit Menu',
            'menu' => $menu,
            'roles' => Role::all(),
            'role_checked' => $menu->role->pluck('id')->toArray()
        ];

        return view('admin.menu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MenuStoreUpdateRequest $request, Menu $menu): RedirectResponse
    {
        $menu->update([
            'menu' => $request->menu,
            'status' => $request->status,
        ]);

        $menu->role()->sync($request->roles);

        return redirect()->route('menu.show', $menu->id)->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu_not_delete = ['dashboard', 'audit', 'user', 'dokumen', 'assessment', 'akademik', 'menu', 'auditee', 'auditor', 'peer-assessment'];

        if (in_array($menu->route, $menu_not_delete)) {
            return back()->with('error', 'Menu ini tidak bisa dihapus!');
        }

        $menu->role()->detach();

        $menu->delete();

        return redirect()->route('menu')->with('success', 'Menu berhasil dihapus!');
    }
}
