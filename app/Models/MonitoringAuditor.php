<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringAuditor extends Model
{
    use HasFactory;

    protected $table = 'monitoring_auditor';

    protected $fillable = ['monitoring_id', 'auditor_id', 'approve'];

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(Auditor::class, 'auditor_id');
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }
}
