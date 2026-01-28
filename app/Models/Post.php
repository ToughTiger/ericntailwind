<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\PostAnalytic;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_id',
        'reviewer_id',
        'approved_by',
        'title',
        'slug',
        'meta_description',
        'keywords',
        'content',
        'word_count',
        'reading_time',
        'readability_score',
        'quality_score',
        'quality_grade',
        'image',
        'alt',
        'is_featured',
        'is_published',
        'is_verified',
        'published_at',
        'scheduled_at',
        'status',
        'submitted_for_review_at',
        'reviewed_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
    ];


    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    // public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Comment::class)->whereNull('parent_comment'  );
    // }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }

    public function analytics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostAnalytic::class);
    }

    public function versions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostVersion::class)->orderBy('version_number', 'desc');
    }
    
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PostComment::class)->orderBy('created_at', 'desc');
    }
    
    public function reviewer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    
    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods for analytics
    public function getTotalViewsAttribute(): int
    {
        return $this->analytics()->count();
    }

    public function getUniqueViewsAttribute(): int
    {
        return $this->analytics()->where('is_unique_visitor', true)->count();
    }

    // Get current version number
    public function getCurrentVersionNumber(): int
    {
        return $this->versions()->max('version_number') ?? 0;
    }

    // public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    // {
    //     return $this->hasMany(Like::class, 'post_id');
    // }

//    casting Enums
    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'submitted_for_review_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // Scopes for scheduled posts
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                     ->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '<=', now());
    }

    public function scopePendingScheduled($query)
    {
        return $query->where('status', 'scheduled')
                     ->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '>', now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
    // Editorial workflow scopes
    public function scopePendingReview($query)
    {
        return $query->where('status', 'review');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    public function scopeMyPosts($query)
    {
        return $query->where('author_id', auth()->id());
    }
    
    public function scopeAssignedToMe($query)
    {
        return $query->where('reviewer_id', auth()->id());
    }

    // Check if post should be published
    public function shouldBePublished(): bool
    {
        return $this->status === 'scheduled' 
               && $this->scheduled_at 
               && $this->scheduled_at->isPast();
    }
    
    // Editorial workflow actions
    public function submitForReview(?int $reviewerId = null): void
    {
        $this->update([
            'status' => 'review',
            'reviewer_id' => $reviewerId,
            'submitted_for_review_at' => now(),
        ]);
    }
    
    public function approve(?string $comment = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);
        
        if ($comment) {
            $this->addComment($comment, 'approval');
        }
    }
    
    public function reject(string $reason, ?string $comment = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
        
        if ($comment) {
            $this->addComment($comment, 'rejection');
        }
    }
    
    public function requestChanges(string $feedback): void
    {
        $this->update([
            'status' => 'draft',
            'reviewed_at' => now(),
        ]);
        
        $this->addComment($feedback, 'review');
    }
    
    public function addComment(string $comment, string $type = 'comment', ?array $mentionedUsers = null): PostComment
    {
        return $this->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $comment,
            'type' => $type,
            'mentioned_users' => $mentionedUsers,
        ]);
    }

    // Publish the post
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    // Calculate and update quality metrics
    public function updateQualityMetrics(): void
    {
        $analysisService = app(\App\Services\ContentAnalysisService::class);
        
        $readability = $analysisService->calculateReadabilityScore($this->content ?? '');
        $quality = $analysisService->calculateQualityScore($this->content ?? '', $this->toArray());
        $readingTime = $analysisService->calculateReadingTime($this->content ?? '');

        $this->update([
            'word_count' => $readingTime['wordCount'],
            'reading_time' => $readingTime['minutes'],
            'readability_score' => $readability['score'],
            'quality_score' => $quality['percentage'],
            'quality_grade' => $quality['grade'],
        ]);
    }

}