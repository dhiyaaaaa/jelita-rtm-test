<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtlAuditee extends Model
{
    use HasFactory;

    protected $table = 'rtl_auditee';

    protected $fillable = ['rtl_id', 'auditee_id', 'approve'];

    public function auditee(): BelongsTo
    {
        return $this->belongsTo(Auditee::class, 'auditee_id');
    }

    public function rtl(): BelongsTo
    {
        return $this->belongsTo(Rtl::class, 'rtl_id');
    }
}
