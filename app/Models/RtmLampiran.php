<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtmLampiran extends Model
{
    use HasFactory;

    use HasFactory, HasUuids;

    protected $table = 'rtm_lampiran';

    protected $fillable = ['rtm_jadwal_id', 'presensi', 'undangan', 'dokumentasi'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }
}
