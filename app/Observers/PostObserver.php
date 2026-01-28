<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ContentAnalysisService;
use App\Services\VersionControlService;

class PostObserver
{
    protected ContentAnalysisService $analysisService;
    protected VersionControlService $versionService;

    public function __construct(
        ContentAnalysisService $analysisService,
        VersionControlService $versionService
    ) {
        $this->analysisService = $analysisService;
        $this->versionService = $versionService;
    }

    /**
     * Handle the Post "saving" event.
     * Calculate quality metrics before saving
     */
    public function saving(Post $post): void
    {
        // Only calculate if content has changed or related fields
        if ($post->isDirty('content') || $post->isDirty('meta_description') || $post->isDirty('keywords') || $post->isDirty('image') || $post->isDirty('alt')) {
            $content = $post->content ?? '';
            $postData = $post->toArray();
            
            $readability = $this->analysisService->calculateReadabilityScore($content);
            $quality = $this->analysisService->calculateQualityScore($content, $postData);
            $readingTime = $this->analysisService->calculateReadingTime($content);
            
            $post->word_count = $readingTime['wordCount'];
            $post->reading_time = $readingTime['minutes'];
            $post->readability_score = $readability['score'];
            $post->quality_score = $quality['percentage'];
            $post->quality_grade = $quality['grade'];
        }
    }

    /**
     * Handle the Post "updated" event.
     * Create version after successful update
     */
    public function updated(Post $post): void
    {
        // Create version if significant changes were made
        if ($this->versionService->shouldCreateVersion($post)) {
            $this->versionService->createVersion($post);
        }
    }
}

