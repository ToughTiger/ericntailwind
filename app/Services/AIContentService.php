<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class AIContentService
{
    /**
     * Generate meta description from content using AI
     */
    public function generateMetaDescription(string $title, string $content, int $maxLength = 160): string
    {
        try {
            $cleanContent = strip_tags($content);
            $cleanContent = substr($cleanContent, 0, 2000); // Limit context

            $prompt = "Based on this blog post title and content, write a compelling SEO meta description (max {$maxLength} characters).\n\nTitle: {$title}\n\nContent: {$cleanContent}\n\nMeta Description:";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an SEO expert. Write concise, compelling meta descriptions that encourage clicks while accurately describing the content.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 100,
                'temperature' => 0.7,
            ]);

            $metaDescription = trim($response->choices[0]->message->content);
            
            // Ensure it's within length limit
            if (strlen($metaDescription) > $maxLength) {
                $metaDescription = substr($metaDescription, 0, $maxLength - 3) . '...';
            }

            return $metaDescription;
        } catch (\Exception $e) {
            \Log::error('AI Meta Description Generation Failed: ' . $e->getMessage());
            
            // Fallback: Create basic meta description
            return $this->generateFallbackMetaDescription($title, $content, $maxLength);
        }
    }

    /**
     * Generate keywords from content using AI
     */
    public function generateKeywords(string $title, string $content, int $count = 5): string
    {
        try {
            $cleanContent = strip_tags($content);
            $cleanContent = substr($cleanContent, 0, 2000);

            $prompt = "Extract {$count} SEO keywords/phrases from this blog post. Return as comma-separated list.\n\nTitle: {$title}\n\nContent: {$cleanContent}\n\nKeywords:";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an SEO expert. Extract relevant keywords and key phrases.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 60,
                'temperature' => 0.5,
            ]);

            return trim($response->choices[0]->message->content);
        } catch (\Exception $e) {
            \Log::error('AI Keyword Generation Failed: ' . $e->getMessage());
            return $this->generateFallbackKeywords($title, $content);
        }
    }

    /**
     * Get SEO suggestions for content
     */
    public function getSEOSuggestions(string $title, string $content, array $currentData = []): array
    {
        $suggestions = [];

        // Title analysis
        $titleLength = strlen($title);
        if ($titleLength < 30) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Title',
                'message' => 'Title is too short. Aim for 50-60 characters for better SEO.',
            ];
        } elseif ($titleLength > 60) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Title',
                'message' => 'Title is too long. Google may truncate it in search results.',
            ];
        }

        // Meta description
        if (empty($currentData['meta_description'])) {
            $suggestions[] = [
                'type' => 'error',
                'category' => 'Meta Description',
                'message' => 'Missing meta description. Use AI generator to create one.',
                'action' => 'generate',
            ];
        } elseif (strlen($currentData['meta_description']) < 120) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Meta Description',
                'message' => 'Meta description is too short. Aim for 150-160 characters.',
            ];
        }

        // Keywords
        if (empty($currentData['keywords'])) {
            $suggestions[] = [
                'type' => 'error',
                'category' => 'Keywords',
                'message' => 'No keywords specified. Add relevant keywords for SEO.',
                'action' => 'generate',
            ];
        }

        // Image alt text
        if (!empty($currentData['image']) && empty($currentData['alt'])) {
            $suggestions[] = [
                'type' => 'error',
                'category' => 'Images',
                'message' => 'Featured image missing alt text. Add descriptive alt text.',
            ];
        }

        // Content length
        $wordCount = str_word_count(strip_tags($content));
        if ($wordCount < 300) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Content',
                'message' => "Content is short ({$wordCount} words). Aim for at least 500 words.",
            ];
        }

        // Headings
        if (!preg_match('/<h[1-6]/', $content)) {
            $suggestions[] = [
                'type' => 'warning',
                'category' => 'Structure',
                'message' => 'No headings found. Use headings (H2, H3) to structure content.',
            ];
        }

        return $suggestions;
    }

    /**
     * Fallback meta description generator (no AI)
     */
    private function generateFallbackMetaDescription(string $title, string $content, int $maxLength): string
    {
        $cleanContent = strip_tags($content);
        $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
        
        // Try to get first paragraph
        $firstParagraph = substr($cleanContent, 0, $maxLength);
        
        // Cut at last complete sentence
        $lastPeriod = strrpos($firstParagraph, '.');
        if ($lastPeriod !== false && $lastPeriod > $maxLength * 0.5) {
            return substr($firstParagraph, 0, $lastPeriod + 1);
        }
        
        // Cut at last space
        $lastSpace = strrpos($firstParagraph, ' ');
        if ($lastSpace !== false) {
            return substr($firstParagraph, 0, $lastSpace) . '...';
        }
        
        return $firstParagraph . '...';
    }

    /**
     * Fallback keyword generator (no AI)
     */
    private function generateFallbackKeywords(string $title, string $content): string
    {
        // Extract words from title
        $titleWords = explode(' ', strtolower($title));
        $titleWords = array_filter($titleWords, fn($word) => strlen($word) > 3);
        
        return implode(', ', array_slice($titleWords, 0, 3));
    }
}
