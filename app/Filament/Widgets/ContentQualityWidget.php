<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentQualityWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $posts = Post::whereNotNull('quality_score')->get();
        
        $averageQuality = $posts->avg('quality_score') ?? 0;
        $averageReadability = $posts->avg('readability_score') ?? 0;
        $averageWordCount = $posts->avg('word_count') ?? 0;
        $averageReadingTime = $posts->avg('reading_time') ?? 0;
        
        // Count posts by grade
        $gradeA = Post::where('quality_grade', 'A')->count();
        $gradeB = Post::where('quality_grade', 'B')->count();
        $gradeF = Post::where('quality_grade', 'F')->count();
        
        return [
            Stat::make('Average Quality Score', number_format($averageQuality, 1) . '%')
                ->description($this->getQualityDescription($averageQuality))
                ->descriptionIcon($averageQuality >= 70 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($averageQuality >= 80 ? 'success' : ($averageQuality >= 60 ? 'warning' : 'danger'))
                ->chart($this->getQualityTrend()),
                
            Stat::make('Average Readability', number_format($averageReadability, 1))
                ->description($this->getReadabilityLevel($averageReadability))
                ->descriptionIcon('heroicon-m-book-open')
                ->color($this->getReadabilityColor($averageReadability)),
                
            Stat::make('Avg Word Count', number_format($averageWordCount, 0))
                ->description('~' . number_format($averageReadingTime, 0) . ' min read')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
                
            Stat::make('Quality Distribution', $gradeA . 'A · ' . $gradeB . 'B · ' . $gradeF . 'F')
                ->description('Posts by grade')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray'),
        ];
    }
    
    protected function getQualityDescription(float $score): string
    {
        if ($score >= 90) return 'Excellent quality';
        if ($score >= 80) return 'Good quality';
        if ($score >= 70) return 'Fair quality';
        if ($score >= 60) return 'Needs improvement';
        return 'Poor quality';
    }
    
    protected function getReadabilityLevel(float $score): string
    {
        if ($score >= 80) return 'Easy to read';
        if ($score >= 60) return 'Standard level';
        if ($score >= 50) return 'Fairly difficult';
        return 'Difficult to read';
    }
    
    protected function getReadabilityColor(float $score): string
    {
        if ($score >= 60 && $score <= 80) return 'success';
        if ($score >= 50 || $score <= 90) return 'warning';
        return 'danger';
    }
    
    protected function getQualityTrend(): array
    {
        return Post::whereNotNull('quality_score')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->pluck('quality_score')
            ->reverse()
            ->toArray();
    }
}
