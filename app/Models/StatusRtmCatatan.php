<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusRtmCatatan extends Model
{
    protected $table = 'status_rtm_catatan';

    protected $fillable = ['status', 'rtm_jadwal_id'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }
}
