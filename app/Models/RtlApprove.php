<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtlApprove extends Model
{
    use HasFactory;

    protected $table = 'rtl_approve';

    protected $fillable = ['rtl_id', 'user_id', 'approve'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rtl(): BelongsTo
    {
        return $this->belongsTo(Rtl::class, 'rtl_id');
    }

}
