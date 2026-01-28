<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Widgets\ChartWidget;
use Spatie\Analytics\Period;

class AcquisitionChannelChart extends ChartWidget
{
    protected static ?string $heading = 'Acquisition Channels';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            $acquisitionData = $analyticsService->getAcquisitionOverview(Period::days(30));

            $labels = [];
            $data = [];

            foreach ($acquisitionData as $row) {
                $channel = $row['sessionDefaultChannelGroup'] ?? 'Unknown';
                $labels[] = ucfirst($channel);
                $data[] = $row['activeUsers'] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Users by Channel',
                        'data' => $data,
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.8)',   // blue - Organic Search
                            'rgba(16, 185, 129, 0.8)',   // green - Direct
                            'rgba(251, 146, 60, 0.8)',   // orange - Social
                            'rgba(168, 85, 247, 0.8)',   // purple - Referral
                            'rgba(239, 68, 68, 0.8)',    // red - Paid
                            'rgba(245, 158, 11, 0.8)',   // amber - Email
                            'rgba(107, 114, 128, 0.8)',  // gray - Other
                        ],
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
