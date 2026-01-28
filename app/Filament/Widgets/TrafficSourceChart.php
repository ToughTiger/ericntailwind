<?php

namespace App\Filament\Widgets;

use App\Models\PostAnalytic;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TrafficSourceChart extends ChartWidget
{
    protected static ?string $heading = 'Traffic Sources';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $trafficData = PostAnalytic::select('traffic_source', DB::raw('COUNT(*) as count'))
            ->groupBy('traffic_source')
            ->pluck('count', 'traffic_source')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Traffic by Source',
                    'data' => array_values($trafficData),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)', // blue - direct
                        'rgba(16, 185, 129, 0.8)', // green - search
                        'rgba(251, 146, 60, 0.8)', // orange - social
                        'rgba(168, 85, 247, 0.8)', // purple - referral
                    ],
                ],
            ],
            'labels' => array_map('ucfirst', array_keys($trafficData)),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
