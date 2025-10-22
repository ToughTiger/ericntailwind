<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Featured;

class BulletPoint extends Model
{
    use HasFactory;
    protected $fillable = [
        'featured_id',
        'text',
        'order',
        'icon_color',
        'heading_color',
        'link_color',
        'heading',
    ];
    public function featured()
    {
        return $this->belongsTo(Featured::class);
    }
}
