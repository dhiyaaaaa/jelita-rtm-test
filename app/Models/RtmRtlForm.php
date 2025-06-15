<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtmRtlForm extends Model
{
    use HasFactory;

    protected $table = 'rtm_rtl_form';

    protected $fillable = 
    [
        'rtm_rtl_univ_id', 
        'form_id', 
        'user_id',
        'rekomendasi',
        'koreksi',
    ];

    public function rtm_rtl_univ(): BelongsTo
    {
        return $this->belongsTo(RtmRtlUniv::class, 'rtm_rtl_univ_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
