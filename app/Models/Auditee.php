<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auditee extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'auditee';

    protected $fillable = ['user_id', 'prodi_id', 'fakultas_id', 'jabatan_id', 'unit_id', 'jadwal_audit_id'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'auditee_auditor', 'auditee_id', 'auditor_id')
            ->withPivot('prodi_id', 'fakultas_id', 'unit_id', 'jadwal_audit_id')
            ->withTimestamps();
    }

    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class);
    }

    public function jawaban_auditee(): HasMany
    {
        return $this->hasMany(JawabanAuditee::class);
    }

    public function berita_acara(): BelongsToMany
    {
        return $this->belongsToMany(BeritaAcara::class, 'berita_acara_auditee', 'auditee_id', 'berita_acara_id')->withPivot('approve')->withTimestamps();
    }

    public function ptk(): BelongsToMany
    {
        return $this->belongsToMany(Ptk::class, 'ptk_auditee', 'auditee_id', 'ptk_id')->withPivot('approve')->withTimestamps();
    }

    public function laporan(): BelongsToMany
    {
        return $this->belongsToMany(Laporan::class, 'laporan_auditee', 'auditee_id', 'laporan_id')->withPivot('approve')->withTimestamps();
    }

    public function status_ptk_auditee(): HasMany
    {
        return $this->hasMany(StatusPtkAuditee::class, 'auditee_id');
    }
    
    public function rtl(): BelongsToMany
    {
        return $this->belongsToMany(Rtl::class, 'rtl_auditee', 'auditee_id', 'rtl_id')->withPivot('approve')->withTimestamps();
    }

    public function rtm_rtl(): BelongsToMany
    {
        return $this->belongsToMany(RtmRtl::class, 'rtm_rtl_approve', 'auditee_id', 'rtm_rtl_id')->withPivot('approve')->withTimestamps();
    }
}
