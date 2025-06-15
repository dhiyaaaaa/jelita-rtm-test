<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtmRtlUniv extends Model
{
    use HasFactory;

    use HasFactory, HasUuids;

    protected $table = 'rtm_rtl_univ';

    protected $fillable = ['rtm_jadwal_id', 'jadwal_audit_id', 'fakultas_id', 'unit_id', 'tgl'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }

    public function rtm_rtl_form(): HasMany
    {
    return $this->hasMany(RtmRtlForm::class, 'rtm_rtl_univ_id', 'id');
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

    public function status_rtm_rtl_univ(): HasMany
    {
        return $this->hasMany(StatusRtmRtlUniv::class, 'rtm_rtl_univ_id');
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rtm_rtl_univ_approve', 'rtm_rtl_univ_id', 'user_id')->withPivot('approve')->withTimestamps();
    }
}
