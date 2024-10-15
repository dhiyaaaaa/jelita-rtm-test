<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = ['nama'];

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_kriteria', 'kriteria_id', 'instrumen_id')
            ->withPivot('isi')
            ->withTimestamps();
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class);
    }

    // Slugs
    protected static function booted(): void
    {
        static::creating(function ($kriteria) {
            $baseSlug = Str::slug(Str::lower($kriteria->nama));
            $slug = $baseSlug;

            $count = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $kriteria->slug = $slug;
        });
    }
}
