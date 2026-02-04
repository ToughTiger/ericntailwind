<?php

namespace App\Filament\Widgets;

use App\Services\AnalyticsService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Analytics\Period;

class TopReferrersTable extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): ?string
    {
        return 'Top Referrers (Last 30 Days)';
    }

    protected function getTableQuery(): Builder|null
    {
        // Return null to use static data instead
        return null;
    }

    public function getTableRecords(): Collection
    {
        try {
            $analyticsService = app(AnalyticsService::class);
            $referrers = $analyticsService->getTopReferrers(Period::days(30), 15);

            $data = collect($referrers)->map(function ($referrer) {
                return [
                    'url' => $referrer['url'] ?? 'Direct',
                    'pageViews' => $referrer['pageViews'] ?? 0,
                ];
            });

            return new Collection($data);
        } catch (\Exception $e) {
            return new Collection([]);
        }
    }

    public function table(Table $table): Table
    {
        $records = $this->getTableRecords();

        return $table
            ->query(fn() => null)
            ->records($records)
            ->columns([
                Tables\Columns\TextColumn::make('url')
                    ->label('Referrer URL')
                    ->searchable()
                    ->limit(60)
                    ->tooltip(fn ($state) => $state)
                    ->url(fn ($record) => is_array($record) && isset($record['url']) && $record['url'] !== 'Direct' ? 'https://' . $record['url'] : null, shouldOpenInNewTab: true),

                Tables\Columns\TextColumn::make('pageViews')
                    ->label('Page Views')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state))
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
