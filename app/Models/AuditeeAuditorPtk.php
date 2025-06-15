<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditeeAuditorPtk extends Model
{
    use HasFactory;

    protected $table = 'auditee_auditor_ptk';

    protected $fillable = ['auditee_id', 'fakultas_id', 'auditor_id', 'jadwal_audit_id'];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(Auditor::class, 'auditor_id');
    }

    public function auditee(): BelongsTo
    {
        return $this->belongsTo(Auditee::class, 'auditee_id');
    }

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id');
    }
}
