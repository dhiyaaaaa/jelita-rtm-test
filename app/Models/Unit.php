<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'unit';

    protected $fillable = ['nama'];

    protected $appends = ['type'];

    public function getTypeAttribute(): string
    {
        return 'universitas';
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jabatan_user', 'unit_id', 'user_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }

    public function jabatan(): BelongsToMany
    {
        return $this->belongsToMany(Jabatan::class, 'jabatan_unit', 'unit_id', 'jabatan_id')->withTimestamps();
    }

    public function auditee(): HasMany
    {
        return $this->hasMany(Auditee::class, 'unit_id', 'id');
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'auditee_auditor', 'unit_id', 'auditor_id')->withPivot('auditee_id', 'jadwal_audit_id')->withTimestamps();;
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_jenjang', 'unit_id', 'instrumen_id')
            ->withPivot('jenjang_id', 'prodi_id')
            ->withTimestamps();
    }

    public function berita_acara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'unit_id', 'id');
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class, 'unit_id', 'id');
    }

    public function ptk(): HasMany
    {
        return $this->hasMany(Ptk::class, 'unit_id', 'id');
    }

    // Relasi Table Laporan
    public function laporan():HasMany
    {
        return $this->hasMany(Laporan::class, 'unit_id', 'id');
    }

    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class, 'unit_id');
    }

    public function status_audit_auditor(): HasMany
    {
        return $this->hasMany(StatusAuditAuditor::class, 'unit_id');
    }

    public function assessment_jawaban(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'unit_id');
    }

    public function status_assessment(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'unit_id');
    }
    public function rtm_jadwal(): HasMany
    {
        return $this->hasMany(RtmJadwal::class, 'unit_id', 'id');
    }

    public function rtm_rtl(): HasMany
    {
        return $this->hasMany(RtmRtl::class, 'unit_id', 'id');
    }
    
}
