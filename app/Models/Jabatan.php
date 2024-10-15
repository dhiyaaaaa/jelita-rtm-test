<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    protected  $fillable = ['nama', 'type', 'unik'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jabatan_user', 'jabatan_id', 'user_id')
            ->withPivot('prodi_id', 'fakultas_id', 'unit_id')->withTimestamps();
    }

    public function unit(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'jabatan_unit', 'jabatan_id', 'unit_id')->withTimestamps();
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_jabatan', 'jabatan_id', 'instrumen_id')
            ->withTimestamps();
    }

    public function auditee(): HasOne
    {
        return $this->hasOne(Auditee::class, 'jabatan_id', 'id');
    }

    public function setting(): HasMany
    {
        return $this->hasMany(Setting::class, 'jabatan_id');
    }

    // Slugs
    protected static function booted(): void
    {
        static::creating(function ($jabatan) {
            $baseSlug = Str::slug(Str::lower($jabatan->nama));
            $slug = $baseSlug;

            $count = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $jabatan->slug = $slug;
        });
    }
}
