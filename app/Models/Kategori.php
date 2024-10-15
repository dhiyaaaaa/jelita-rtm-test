<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = ['nama', 'standar_id', 'kode'];

    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class, 'standar_id');
    }

    public function instrumen(): HasMany
    {
        return $this->hasMany(Instrumen::class);
    }
}
