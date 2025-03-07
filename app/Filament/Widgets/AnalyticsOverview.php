<?php
namespace App\Filament\Widgets;
use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Facades\Analytics;

use Spatie\Analytics\Period;
use Spatie\Analytics\Facades\AnalyticsFacade;

class AnalyticsOverview extends ChartWidget
{
    protected static ?string $heading = 'Google Analytics Overview';

    protected function getData(): array
    {
       
        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30));
        // dd($analyticsData);

        $labels = [];
        $data = [];

        foreach ($analyticsData as $day) {
            $labels[] = $day['date']->format('M d');
            $data[] = $day['activeUsers'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}