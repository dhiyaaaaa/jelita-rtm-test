<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtmRtl extends Model
{
    use HasFactory;

    use HasFactory, HasUuids;

    protected $table = 'rtm_rtl';

    protected $fillable = ['rtm_jadwal_id', 'jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id', 'tgl'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }
    
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

    public function rtm_tindak_lanjut(): HasMany
    {
    return $this->hasMany(RtmTindakLanjut::class, 'rtm_rtl_id', 'id');
    }

    public function status_rtm_rtl(): HasMany
    {
        return $this->hasMany(StatusRtmRtl::class, 'rtm_rtl_id');
    }

    public function status_rtm_rtl_prodi(): HasMany
    {
        return $this->hasMany(StatusRtmRtlProdi::class, 'rtm_rtl_id');
    }
    
    public function auditee(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'rtm_rtl_approve', 'rtm_rtl_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }
}
