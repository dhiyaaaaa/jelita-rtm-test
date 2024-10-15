<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanAuditee extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jawaban_auditee';

    protected $fillable = ['jawaban', 'form_id', 'auditee_id', 'jadwal_audit_id', 'prodi_id', 'fakultas_id', 'unit_id'];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
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
