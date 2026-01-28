<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Period;

class TopCountriesChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Countries';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            $geoData = $analyticsService->getGeoData(Period::days(30));

            $labels = [];
            $data = [];

            foreach ($geoData as $row) {
                $labels[] = $row['country'] ?? 'Unknown';
                $data[] = $row['activeUsers'] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Users by Country',
                        'data' => $data,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                        'borderColor' => 'rgba(59, 130, 246, 1)',
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => $labels,
            ];
        } catch (\Exception $e) {
            return [
                'datasets' => [['label' => 'No Data', 'data' => []]],
                'labels' => [],
            ];
        }
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
