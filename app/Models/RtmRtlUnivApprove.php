<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtmRtlUnivApprove extends Model
{
    use HasFactory;

    protected $table = 'rtm_rtl_univ_approve';

    protected $fillable = ['rtm_rtl_univ_id', 'user_id', 'approve'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rtm_rtl_univ(): BelongsTo
    {
        return $this->belongsTo(RtmRtlUniv::class, 'rtm_rtl_univ_id');
    }
}
