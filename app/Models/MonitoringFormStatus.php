<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringFormStatus extends Model
{
    use HasFactory;

    protected $table = 'monitoring_form_status';

    protected $fillable = ['monitoring_id', 'status', 'form_id', 'auditor_id', 'kriteria_id'];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
