<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BulletPoint;

class Featured extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'isActive',
    ];

    public function bulletPoints()
    {
        return $this->hasMany(BulletPoint::class);
    }
}
