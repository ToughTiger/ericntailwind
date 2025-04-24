<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Illuminate\Support\Collection;
use Spatie\Analytics\Period;
use Spatie\Analytics\Facades\Analytics;

class TopPagesTable extends TableWidget
{
    protected static ?string $heading = 'Most Visited Pages';
    // protected int $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(function (): Collection {
                $pages = Analytics::fetchMostVisitedPages(Period::days(30));
                return new Collection($pages);
            })
            ->columns([
                Tables\Columns\TextColumn::make('pageTitle')
                    ->label('Page')
                    ->formatStateUsing(fn ($state, $record) => $state ?: $record['pagePath'])
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('screenPageViews')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn ($record) => $record['pagePath'])
                    ->icon('heroicon-o-external-link')
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No pages visited yet')
            ->emptyStateIcon('heroicon-o-document-magnifying-glass');
    }
}