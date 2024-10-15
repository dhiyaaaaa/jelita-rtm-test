<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BeritaAcara extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'berita_acara';

    protected $fillable = ['tgl', 'jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id'];

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
        return $this->belongsToMany(Auditee::class, 'berita_acara_auditee', 'berita_acara_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'berita_acara_auditor', 'berita_acara_id', 'auditor_id')->withPivot('approve')->withTimestamps();
    }
}
