<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Analytics\Period;

class GoogleAnalyticsStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            
            // Get data for different periods
            $today = $analyticsService->getTotalVisitorsAndPageViews(Period::days(1));
            $last7Days = $analyticsService->getTotalVisitorsAndPageViews(Period::days(7));
            $last30Days = $analyticsService->getTotalVisitorsAndPageViews(Period::days(30));
            $last90Days = $analyticsService->getTotalVisitorsAndPageViews(Period::days(90));

            // Calculate trends (compare with previous period)
            $previous30Days = $analyticsService->getTotalVisitorsAndPageViews(
                Period::create(now()->subDays(60), now()->subDays(31))
            );

            $visitorTrend = $previous30Days['visitors'] > 0 
                ? round((($last30Days['visitors'] - $previous30Days['visitors']) / $previous30Days['visitors']) * 100, 1)
                : 0;

            $pageViewTrend = $previous30Days['pageViews'] > 0
                ? round((($last30Days['pageViews'] - $previous30Days['pageViews']) / $previous30Days['pageViews']) * 100, 1)
                : 0;

            return [
                Stat::make('Total Visitors (30 days)', number_format($last30Days['visitors']))
                    ->description(($visitorTrend >= 0 ? '+' : '') . $visitorTrend . '% from previous 30 days')
                    ->descriptionIcon($visitorTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($visitorTrend >= 0 ? 'success' : 'danger')
                    ->chart($this->getVisitorChart()),

                Stat::make('Page Views (30 days)', number_format($last30Days['pageViews']))
                    ->description(($pageViewTrend >= 0 ? '+' : '') . $pageViewTrend . '% from previous 30 days')
                    ->descriptionIcon($pageViewTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($pageViewTrend >= 0 ? 'success' : 'danger'),

                Stat::make('Visitors This Week', number_format($last7Days['visitors']))
                    ->description(number_format($today['visitors']) . ' today')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),

                Stat::make('Views Per Visitor', 
                    $last30Days['visitors'] > 0 
                        ? number_format($last30Days['pageViews'] / $last30Days['visitors'], 2)
                        : '0'
                )
                    ->description('Average pages per session')
                    ->descriptionIcon('heroicon-m-document-duplicate')
                    ->color('warning'),
            ];
        } catch (\Exception $e) {
            return [
                Stat::make('Google Analytics', 'Error')
                    ->description('Unable to fetch analytics data')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger'),
            ];
        }
    }

    protected function getVisitorChart(): array
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            $data = $analyticsService->getVisitorsAndPageViews(Period::days(7));
            
            return $data->pluck('visitors')->toArray();
        } catch (\Exception $e) {
            return [0, 0, 0, 0, 0, 0, 0];
        }
    }
}
