<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraAuditee extends Model
{
    use HasFactory;

    protected $table = 'berita_acara_auditee';

    protected $fillable = ['approve', 'berita_acara_id', 'auditee_id'];

    public function auditee(): BelongsTo
    {
        return $this->belongsTo(Auditee::class, 'auditee_id');
    }

    public function berita_acara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_acara_id');
    }
}
