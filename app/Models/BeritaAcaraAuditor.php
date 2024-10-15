<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraAuditor extends Model
{
    use HasFactory;

    protected $table = 'berita_acara_auditor';

    protected $fillable = ['approve', 'berita_acara_id', 'auditor_id'];

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(Auditor::class, 'auditor_id');
    }
}
