<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtmTindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'rtm_tindak_lanjut';

    protected $fillable = 
    [
        'rtm_rtl_id', 
        'form_id', 
        'prodi_id', 
        'fakultas_id', 
        'unit_id', 
        'kriteria_id',
        'tindakan',
        'catatan'
    ];

    public function rtm_rtl(): BelongsTo
    {
        return $this->belongsTo(RtmRtl::class, 'rtm_rtl_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
    
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id', 'id');
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
    
}
