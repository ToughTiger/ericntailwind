<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SEOHealthWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $totalPublished = Post::where('status', 'published')->count();
        
        // Posts missing SEO elements
        $missingMetaDesc = Post::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('meta_description')
                      ->orWhere('meta_description', '');
            })->count();

        $missingKeywords = Post::where('status', 'published')
            ->where(function($query) {
                $query->whereNull('keywords')
                      ->orWhere('keywords', '');
            })->count();

        $missingAltTags = Post::where('status', 'published')
            ->whereNotNull('image')
            ->where(function($query) {
                $query->whereNull('alt')
                      ->orWhere('alt', '');
            })->count();

        $verified = Post::where('status', 'published')
            ->where('is_verified', true)
            ->count();

        // Calculate health score
        $seoIssues = $missingMetaDesc + $missingKeywords + $missingAltTags;
        $healthScore = $totalPublished > 0 
            ? round((($totalPublished * 3 - $seoIssues) / ($totalPublished * 3)) * 100)
            : 100;

        return [
            Stat::make('SEO Health Score', $healthScore . '%')
                ->description($seoIssues . ' issues found across published posts')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($healthScore >= 80 ? 'success' : ($healthScore >= 60 ? 'warning' : 'danger'))
                ->chart([$healthScore, 100 - $healthScore]),

            Stat::make('Missing Meta Descriptions', $missingMetaDesc)
                ->description('Posts need meta descriptions')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($missingMetaDesc > 0 ? 'danger' : 'success'),

            Stat::make('Missing Keywords', $missingKeywords)
                ->description('Posts need SEO keywords')
                ->descriptionIcon('heroicon-m-key')
                ->color($missingKeywords > 0 ? 'danger' : 'success'),

            Stat::make('Missing Alt Tags', $missingAltTags)
                ->description('Images need alt text')
                ->descriptionIcon('heroicon-m-photo')
                ->color($missingAltTags > 0 ? 'danger' : 'success'),
        ];
    }
}
