<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'version_number',
        'title',
        'content',
        'excerpt',
        'meta_description',
        'keywords',
        'change_summary',
        'is_major_change',
        'changes_made',
    ];

    protected $casts = [
        'is_major_change' => 'boolean',
        'changes_made' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the post that owns this version
     */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user who created this version
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get version label
     */
    public function getVersionLabelAttribute(): string
    {
        return "v{$this->version_number}" . ($this->is_major_change ? ' (Major)' : '');
    }

    /**
     * Get formatted changes
     */
    public function getFormattedChangesAttribute(): string
    {
        if (!$this->changes_made) {
            return $this->change_summary ?? 'No change summary';
        }

        $changes = [];
        foreach ($this->changes_made as $field => $change) {
            $changes[] = ucfirst(str_replace('_', ' ', $field));
        }

        return implode(', ', $changes);
    }
}
