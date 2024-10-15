<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrumen extends Model
{
    use HasFactory;

    protected $table = 'instrumen';

    protected $fillable = ['pernyataan', 'indikator', 'status', 'standar_id', 'peraturan_id', 'kategori_id', 'level_id', 'jenis_pertanyaan_id', 'kode'];

    public function peraturan(): BelongsTo
    {
        return $this->belongsTo(Peraturan::class, 'peraturan_id');
    }

    public function standar(): BelongsTo
    {
        return $this->belongsTo(Standar::class, 'standar_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function jenis_pertanyaan(): BelongsTo
    {
        return $this->belongsTo(JenisPertanyaan::class, 'jenis_pertanyaan_id');
    }

    public function pasal(): BelongsToMany
    {
        return $this->belongsToMany(Pasal::class, 'instrumen_pasal_ayat', 'instrumen_id', 'pasal_id')
            ->withPivot('ayat_id')
            ->withTimestamps();
    }

    public function ayat(): BelongsToMany
    {
        return $this->belongsToMany(Ayat::class, 'instrumen_pasal_ayat')
            ->withTimestamps();
    }

    public function jenjang(): BelongsToMany
    {
        return $this->belongsToMany(Jenjang::class, 'instrumen_jenjang', 'instrumen_id', 'jenjang_id')
            ->withPivot('prodi_id', 'unit_id')
            ->withTimestamps();
    }

    public function prodi(): BelongsToMany
    {
        return $this->belongsToMany(Prodi::class, 'instrumen_jenjang', 'instrumen_id', 'prodi_id')
            ->withPivot('jenjang_id', 'unit_id')
            ->withTimestamps();
    }


    public function unit(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'instrumen_jenjang', 'instrumen_id', 'unit_id')
            ->withPivot('jenjang_id', 'prodi_id')
            ->withTimestamps();
    }

    public function kriteria(): BelongsToMany
    {
        return $this->belongsToMany(Kriteria::class, 'instrumen_kriteria', 'instrumen_id', 'kriteria_id')
            ->withPivot('isi')
            ->withTimestamps();
    }

    public function jabatan(): BelongsToMany
    {
        return $this->belongsToMany(Jabatan::class, 'instrumen_jabatan', 'instrumen_id', 'jabatan_id')
            ->withTimestamps();
    }

    public function form(): HasMany
    {
        return $this->hasMany(Form::class, 'instrumen_id');
    }
}
