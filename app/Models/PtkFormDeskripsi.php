<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtkFormDeskripsi extends Model
{
    use HasFactory;

    protected $table = 'ptk_form_deskripsi';

    protected $fillable = ['ptk_id', 'deskripsi', 'form_id', 'auditor_id'];

    public function ptk(): BelongsTo
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
