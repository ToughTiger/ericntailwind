<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Spatie\Analytics\Period;

class TopReferrersTable extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): ?string
    {
        return 'Top Referrers (Last 30 Days)';
    }

    public function table(Table $table): Table
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            $referrers = $analyticsService->getTopReferrers(Period::days(30), 15);

            $tableData = collect($referrers)->map(function ($referrer) {
                return [
                    'url' => $referrer['url'] ?? 'Direct',
                    'pageViews' => $referrer['pageViews'] ?? 0,
                ];
            })->toArray();

            return $table
                ->query(
                    collect($tableData)->map(fn($item) => (object) $item)
                )
                ->columns([
                    Tables\Columns\TextColumn::make('url')
                        ->label('Referrer URL')
                        ->searchable()
                        ->limit(60)
                        ->tooltip(fn ($state) => $state)
                        ->url(fn ($record) => $record->url !== 'Direct' ? 'https://' . $record->url : null, shouldOpenInNewTab: true),

                    Tables\Columns\TextColumn::make('pageViews')
                        ->label('Page Views')
                        ->numeric()
                        ->sortable()
                        ->formatStateUsing(fn ($state) => number_format($state))
                        ->badge()
                        ->color('success'),
                ])
                ->paginated(false);
        } catch (\Exception $e) {
            return $table
                ->query(collect([]))
                ->columns([
                    Tables\Columns\TextColumn::make('error')
                        ->label('Error')
                        ->default('Unable to fetch referrer data'),
                ]);
        }
    }
}
