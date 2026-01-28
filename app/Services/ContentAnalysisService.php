<?php

namespace App\Services;

class ContentAnalysisService
{
    /**
     * Calculate Flesch Reading Ease Score
     * 90-100: Very Easy
     * 80-89: Easy
     * 70-79: Fairly Easy
     * 60-69: Standard
     * 50-59: Fairly Difficult
     * 30-49: Difficult
     * 0-29: Very Confusing
     */
    public function calculateReadabilityScore(string $content): array
    {
        $cleanContent = strip_tags($content);
        $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
        
        $wordCount = $this->countWords($cleanContent);
        $sentenceCount = $this->countSentences($cleanContent);
        $syllableCount = $this->countSyllables($cleanContent);

        if ($wordCount === 0 || $sentenceCount === 0) {
            return [
                'score' => 0,
                'level' => 'N/A',
                'color' => 'gray',
            ];
        }

        // Flesch Reading Ease formula
        $score = 206.835 - 1.015 * ($wordCount / $sentenceCount) - 84.6 * ($syllableCount / $wordCount);
        $score = max(0, min(100, $score)); // Clamp between 0-100

        return [
            'score' => round($score, 1),
            'level' => $this->getReadabilityLevel($score),
            'color' => $this->getReadabilityColor($score),
            'wordCount' => $wordCount,
            'sentenceCount' => $sentenceCount,
            'syllableCount' => $syllableCount,
        ];
    }

    /**
     * Calculate comprehensive content quality score
     */
    public function calculateQualityScore(string $content, array $postData = []): array
    {
        $scores = [];
        $totalScore = 0;
        $maxScore = 0;

        // 1. Content Length (20 points)
        $wordCount = $this->countWords(strip_tags($content));
        $lengthScore = $this->scoreLengthQuality($wordCount);
        $scores['length'] = ['score' => $lengthScore, 'max' => 20];
        $totalScore += $lengthScore;
        $maxScore += 20;

        // 2. Readability (20 points)
        $readability = $this->calculateReadabilityScore($content);
        $readabilityScore = $this->scoreReadabilityQuality($readability['score']);
        $scores['readability'] = ['score' => $readabilityScore, 'max' => 20];
        $totalScore += $readabilityScore;
        $maxScore += 20;

        // 3. SEO Elements (30 points)
        $seoScore = $this->scoreSEOQuality($postData);
        $scores['seo'] = ['score' => $seoScore, 'max' => 30];
        $totalScore += $seoScore;
        $maxScore += 30;

        // 4. Structure (15 points)
        $structureScore = $this->scoreStructureQuality($content);
        $scores['structure'] = ['score' => $structureScore, 'max' => 15];
        $totalScore += $structureScore;
        $maxScore += 15;

        // 5. Media (15 points)
        $mediaScore = $this->scoreMediaQuality($content, $postData);
        $scores['media'] = ['score' => $mediaScore, 'max' => 15];
        $totalScore += $mediaScore;
        $maxScore += 15;

        $percentage = ($totalScore / $maxScore) * 100;

        return [
            'totalScore' => round($totalScore, 1),
            'maxScore' => $maxScore,
            'percentage' => round($percentage, 1),
            'grade' => $this->getGrade($percentage),
            'color' => $this->getGradeColor($percentage),
            'breakdown' => $scores,
        ];
    }

