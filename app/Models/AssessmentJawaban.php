<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentJawaban extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assessment_jawaban';

    protected $fillable = ['jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id', 'assessment_form_id', 'auditor_penilai_id', 'auditor_dinilai_id', 'jawaban'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id');
    }
    
    public function assessment_form(): BelongsTo
    {
        return $this->belongsTo(AssessmentForm::class, 'assessment_form_id');
    }

    public function auditor_penilai(): BelongsTo
    {
        return $this->belongsTo(Auditor::class, 'auditor_penilai_id');
    }

    public function auditor_dinilai(): BelongsTo
    {
        return $this->belongsTo(Auditor::class, 'auditor_dinilai_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    
    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
