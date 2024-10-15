<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ayat extends Model
{
    use HasFactory;

    protected $table = 'ayat';

    protected $fillable = ['ayat', 'isi', 'peraturan_id', 'pasal_id'];

    public function pasal(): BelongsTo
    {
        return $this->belongsTo(Pasal::class, 'pasal_id');
    }

    public function peraturan(): BelongsTo
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_pasal_ayat', 'ayat_id', 'instrumen_id')
        ->withPivot('pasal_id')
        ->withTimestamps();
    }
}
