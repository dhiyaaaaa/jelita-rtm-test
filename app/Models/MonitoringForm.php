<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringForm extends Model
{
    use HasFactory;

    protected $table = 'monitoring_form';

    protected $fillable = ['monitoring_id', 'catatan', 'form_id', 'auditor_id', 'kriteria_id'];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
