<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;


class RtmJadwal extends Model
{
    use HasFactory;

    use HasFactory, HasUuids;

    protected $table = 'rtm_jadwal';

    protected $fillable = ['jadwal_audit_id', 'fakultas_id', 'unit_id', 'tanggal', 'peserta', 'jam_mulai', 'jam_selesai', 'tempat', 'agenda', 'pimpinan'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function rtm_lampiran(): HasMany
    {
        return $this->hasMany(RtmLampiran::class, 'rtm_jadwal_id');
    }

    public function rtm_rtl(): HasMany
    {
        return $this->hasMany(RtmRtl::class, 'rtm_jadwal_id');
    }

    public function rtm_tindak_lanjut(): HasMany
    {
        return $this->hasMany(RtmTindakLanjut::class, 'rtm_jadwal_id');
    }

    public function rtm_ptk(): HasMany
    {
        return $this->hasMany(RtmPtk::class, 'rtm_jadwal_id');
    }

    public function rtm_ptk_form(): HasMany
    {
        return $this->hasMany(RtmPtkForm::class, 'rtm_jadwal_id');
    }
    public static function boot()
    {
        parent::boot();

        static::deleting(function($rtmJadwal) {
           
            $rtmJadwal->rtm_lampiran()->delete();
            $rtmJadwal->rtm_rtl()->delete();
        });
    }
    public function auditee(): HasMany
    {
        return $this->hasMany(Auditee::class, 'rtm_jadwal_id');
    }
}
