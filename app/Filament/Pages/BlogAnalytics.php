<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BlogPerformanceStats;
use App\Filament\Widgets\PostPerformanceTable;
use App\Filament\Widgets\SEOHealthWidget;
use App\Filament\Widgets\TrafficSourceChart;
use Filament\Pages\Page;

class BlogAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'Blog Analytics';
    protected static ?string $title = 'Blog Analytics Dashboard';
    protected static string $view = 'filament.pages.blog-analytics';
    protected static ?string $navigationGroup = 'Analytics';
    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            BlogPerformanceStats::class,
            SEOHealthWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TrafficSourceChart::class,
            PostPerformanceTable::class,
        ];
    }
}
