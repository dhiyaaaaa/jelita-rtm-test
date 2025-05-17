<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanRtm extends Model
{
    use HasFactory;

    protected $table = 'catatan_rtm';

    protected $fillable = ['rtm_jadwal_id', 'judul', 'catatan'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }

}
