<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtmRtlApprove extends Model
{
    use HasFactory;

    protected $table = 'rtm_rtl_approve';

    protected $fillable = ['rtm_rtl_id', 'user_id', 'approve'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rtm_rtl(): BelongsTo
    {
        return $this->belongsTo(RtmRtl::class, 'rtm_rtl_id');
    }
}
