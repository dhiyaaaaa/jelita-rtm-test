<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'laporan';

    protected $fillable = ['jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id', 'tgl'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function auditee(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'laporan_auditee', 'laporan_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'laporan_auditor', 'laporan_id', 'auditor_id')->withPivot('approve')->withTimestamps();
    }

    public function status_laporan(): HasMany
    {
        return $this->hasMany(StatusLaporan::class, 'laporan_id');
    }
}
