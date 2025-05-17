<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StatusRtmRtlUniv extends Model
{
    use HasFactory;

    protected $table = 'status_rtm_rtl_univ';

    protected $fillable = ['status', 'rtm_rtl_id'];

    public function rtm_rtl(): BelongsTo
    {
        return $this->belongsTo(RtmRtl::class, 'rtm_rtl_id');
    }
}
