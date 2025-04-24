<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class TopBrowsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top Browsers';

    protected function getData(): array
    {
        $browsers = Analytics::fetchTopBrowsers(Period::days(30));
        $labels = [];
        $data = [];
        $colors = [
            '#FF6384', // Red
            '#36A2EB', // Blue
            '#FFCE56', // Yellow
            '#4BC0C0', // Teal
            '#9966FF', // Purple
            '#FF9F40', // Orange
            '#8AC249', // Green
            '#EA5F89', // Pink
            '#0D4C92', // Dark Blue
            '#EB7A1E'  // Dark Orange
        ];

        foreach ($browsers as $index => $browser) {
            // dd($browser);
            $labels[] = $browser['browser'];
            $data[] = $browser['screenPageViews'];
        }
        return [
          'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'hoverOffset' => 10,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 20,
                        'usePointStyle' => true,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
            'cutout' => '65%', // Makes it a donut chart if preferred
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
