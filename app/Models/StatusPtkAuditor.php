<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPtkAuditor extends Model
{
    use HasFactory;

    protected $table = 'status_ptk_auditor';

    protected $fillable = ['status', 'ptk_id'];

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}
