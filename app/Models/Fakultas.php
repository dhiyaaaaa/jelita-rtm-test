<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'fakultas';

    protected $fillable = ['nama'];

    protected $appends = ['type'];

    public function getTypeAttribute(): string
    {
        return 'fakultas';
    }

    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jabatan_user', 'fakultas_id', 'user_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }

    public function auditee(): HasMany
    {
        return $this->hasMany(Auditee::class, 'fakultas_id', 'id');
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'auditee_auditor', 'fakultas_id', 'auditor_id')->withPivot('auditee_id', 'jadwal_audit_id')->withTimestamps();;
    }

    public function berita_acara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'fakultas_id', 'id');
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class, 'fakultas_id', 'id');
    }

    public function ptk(): HasMany
    {
        return $this->hasMany(Ptk::class, 'fakultas_id', 'id');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'fakultas_id', 'id');
    }

    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class, 'fakultas_id');
    }

    public function status_audit_auditor(): HasMany
    {
        return $this->hasMany(StatusAuditAuditor::class, 'fakultas_id');
    }

    public function assessment_jawaban(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'fakultas_id');
    }

    public function status_assessment(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'fakultas_id');
    }
    
    public function rtm_jadwal(): HasMany
    {
        return $this->hasMany(RtmJadwal::class, 'fakultas_id');
    }

    public function rtm_rtl(): HasMany
    {
        return $this->hasMany(RtmRtl::class, 'fakultas_id');
    }

    public function rtl(): HasMany
    {
        return $this->hasMany(Rtl::class, 'fakultas_id', 'id');
    }

    public function monitoring(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'fakultas_id', 'id');
    }
}
