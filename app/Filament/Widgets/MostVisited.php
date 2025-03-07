<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
class MostVisited extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $labels = [];
        $url = [];
        $views = [];
        $pages = Analytics::fetchMostVisitedPages(Period::days(7))->toArray();
        // dd($pages);
        foreach ($pages as $page) {
            $labels[] = $page['pageTitle'];
            $url[] = $page['fullPageUrl'];
            $views[] = $page['screenPageViews'];
            
        }
        return[
            'datasets' => [
                [
                    'label' => 'Page Title',
                    'data' => $url,
                    'views' => $views,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                ],
            ],
            'labels' => $labels,
        ];
    }
       

    protected function getType(): string
    {
        return 'pie';
    }
}
