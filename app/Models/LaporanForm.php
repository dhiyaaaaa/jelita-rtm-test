<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanForm extends Model
{
    use HasFactory;

    protected $table = 'laporan_form';

    protected $fillable = ['laporan_id', 'ruang_peningkatan', 'kelebihan', 'form_id', 'auditor_id'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
