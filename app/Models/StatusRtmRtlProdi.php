<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StatusRtmRtlProdi extends Model
{
    use HasFactory;

    protected $table = 'status_rtm_rtl_prodi';

    protected $fillable = ['status', 'rtm_rtl_id'];

    public function rtl(): BelongsTo
    {
        return $this->belongsTo(Rtl::class, 'rtm_rtl_id');
    }
}
