<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentForm extends Model
{
    use HasFactory;

    protected $table = 'assessment_form';

    protected $fillable = ['assessment_pertanyaan_id', 'jadwal_audit_id'];

    public function jadwal_audit(): BelongsTo
    {
        return $this->belongsTo(JadwalAudit::class, 'jadwal_audit_id');
    }

    public function assessment_pertanyaan(): BelongsTo
    {
        return $this->belongsTo(AssessmentPertanyaan::class, 'assessment_pertanyaan_id');
    }

    public function assessment_jawaban(): HasMany
    {
        return $this->hasMany(AssessmentJawaban::class, 'assessment_form_id');
    }
}
