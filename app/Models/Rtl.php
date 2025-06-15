<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rtl extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rtl';

    protected $fillable = ['jadwal_audit_id', 'fakultas_id', 'unit_id', 'tgl'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function auditee(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'rtl_auditee', 'rtl_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rtl_approve', 'rtl_id', 'user_id')->withPivot('approve')->withTimestamps();
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function rtl_form(): HasMany
    {
        return $this->hasMany(RtlForm::class, 'rtl_id', 'id');
    }

    public function status_rtl(): HasMany
    {
        return $this->hasMany(StatusRtl::class, 'rtl_id');
    }
}
