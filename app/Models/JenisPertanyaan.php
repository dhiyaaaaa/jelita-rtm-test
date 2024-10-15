<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JenisPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pertanyaan';

    protected $fillable = ['nama'];

    public function instrumen(): HasMany
    {
        return $this->hasMany(Instrumen::class);
    }

    // Slugs
    protected static function booted(): void
    {
        static::creating(function ($jenisPertanyaan) {
            $firstWord = Str::of($jenisPertanyaan->nama)->explode(' ')->first();
            $baseSlug = Str::slug(Str::lower($firstWord));
            $slug = $baseSlug;

            $count = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $jenisPertanyaan->slug = $slug;
        });
    }
}
