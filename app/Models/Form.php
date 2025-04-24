<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'form';

    protected $fillable = ['jadwal_id', 'instrumen_id'];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_id');
    }

    public function instrumen(): BelongsTo
    {
        return $this->belongsTo(Instrumen::class, 'instrumen_id');
    }

    public function status_audit_auditee(): HasMany
    {
        return $this->hasMany(StatusAuditAuditee::class);
    }

    public function jawaban_auditor(): HasMany
    {
        return $this->hasMany(JawabanAuditor::class);
    }
    
    public function jawaban_auditee(): HasMany
    {
        return $this->hasMany(JawabanAuditee::class);
    }

    public function link(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function ptk(): HasMany
    {
        return $this->hasMany(Ptk::class);
    }

    public function ptk_form_deskripsi(): HasMany
    {
        return $this->hasMany(PtkFormDeskripsi::class, 'form_id');
    }

    public function ptk_form(): HasMany
    {
        return $this->hasMany(PtkForm::class, 'form_id');
    }

    public function ptk_form_rencana(): HasMany
    {
        return $this->hasMany(PtkFormRencana::class, 'form_id');
    }

    public function laporan_form(): HasMany
    {
        return $this->hasMany(LaporanForm::class, 'form_id');
    }

    public function rtm_tindak_lanjut(): HasMany
    {
        return $this->hasMany(RtmTindakLanjut::class, 'form_id');
    }

    public function rtl_form(): HasMany
    {
        return $this->hasMany(RtlForm::class, 'form_id');
    }

    public function monitoring_form(): HasMany
    {
        return $this->hasMany(MonitoringForm::class, 'form_id');
    }

    public function monitoring_status_form(): HasMany
    {
        return $this->hasMany(MonitoringFormStatus::class, 'form_id');
    }

}
