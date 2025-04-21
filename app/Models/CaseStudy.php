<?php

namespace App\Models;

use App\Models\CaseStudyDownload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_published',
        'pdf_path',
        'thumbnail_path',
    ];
    public function downloads()
    {
        return $this->hasMany(CaseStudyDownload::class);
    }
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }
}
