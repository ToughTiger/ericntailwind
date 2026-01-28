<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'comment',
        'type',
        'is_internal',
        'mentioned_users',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'mentioned_users' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the post that owns the comment
     */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who made the comment
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'parent_id');
    }

    /**
     * Get replies to this comment
     */
    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

    /**
     * Get type badge color
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->type) {
            'comment' => 'gray',
            'review' => 'info',
            'approval' => 'success',
            'rejection' => 'danger',
            default => 'gray',
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'comment' => 'Comment',
            'review' => 'Review Feedback',
            'approval' => 'Approval',
            'rejection' => 'Rejection',
            default => 'Comment',
        };
    }
}
