<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditor extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'auditor';

    protected $fillable = ['user_id', 'jadwal_audit_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auditee(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'auditee_auditor', 'auditor_id', 'auditee_id')
            ->withPivot('prodi_id', 'fakultas_id', 'unit_id', 'jadwal_audit_id')
            ->withTimestamps();;
    }
    
    public function auditee_auditor(): HasMany
    {
        return $this->hasMany(AuditeeAuditor::class, 'auditor_id');
    }

    public function auditeePtk(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'auditee_auditor_ptk', 'auditor_id', 'auditee_id')
            ->withPivot('fakultas_id', 'unit_id', 'jadwal_audit_id')
            ->withTimestamps();;
    }

    public function auditee_auditor_ptk(): HasMany
    {
        return $this->hasMany(AuditeeAuditorPtk::class, 'auditor_id');
    }

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class);
    }

    public function status_audit_auditor(): HasMany
    {
        return $this->hasMany(StatusAuditAuditor::class);
    }

    public function berita_acara(): BelongsToMany
    {
        return $this->belongsToMany(BeritaAcara::class, 'berita_acara_auditor', 'auditor_id', 'berita_acara_id')->withPivot('approve')->withTimestamps();
    }

    public function ptk(): BelongsToMany
    {
        return $this->belongsToMany(Ptk::class, 'ptk_auditee', 'auditor_id', 'ptk_id')->withPivot('approve')->withTimestamps();
    }

    public function laporan(): BelongsToMany
    {
        return $this->belongsToMany(Laporan::class, 'laporan_auditor', 'auditor_id', 'laporan_id')->withPivot('approve')->withTimestamps();
    }

    public function monitoring(): BelongsToMany
    {
        return $this->belongsToMany(Monitoring::class, 'monitoring_auditor', 'auditor_id', 'laporan_id')->withPivot('approve')->withTimestamps();
    }

    public function assessment_jawaban_penilai(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'auditor_penilai_id');
    }

    public function assessment_jawaban_dinilai(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'auditor_dinilai_id');
    }

    public function status_assessment_dinilai(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'auditor_dinilai_id');
    }

    public function status_assessment_penilai(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'auditor_penilai_id');
    }

    public function status_ptk_auditor(): HasMany
    {
        return $this->hasMany(StatusPtkAuditor::class, 'auditor_id');
    }
}
