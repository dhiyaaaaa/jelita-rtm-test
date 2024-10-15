<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Jenjang extends Model
{
    use HasFactory;

    protected $table = 'jenjang';

    protected  $fillable = ['nama'];

    public function prodi() : HasMany
    {
        return $this->hasMany(Prodi::class);
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_jenjang', 'jenjang_id', 'instrumen_id');
    }
}
