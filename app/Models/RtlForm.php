<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtlForm extends Model
{
    use HasFactory;

    protected $table = 'rtl_form';

    protected $fillable = ['rtl_id', 'form_id', 'kriteria_id', 'auditee_id', 'tindakan', 'bukti'];

    public function rtl(): BelongsTo
    {
        return $this->belongsTo(Rtl::class, 'rtl_id', 'id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }
}

