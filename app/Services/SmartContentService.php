<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SmartContentService
{
    /**
     * Find related posts based on content similarity
     */
    public function findRelatedPosts(Post $post, int $limit = 5): Collection
    {
        // Get posts with similar tags
        $tagIds = $post->tags()->pluck('tags.id');
        
        $relatedByTags = Post::query()
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->withCount(['tags' => function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            }])
            ->orderBy('tags_count', 'desc')
            ->limit($limit * 2)
            ->get();

        // Get posts with similar categories
        $categoryIds = $post->categories()->pluck('categories.id');
        
        $relatedByCategories = Post::query()
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->limit($limit * 2)
            ->get();

        // Merge and deduplicate
        $related = $relatedByTags->merge($relatedByCategories)->unique('id');

        // Score by similarity
        $scored = $related->map(function ($relatedPost) use ($post, $tagIds, $categoryIds) {
            $score = 0;
            
            // Tag similarity
            $commonTags = $relatedPost->tags()->whereIn('tags.id', $tagIds)->count();
            $score += $commonTags * 3;
            
            // Category similarity
            $commonCategories = $relatedPost->categories()->whereIn('categories.id', $categoryIds)->count();
            $score += $commonCategories * 2;
            
            // Keyword similarity
            if ($post->keywords && $relatedPost->keywords) {
                $postKeywords = explode(',', strtolower($post->keywords));
                $relatedKeywords = explode(',', strtolower($relatedPost->keywords));
                $commonKeywords = count(array_intersect($postKeywords, $relatedKeywords));
                $score += $commonKeywords * 2;
            }
            
            // Quality bonus
            if ($relatedPost->quality_score >= 80) {
                $score += 1;
            }
            
            $relatedPost->similarity_score = $score;
            return $relatedPost;
        });

        return $scored->sortByDesc('similarity_score')->take($limit)->values();
    }

    /**
     * Suggest tags based on content using keyword extraction
     */
    public function suggestTags(string $content, ?string $title = null, int $limit = 10): array
    {
        // Combine title and content
        $text = ($title ? $title . ' ' : '') . strip_tags($content);
        $text = strtolower($text);
        
        // Remove common words
        $stopWords = ['the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'i', 'it', 'for', 'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at', 'this', 'but', 'his', 'by', 'from', 'is', 'was', 'are', 'been', 'has', 'had', 'were', 'will', 'would', 'can', 'could', 'should', 'may', 'might', 'must', 'shall', 'an', 'or', 'if', 'when', 'than', 'because', 'while', 'where', 'after', 'so', 'though', 'since', 'until', 'whether', 'before', 'although', 'nor', 'like', 'once', 'unless', 'now', 'except'];
        
        // Extract words
        $words = str_word_count($text, 1);
        
        // Filter and count
        $wordFreq = [];
        foreach ($words as $word) {
            if (strlen($word) >= 4 && !in_array($word, $stopWords)) {
                if (!isset($wordFreq[$word])) {
                    $wordFreq[$word] = 0;
                }
                $wordFreq[$word]++;
            }
        }
        
        // Sort by frequency
        arsort($wordFreq);
        
        // Get top keywords
        $keywords = array_slice(array_keys($wordFreq), 0, 20);
        
        // Match with existing tags
        $existingTags = Tag::whereIn('name', $keywords)
            ->orWhere(function ($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('name', 'LIKE', "%{$keyword}%");
                }
            })
            ->limit($limit)
            ->get()
            ->pluck('name')
            ->toArray();
        
        // If we have existing tags, return them
        if (count($existingTags) >= 3) {
            return array_slice($existingTags, 0, $limit);
        }
        
        // Otherwise, suggest new tags from keywords
        $suggestions = array_slice($keywords, 0, $limit);
        
        // Merge existing and new suggestions
        $merged = array_unique(array_merge($existingTags, $suggestions));
        
        return array_slice($merged, 0, $limit);
    }

    /**
     * Suggest categories based on content
     */
    public function suggestCategories(string $content, ?string $title = null, int $limit = 3): array
    {
        $text = ($title ? $title . ' ' : '') . strip_tags($content);
        $text = strtolower($text);
        
        // Get all categories
        $categories = \App\Models\Category::all();
        
        if ($categories->isEmpty()) {
            return [];
        }
        
        // Score each category
        $scored = $categories->map(function ($category) use ($text) {
            $categoryName = strtolower($category->name);
            $categorySlug = strtolower($category->slug ?? '');
            
            $score = 0;
            
            // Check if category name appears in text
            $nameCount = substr_count($text, $categoryName);
            $score += $nameCount * 5;
            
            // Check if category slug appears
            $slugCount = substr_count($text, $categorySlug);
            $score += $slugCount * 3;
            
            // Bonus for exact match in title
            if (strpos($text, $categoryName) !== false) {
                $score += 10;
            }
            
            $category->relevance_score = $score;
            return $category;
        });
        
        return $scored->filter(fn($cat) => $cat->relevance_score > 0)
            ->sortByDesc('relevance_score')
            ->take($limit)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Find duplicate or very similar posts
     */
    public function findSimilarPosts(Post $post, float $threshold = 0.7): Collection
    {
        if (!$post->title) {
            return collect();
        }
        
        // Find posts with very similar titles
        $similarByTitle = Post::query()
            ->where('id', '!=', $post->id)
            ->where('title', 'LIKE', '%' . substr($post->title, 0, 20) . '%')
            ->limit(10)
            ->get();
        
        // Calculate similarity score
        $similar = $similarByTitle->filter(function ($otherPost) use ($post, $threshold) {
            $similarity = $this->calculateTextSimilarity($post->title, $otherPost->title);
            return $similarity >= $threshold;
        });
        
        return $similar;
    }

    /**
     * Calculate text similarity (simple Levenshtein-based)
     */
    protected function calculateTextSimilarity(string $text1, string $text2): float
    {
        $text1 = strtolower(trim($text1));
        $text2 = strtolower(trim($text2));
        
        if ($text1 === $text2) {
            return 1.0;
        }
        
        $maxLength = max(strlen($text1), strlen($text2));
        if ($maxLength === 0) {
            return 0.0;
        }
        
        $distance = levenshtein($text1, $text2);
        return 1 - ($distance / $maxLength);
    }
}
