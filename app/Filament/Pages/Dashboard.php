<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $routePath = '/';
    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\QuickActionsWidget::class,
            \App\Filament\Widgets\EditorialStatsWidget::class,
            \App\Filament\Widgets\BlogPerformanceStats::class,
            \App\Filament\Widgets\SEOHealthWidget::class,
            \App\Filament\Widgets\ContentQualityWidget::class,
            \App\Filament\Widgets\SmartContentStatsWidget::class,
            \App\Filament\Widgets\MyReviewsWidget::class,
            \App\Filament\Widgets\ReviewQueueWidget::class,
            \App\Filament\Widgets\ScheduledPostsWidget::class,
            \App\Filament\Widgets\LowQualityPostsWidget::class,
            \App\Filament\Widgets\RecentVersionsWidget::class,
            \App\Filament\Widgets\PostPerformanceTable::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }
}
