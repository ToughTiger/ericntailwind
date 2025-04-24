<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class UserTypesChart extends ChartWidget
{
    protected static ?string $heading = 'New vs Returning Visitors (Last 30 Days)';
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'doughnut'; // Explicit doughnut chart type
    }

    protected function getData(): array
    {
        // $analytics = app(Analytics::class);
        $userTypes = Analytics::fetchUserTypes(Period::days(30));
        // dd($userTypes);

        // Default data if no results
        $labels = ['New Visitors', 'Returning Visitors'];
        $data = [0, 0];
        $colors = ['#3b82f6', '#10b981']; // Blue for new, green for returning

        if ($userTypes->isNotEmpty()) {
            $labels = $userTypes->pluck('newVsReturning')->map(function ($type) {
                return ucfirst($type) . ' Visitors';
            })->toArray();

            $data = $userTypes->pluck('activeUsers')->toArray();
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'hoverBackgroundColor' => ['#2563eb', '#059669'], // Darker shades on hover
                    'borderWidth' => 0,
                    'cutout' => '70%', // Proper doughnut hole
                    'hoverOffset' => 10,
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
                        'font' => ['size' => 14],
                        'padding' => 15,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.raw.toLocaleString() + " (" + Math.round(context.parsed*100/context.dataset.data.reduce((a,b)=>a+b)) + "%)";
                        }'
                    ]
                ],
            ],
            'maintainAspectRatio' => false,
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ],
            'responsive' => true,
        ];
    }
}
