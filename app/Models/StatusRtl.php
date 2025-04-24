<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusRtl extends Model
{
    use HasFactory;

    protected $table = 'status_rtl';

    protected $fillable = ['status', 'rtl_id', 'kriteria_id'];

    public function rtl(): BelongsTo
    {
        return $this->belongsTo(Rtl::class, 'rtl_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
