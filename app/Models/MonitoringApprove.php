<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringApprove extends Model
{
    use HasFactory;

    protected $table = 'monitoring_approve';

    protected $fillable = ['monitoring_id', 'user_id', 'approve'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class, 'monitoring_id');
    }
}
