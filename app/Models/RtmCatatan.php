<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtmCatatan extends Model
{
    use HasFactory;

    protected $table = 'rtm_catatan';

    protected $fillable = ['rtm_jadwal_id', 'user_id', 'judul', 'isi'];

    public function rtm_jadwal(): BelongsTo
    {
        return $this->belongsTo(RtmJadwal::class, 'rtm_jadwal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
