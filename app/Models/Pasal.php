<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasal extends Model
{
    use HasFactory;

    protected $table = 'pasal';

    protected $fillable = ['pasal', 'isi', 'peraturan_id'];

    public function ayat(): HasMany
    {
        return $this->hasMany(Ayat::class);
    }
    
    public function peraturan(): BelongsTo
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_pasal_ayat',  'pasal_id', 'instrumen_id')
            ->withPivot('ayat_id')
            ->withTimestamps();
    }
}
