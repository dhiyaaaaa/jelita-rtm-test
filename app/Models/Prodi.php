<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prodi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'prodi';

    protected $fillable = ['nama', 'status', 'fakultas_id', 'jenjang_id'];

    protected $appends = ['type'];

    public function getTypeAttribute(): string
    {
        return 'prodi';
    }

    public function jenjang(): BelongsTo
    {
        return $this->belongsTo(Jenjang::class, 'jenjang_id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'jabatan_user', 'prodi_id', 'user_id')
            ->withPivot('jabatan_id')->withTimestamps();
    }

    public function auditee(): HasMany
    {
        return $this->hasMany(Auditee::class, 'prodi_id', 'id');
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'auditee_auditor', 'prodi_id', 'auditor_id')->withPivot('auditee_id', 'jadwal_audit_id')->withTimestamps();;
    }

    public function instrumen(): BelongsToMany
    {
        return $this->belongsToMany(Instrumen::class, 'instrumen_jenjang', 'prodi_id', 'instrumen_id')->withTimestamps();
    }

    public function berita_acara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'prodi_id', 'id');
    }

    public function ptk(): HasMany
    {
        return $this->hasMany(Ptk::class, 'prodi_id', 'id');
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class, 'prodi_id', 'id');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'prodi_id', 'id');
    }

    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class, 'prodi_id');
    }

    public function status_audit_auditor(): HasMany
    {
        return $this->hasMany(StatusAuditAuditor::class, 'prodi_id');
    }

    public function assessment_jawaban(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'prodi_id');
    }
    public function status_assessment(): HasMany
    {
        return $this->hasMany(StatusAssessment::class, 'prodi_id');
    }
}
