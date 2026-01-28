<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SmartContentStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $totalTags = \App\Models\Tag::count();
        $totalCategories = \App\Models\Category::count();
        $totalVersions = \App\Models\PostVersion::count();
        
        $avgVersionsPerPost = $totalPosts > 0 ? round($totalVersions / $totalPosts, 1) : 0;
        $postsWithMultipleVersions = Post::has('versions', '>=', 2)->count();
        
        $recentVersions = \App\Models\PostVersion::where('created_at', '>=', now()->subDays(7))->count();
        
        return [
            Stat::make('Total Versions', number_format($totalVersions))
                ->description("{$postsWithMultipleVersions} posts with revisions")
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('info')
                ->chart($this->getVersionTrend()),
                
            Stat::make('Avg Versions/Post', $avgVersionsPerPost)
                ->description('Average revision history')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
                
            Stat::make('This Week', $recentVersions . ' versions')
                ->description('Recent revisions')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
                
            Stat::make('Content Organization', "{$totalTags} tags · {$totalCategories} categories")
                ->description('Taxonomy structure')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),
        ];
    }
    
    protected function getVersionTrend(): array
    {
        return \App\Models\PostVersion::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }
}
