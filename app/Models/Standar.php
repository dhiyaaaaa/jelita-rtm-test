<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Standar extends Model
{
    use HasFactory;

    protected $table = 'standar';

    protected $fillable = ['nama', 'peraturan_id', 'kode'];

    public function peraturan(): BelongsTo
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function kategori(): HasMany
    {
        return $this->hasMany(Kategori::class);
    }

    public function instrumen(): HasMany
    {
        return $this->hasMany(Instrumen::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($standar) {
            if ($standar->isDirty('kode')) {
                foreach ($standar->instrumen as $instrumen) {
                    $kategori = $instrumen->kategori;
                    $originalKode = $instrumen->kode;
                    $suffix = explode('-', $originalKode)[1];
                    $instrumen->kode = $standar->kode . $kategori->kode . '-' . $suffix;
                    $instrumen->save();
                }
            }
        });
    }
}
