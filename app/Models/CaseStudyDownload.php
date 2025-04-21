<?php

namespace App\Models;

use App\Models\CaseStudy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudyDownload extends Model
{
    use HasFactory;
    protected $fillable = [
        'case_study_id',
        'name',
        'email',
        'phone',
        'company',
        'ip_address',
    ];
    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }
}