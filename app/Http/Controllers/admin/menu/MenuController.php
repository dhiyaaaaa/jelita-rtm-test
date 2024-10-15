<?php

namespace App\Http\Controllers\admin\menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuStoreUpdateRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Submenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'menus' => Menu::get()->all()
        ];

        return view('admin.menu.index', $data);
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
}
