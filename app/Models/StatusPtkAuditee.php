<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusPtkAuditee extends Model
{
    use HasFactory;

    protected $table = 'status_ptk_auditee';

    protected $fillable = ['status', 'ptk_id'];

    public function ptk(): BelongsTo
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
