<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringAuditee extends Model
{
    use HasFactory;

    protected $table = 'monitoring_auditee';

    protected $fillable = ['monitoring_id', 'auditee_id', 'approve'];

    public function auditee(): BelongsTo
    {
        return $this->belongsTo(Auditee::class, 'auditee_id');
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }
}
