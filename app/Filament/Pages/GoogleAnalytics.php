<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\DevicesChart;
use App\Filament\Widgets\UserTypesChart;
use App\Filament\Widgets\TopBrowsersChart;
use App\Filament\Widgets\TopPagesTable;
use Filament\Pages\Page;

class GoogleAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Google Analytics';
    protected static string $view = 'filament.pages.google-analytics';

    protected function getWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            TopBrowsersChart::class,
            UserTypesChart::class,
            DevicesChart::class,
            // TopPagesTable::class,
        ];
    }

    // Optional: Control widget columns
    // protected function getColumns(): int | string | array
    // {
    //     return [
    //         'sm' => 1,
    //         'md' => 2,
    //         'lg' => 4,
    //         'xl' => 6,
    //     ];
    // }
}
