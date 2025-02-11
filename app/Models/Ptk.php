<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ptk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ptk';

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
        return $this->belongsToMany(Auditee::class, 'ptk_auditee', 'ptk_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'ptk_auditor', 'ptk_id', 'auditor_id')->withPivot('approve')->withTimestamps();
    }

    public function ptk_form(): HasMany
    {
        return $this->hasMany(PtkForm::class);
    }

    public function status_ptk_auditee(): HasMany
    {
        return $this->hasMany(StatusPtkAuditee::class, 'ptk_id');
    }

    public function status_ptk_auditor(): HasMany
    {
        return $this->hasMany(StatusPtkAuditor::class, 'ptk_id');
    }

    public function ptk_form_deskripsi(): HasMany
    {
        return $this->hasMany(PtkFormDeskripsi::class);
    }
}
