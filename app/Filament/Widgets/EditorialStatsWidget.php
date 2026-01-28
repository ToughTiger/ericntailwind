<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EditorialStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Review', Post::pendingReview()->count())
                ->description('Posts awaiting review')
                ->descriptionIcon('heroicon-m-eye')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('Approved', Post::approved()->count())
                ->description('Ready to publish')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([3, 2, 5, 4, 7, 8, 6, 9]),
            
            Stat::make('My Reviews', Post::assignedToMe()->count())
                ->description('Assigned to me')
                ->descriptionIcon('heroicon-m-user')
                ->color('info')
                ->chart([2, 1, 3, 2, 4, 3, 2, 1]),
            
            Stat::make('Rejected', Post::rejected()->whereDate('rejected_at', '>=', now()->subDays(7))->count())
                ->description('Last 7 days')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->chart([1, 0, 2, 1, 0, 1, 2, 1]),
        ];
    }
}
