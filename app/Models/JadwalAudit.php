<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalAudit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jadwal_audit';

    protected $fillable = ['jadwal', 'tgl_mulai', 'tgl_selesai', 'status', 'import', 'fitur_auditor'];

    protected $appends = ['expired'];

    public function getExpiredAttribute(): string
    {
        $currentDate = now()->format('Y-m-d');
        return $currentDate < $this->tgl_mulai || $currentDate > $this->tgl_selesai;
    }

    // Relasi Table Auditee
    public function auditee(): HasMany
    {
        return $this->hasMany(Auditee::class, 'jadwal_audit_id');
    }

    // Relasi Table Auditor
    public function auditor(): HasMany
    {
        return $this->hasMany(Auditor::class, 'jadwal_audit_id', 'id');
    }

    // Assessment Form
    public function assessment_form(): HasMany
    {
        return $this->hasMany(AssessmentForm::class, 'jadwal_audit_id');
    }

    // Relasi Table Auditee Auditor
    public function auditee_auditor(): HasMany
    {
        return $this->hasMany(AuditeeAuditor::class, 'jadwal_audit_id', 'id');
    }

     // Relasi Table Auditee Auditor
    public function auditee_auditor_ptk(): HasMany
    {
        return $this->hasMany(AuditeeAuditorPtk::class, 'jadwal_audit_id', 'id');
    }

    // Relasi Table Form
    public function form(): HasMany
    {
        return $this->hasMany(Form::class, 'jadwal_id');
    }

    // Relasi Table Jawaban Auditee
    public function jawaban_auditee(): HasMany
    {
        return $this->hasMany(JawabanAuditee::class);
    }

    // Relasi Table Status
    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class);
    }

    // Relasi Table Jawaban Auditor
    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class, 'jadwal_audit_id', 'id');
    }

    // Relasi Table Berita Acara
    public function berita_acara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'jadwal_audit_id', 'id');
    }

    // Relasi Table PTK
    public function ptk(): HasMany
    {
        return $this->hasMany(Ptk::class, 'jadwal_audit_id', 'id');
    }

    // Relasi Table Laporan
    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'jadwal_audit_id', 'id');
    }

    public function status_assessment(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'jadwal_audit_id');
    }

    public function status_audit_auditor(): HasMany
    {
        return $this->hasMany(StatusAuditAuditor::class, 'jadwal_audit_id');
    }

    public function rtm_jadwal(): HasMany
    {
        return $this->hasMany(RtmJadwal::class, 'jadwal_audit_id', 'id');
    }

    public function rtm_rtl(): HasMany
    {
        return $this->hasMany(RtmRtl::class, 'jadwal_audit_id', 'id');
    }

    public function rtm_rtl_univ(): HasMany
    {
        return $this->hasMany(RtmRtlUniv::class, 'jadwal_audit_id', 'id');
    }


    public function rtl(): HasMany
    {
        return $this->hasMany(Rtl::class, 'jadwal_audit_id', 'id');
    }

    public function monitoring(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'jadwal_audit_id', 'id');
    }
}
