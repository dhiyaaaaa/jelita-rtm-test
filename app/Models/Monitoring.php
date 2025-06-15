<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Monitoring extends Model
{
    use HasFactory;

    use HasFactory, HasUuids;

    protected $table = 'monitoring';

    protected $fillable = ['jadwal_audit_id', 'fakultas_id', 'unit_id', 'tgl'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id', 'id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function auditee(): BelongsToMany
    {
        return $this->belongsToMany(Auditee::class, 'monitoring_auditee', 'monitoring_id', 'auditee_id')->withPivot('approve')->withTimestamps();
    }

    public function auditor(): BelongsToMany
    {
        return $this->belongsToMany(Auditor::class, 'monitoring_auditor', 'monitoring_id', 'auditor_id')->withPivot('approve')->withTimestamps();
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'monitoring_approve', 'monitoring_id', 'user_id')->withPivot('approve')->withTimestamps();
    }

    public function monitoring_form(): HasMany
    {
    return $this->hasMany(MonitoringForm::class, 'monitoring_id', 'id');
    }

    public function monitoring_form_status(): HasMany
    {
    return $this->hasMany(MonitoringFormStatus::class, 'monitoring_id', 'id');
    }

    public function status_monitoring(): HasMany
    {
        return $this->hasMany(StatusMonitoring::class, 'monitoring_id');
    }

}
