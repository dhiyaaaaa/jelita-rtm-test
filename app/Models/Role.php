<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public function menu(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id')->withTimestamps();
    }
    public function submenu(): BelongsToMany
    {
        return $this->belongsToMany(Submenu::class, 'role_submenu', 'role_id', 'submenu_id')->withTimestamps();
    }
}
