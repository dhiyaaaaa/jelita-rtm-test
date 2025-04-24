<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MainMenu;

class MainMenuController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->roles->pluck('id');

        $mainMenus = MainMenu::with(['menu' => function ($query) use ($roleId) {
            $query->whereHas('role', function ($q) use ($roleId) {
                $q->where('role_id', $roleId);
            });
        }])->get()->filter(function ($mainMenu) {
            return $mainMenu->menu->isNotEmpty();
        });


        $data = [
            'mainMenus' => $mainMenus,
            'title' => 'Jelita'
        ];
        

        return view('main_menu.index', $data);
    }
}
