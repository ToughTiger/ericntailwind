<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'referrer_url',
        'traffic_source',
        'country_code',
        'city',
        'scroll_depth',
        'time_spent',
        'is_unique_visitor',
        'read_complete',
        'viewed_at',
    ];

    protected $casts = [
        'is_unique_visitor' => 'boolean',
        'read_complete' => 'boolean',
        'viewed_at' => 'datetime',
        'scroll_depth' => 'integer',
        'time_spent' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
