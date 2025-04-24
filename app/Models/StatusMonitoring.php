<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusMonitoring extends Model
{
    use HasFactory;

    protected $table = 'status_monitoring';

    protected $fillable = ['status', 'monitoring_id', 'kriteria_id'];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
