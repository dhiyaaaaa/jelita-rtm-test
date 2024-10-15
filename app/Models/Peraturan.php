<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'peraturan';

    protected $fillable = ['nama', 'tahun', 'status'];

    public function pasal(): HasMany
    {
        return $this->hasMany(Pasal::class);
    }

    public function ayat(): HasMany
    {
        return $this->hasMany(Pasal::class);
    }

    public function standar(): HasMany
    {
        return $this->hasMany(Pasal::class);
    }

    public function instrumen(): HasMany
    {
        return $this->hasMany(Instrumen::class);
    }
}
