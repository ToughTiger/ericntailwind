<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\PostAnalytic;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class BlogPerformanceStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        // Posts stats
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $scheduledPosts = Post::where('status', 'scheduled')->count();
        
        // Published posts today/week/month
        $postsToday = Post::where('status', 'published')->whereDate('published_at', $today)->count();
        $postsThisWeek = Post::where('status', 'published')->where('published_at', '>=', $thisWeek)->count();
        $postsThisMonth = Post::where('status', 'published')->where('published_at', '>=', $thisMonth)->count();

        // Analytics stats
        $totalViews = PostAnalytic::count();
        $uniqueViews = PostAnalytic::where('is_unique_visitor', true)->count();
        $viewsToday = PostAnalytic::whereDate('viewed_at', $today)->count();
        $viewsThisWeek = PostAnalytic::where('viewed_at', '>=', $thisWeek)->count();

        return [
            Stat::make('Total Posts', $totalPosts)
                ->description($publishedPosts . ' published, ' . $draftPosts . ' drafts, ' . $scheduledPosts . ' scheduled')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success')
                ->chart($this->getPostsChart()),

            Stat::make('Posts This Month', $postsThisMonth)
                ->description($postsThisWeek . ' this week, ' . $postsToday . ' today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Scheduled Posts', $scheduledPosts)
                ->description(Post::pendingScheduled()->count() . ' upcoming')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.posts.index')),

            Stat::make('Total Views', number_format($totalViews))
                ->description(number_format($uniqueViews) . ' unique visitors')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary')
                ->chart($this->getViewsChart()),
        ];
    }

    protected function getPostsChart(): array
    {
        // Get posts published in last 7 days
        $data = Post::where('status', 'published')
            ->where('published_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(published_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        return array_pad($data, 7, 0);
    }

    protected function getViewsChart(): array
    {
        // Get views in last 7 days
        $data = PostAnalytic::where('viewed_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        return array_pad($data, 7, 0);
    }
}
