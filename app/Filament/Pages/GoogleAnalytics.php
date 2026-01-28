<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsOverview;
use App\Filament\Widgets\DevicesChart;
use App\Filament\Widgets\UserTypesChart;
use App\Filament\Widgets\TopBrowsersChart;
use App\Filament\Widgets\TopPagesTable;
use App\Filament\Widgets\GoogleAnalyticsStats;
use App\Filament\Widgets\AcquisitionChannelChart;
use App\Filament\Widgets\TopCountriesChart;
use App\Filament\Widgets\TopReferrersTable;
use Filament\Pages\Page;

class GoogleAnalytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Google Analytics';
    protected static string $view = 'filament.pages.google-analytics';
    protected static ?string $navigationGroup = 'Analytics';
    protected static ?int $navigationSort = 2;

    protected function getHeaderWidgets(): array
    {
        return [
            GoogleAnalyticsStats::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AnalyticsOverview::class,
            AcquisitionChannelChart::class,
            TopCountriesChart::class,
            DevicesChart::class,
            TopBrowsersChart::class,
            UserTypesChart::class,
            TopReferrersTable::class,
        ];
    }
}
