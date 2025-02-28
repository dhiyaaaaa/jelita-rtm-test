<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtmTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'rtm_tindak_lanjut';

    protected $fillable = 
    [
        'rtm_rtl_id', 
        'form_id', 
        'auditee_id',
        'kriteria_id',
        'tindakan',
        'pic',
        'waktu',
    ];

    public function rtm_rtl(): BelongsTo
    {
        return $this->belongsTo(RtmRtl::class, 'rtm_rtl_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
    
    public function auditee(): BelongsTo
    {
        return $this->belongsTo(Auditee::class, 'auditee_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
    
}
