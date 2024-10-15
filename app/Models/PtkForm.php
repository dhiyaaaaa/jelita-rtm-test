<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtkForm extends Model
{
    use HasFactory;

    protected $table = 'ptk_form';

    protected $fillable = ['ptk_id', 'kategori_temuan', 'analisis', 'akibat', 'target', 'tinjauan', 'form_id', 'auditee_id', 'auditor_id', 'pic'];

    public function ptk(): BelongsTo
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
