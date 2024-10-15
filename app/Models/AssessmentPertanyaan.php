<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'assessment_pertanyaan';

    protected  $fillable = ['pertanyaan'];

    public function assessment_form(): HasMany
    {
        return $this->hasMany(AssessmentForm::class, 'assessment_pertanyaan_id');
    }
}
