<?php

namespace App\Filament\Widgets;
use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class DevicesChart extends ChartWidget
{
    
    protected static ?string $heading = 'Devices Used';

    protected function getData(): array
    {
        $analyticsService = app(AnalyticsService::class);
        $devices = $analyticsService->getDeviceCategories(Period::days(30));
        
        return [
            'datasets' => [[
                'label' => 'Devices',
                'data' => $devices->pluck('activeUsers')->toArray(),
                'backgroundColor' => [
                    'rgba(24, 97, 6, 0.97)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ]
            ]],
            'labels' => $devices->pluck('deviceCategory')->toArray()
        ];
    }

    protected function getType(): string { return 'bar'; }
}