<?php 

namespace App\Http\Controllers\admin\menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmenuStoreUpdateRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Submenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmenuController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Menu $menu): View
    {
        $data = [
            'title' => 'Tambah Submenu',
            'roles' => Role::all(),
            'menu' => $menu
        ];
        return view('admin.menu.submenu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubmenuStoreUpdateRequest $request, Menu $menu): RedirectResponse
    {
        $submenu = Submenu::create([
            'submenu' => $request->submenu,
            'status' => $request->status,
            'menu_id' => $menu->id,
        ]);

        $submenu->role()->attach($request->roles);

        return redirect()->route('menu.show', $menu->id)->with('success', 'Submenu berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu, Submenu $submenu): View
    {
        $data = [
            'title' => 'Edit Submenu',
            'menu' => $menu,
            'submenu' => $submenu->load('role'),
            'roles' => Role::all(),
            'role_checked' => $submenu->role->pluck('id')->toArray()
        ];

        return view('admin.menu.submenu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubmenuStoreUpdateRequest $request, Menu $menu, Submenu $submenu): RedirectResponse
    {
        $submenu->update([
            'submenu' => $request->submenu,
            'status' => $request->status,
        ]);

        $submenu->role()->detach();

        $submenu->role()->sync($request->roles);

        return redirect()->route('menu.show', $menu->id)->with('success', 'Submenu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu, Submenu $submenu): RedirectResponse
    {
        $submenu->role()->detach();

        $submenu->delete();

        return redirect()->route('menu.show', $menu->id)->with('success', 'Submenu berhasil dihapus!');
    }
}