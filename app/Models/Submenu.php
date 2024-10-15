<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'submenu';

    protected  $fillable = ['submenu', 'status', 'route', 'menu_id'];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_submenu', 'submenu_id', 'role_id')->withTimestamps();
    }

    // Nama route diambil dari nama submenu
    protected static function booted(): void
    {
        static::creating(function ($submenu) {
            $submenu->route = strtolower(preg_replace('/\s+/', '-', $submenu->submenu));
        });
    }
}
