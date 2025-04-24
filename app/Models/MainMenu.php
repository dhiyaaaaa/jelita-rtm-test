<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MainMenu extends Model
{
    use HasFactory;

    protected $table = 'main_menu';

    protected  $fillable = ['mainmenu', 'deskripsi', 'status'];

    public function menu(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'main_menu_menu', 'main_menu_id', 'menu_id')->withTimestamps();
    }
}