    /**
     * Analyze keyword density
     */
    public function analyzeKeywordDensity(string $content, ?string $keywords = null): array
    {
        $cleanContent = strtolower(strip_tags($content));
        $words = str_word_count($cleanContent, 1);
        $totalWords = count($words);

        if ($totalWords === 0) {
            return ['density' => [], 'totalWords' => 0];
        }

        $wordFrequency = array_count_values($words);
        arsort($wordFrequency);

        // Remove common stop words
        $stopWords = ['the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'i', 'it', 'for', 'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at', 'this', 'but', 'his', 'by', 'from', 'is', 'was', 'are', 'an'];
        $wordFrequency = array_diff_key($wordFrequency, array_flip($stopWords));

        $density = [];
        $count = 0;
        foreach ($wordFrequency as $word => $freq) {
            if ($count >= 10) break; // Top 10
            if (strlen($word) < 4) continue; // Skip short words
            
            $density[$word] = [
                'count' => $freq,
                'density' => round(($freq / $totalWords) * 100, 2),
            ];
            $count++;
        }

        return [
            'density' => $density,
            'totalWords' => $totalWords,
            'uniqueWords' => count(array_unique($words)),
        ];
    }

    /**
     * Calculate estimated reading time
     */
    public function calculateReadingTime(string $content): array
    {
        $wordCount = $this->countWords(strip_tags($content));
        $wordsPerMinute = 200; // Average reading speed
        $minutes = ceil($wordCount / $wordsPerMinute);

        return [
            'minutes' => $minutes,
            'wordCount' => $wordCount,
            'formatted' => $minutes === 1 ? '1 min read' : "{$minutes} min read",
        ];
    }

    // Helper methods

    private function countWords(string $text): int
    {
        return str_word_count($text);
    }

    private function countSentences(string $text): int
    {
        return preg_match_all('/[.!?]+/', $text);
    }

    private function countSyllables(string $text): int
    {
        $words = str_word_count(strtolower($text), 1);
        $syllableCount = 0;

        foreach ($words as $word) {
            $syllableCount += $this->countSyllablesInWord($word);
        }

        return $syllableCount;
    }

    private function countSyllablesInWord(string $word): int
    {
        $word = strtolower($word);
        $syllables = 0;
        $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];
        $previousWasVowel = false;

        for ($i = 0; $i < strlen($word); $i++) {
            $isVowel = in_array($word[$i], $vowels);
            
            if ($isVowel && !$previousWasVowel) {
                $syllables++;
            }
            
            $previousWasVowel = $isVowel;
        }

        // Adjust for silent 'e'
        if (substr($word, -1) === 'e') {
            $syllables--;
        }

        return max(1, $syllables);
    }

    private function getReadabilityLevel(float $score): string
    {
        if ($score >= 90) return 'Very Easy';
        if ($score >= 80) return 'Easy';
        if ($score >= 70) return 'Fairly Easy';
        if ($score >= 60) return 'Standard';
        if ($score >= 50) return 'Fairly Difficult';
        if ($score >= 30) return 'Difficult';
        return 'Very Confusing';
    }

    private function getReadabilityColor(float $score): string
    {
        if ($score >= 70) return 'success';
        if ($score >= 50) return 'warning';
        return 'danger';
    }

    private function scoreLengthQuality(int $wordCount): float
    {
        if ($wordCount >= 1500) return 20; // Ideal
        if ($wordCount >= 1000) return 18;
        if ($wordCount >= 750) return 15;
        if ($wordCount >= 500) return 12;
        if ($wordCount >= 300) return 8;
        return 4;
    }

    private function scoreReadabilityQuality(float $score): float
    {
        if ($score >= 60 && $score <= 80) return 20; // Ideal range
        if ($score >= 50 && $score < 90) return 15;
        if ($score >= 40 && $score < 95) return 10;
        return 5;
    }

    private function scoreSEOQuality(array $postData): float
    {
        $score = 0;
        
        // Meta description (10 points)
        if (!empty($postData['meta_description']) && strlen($postData['meta_description']) >= 120) {
            $score += 10;
        } elseif (!empty($postData['meta_description'])) {
            $score += 5;
        }

        // Keywords (10 points)
        if (!empty($postData['keywords'])) {
            $score += 10;
        }

        // Alt text for image (10 points)
        if (!empty($postData['image']) && !empty($postData['alt'])) {
            $score += 10;
        } elseif (!empty($postData['image'])) {
            $score += 5;
        }

        return $score;
    }

    private function scoreStructureQuality(string $content): float
    {
        $score = 0;
        
        // Has headings
        if (preg_match('/<h[1-6]/', $content)) {
            $score += 5;
        }

        // Has lists
        if (preg_match('/<[uo]l>/', $content)) {
            $score += 5;
        }

        // Has paragraphs
        $paragraphCount = substr_count($content, '<p>');
        if ($paragraphCount >= 3) {
            $score += 5;
        }

        return $score;
    }

    private function scoreMediaQuality(string $content, array $postData): float
    {
        $score = 0;
        
        // Featured image
        if (!empty($postData['image'])) {
            $score += 10;
        }

        // Images in content
        if (preg_match_all('/<img/', $content) > 0) {
            $score += 5;
        }

        return $score;
    }

    private function getGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    private function getGradeColor(float $percentage): string
    {
        if ($percentage >= 80) return 'success';
        if ($percentage >= 60) return 'warning';
        return 'danger';
    }
}
