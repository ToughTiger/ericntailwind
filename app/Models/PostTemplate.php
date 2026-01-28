<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content_template',
        'default_title_pattern',
        'default_excerpt',
        'default_meta_description',
        'default_keywords',
        'is_default',
        'is_active',
        'icon',
        'color',
        'usage_count',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get default template
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true)->where('is_active', true);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Apply template to a post
     */
    public function applyToPost(Post $post): void
    {
        $updates = [];

        if ($this->content_template) {
            $updates['content'] = $this->content_template;
        }

        if ($this->default_excerpt) {
            $updates['excerpt'] = $this->default_excerpt;
        }

        if ($this->default_meta_description) {
            $updates['meta_description'] = $this->default_meta_description;
        }

        if ($this->default_keywords) {
            $updates['keywords'] = $this->default_keywords;
        }

        if (!empty($updates)) {
            $post->update($updates);
        }

        $this->incrementUsage();
    }
}
