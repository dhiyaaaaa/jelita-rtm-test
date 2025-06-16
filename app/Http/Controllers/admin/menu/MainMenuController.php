<?php

namespace App\Http\Controllers\admin\menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainMenuStoreUpdateRequest;
use App\Models\Menu;
use App\Models\Submenu;
use App\Models\MainMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MainMenuController extends Controller
{
    public function index(): View
    {
        // Sweet Alert
        $title = 'Hapus Menu!';
        $text = "Apakah Anda yakin ingin menghapus menu ini?";
        confirmDelete($title, $text);

        $data = [
            'title' => 'Main Menu',
            'mainMenus' => MainMenu::with('menu')->orderBy('id', 'asc')->get()
        ];

        return view('admin.menu.main_menu.index', $data);
    }

    public function create(): View
    {
        $data = [
            'title' => 'Tambah Menu',
            'menus' => Menu::all()
        ];

        return view('admin.menu.main_menu.create', $data);
    }

    public function store(MainMenuStoreUpdateRequest $request): RedirectResponse
    {
        $mainMenu = MainMenu::create([
            'mainmenu' => $request->mainmenu,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
            
        ]);

        $mainMenu->menu()->attach($request->menus);

        return redirect()->route('main_menu')->with('success', 'Main Menu berhasil ditambah!');
    }

    public function edit(MainMenu $mainMenu): View
    {
        $data = [
            'title' => 'Edit Main Menu',
            'mainMenu' => $mainMenu,
            'menus' => Menu::all(),
            'menu_checked' => $mainMenu->menu->pluck('id')->toArray()
        ];

        return view('admin.menu.main_menu.edit', $data);
    }
    public function update(MainMenuStoreUpdateRequest $request, MainMenu $mainMenu): RedirectResponse
    {
        $mainMenu->update([
            'mainmenu' => $request->mainmenu,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        $mainMenu->menu()->sync($request->menus);

        return redirect()->route('main_menu')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(MainMenu $mainMenu): RedirectResponse
    {
        $mainMenu->menu()->detach(); 
        $mainMenu->delete();         

        return redirect()->route('main_menu')->with('success', 'Main menu berhasil dihapus!');
    }

}

