<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected  $fillable = ['menu', 'status', 'route'];

    public function submenu(): HasMany
    {
        return $this->hasMany(Submenu::class);
    }

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id')->withTimestamps();
    }

    protected static function booted(): void
    {
        static::creating(function (self $menu): void {
            $menu->route = strtolower(preg_replace('/\s+/', '-', $menu->menu));
        });
    }

    public function main_menu(): BelongsToMany
    {
        return $this->belongsToMany(MainMenu::class, 'main_menu_menu', 'menu_id', 'main_menu_id')->withTimestamps();
    }


}
