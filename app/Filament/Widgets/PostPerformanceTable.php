<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PostPerformanceTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()
                    ->where('status', 'published')
                    ->withCount([
                        'analytics as total_views',
                        'analytics as unique_views' => function (Builder $query) {
                            $query->where('is_unique_visitor', true);
                        }
                    ])
                    ->orderByDesc('total_views')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->url(fn (Post $record): string => route('blog.show', $record->slug), shouldOpenInNewTab: true)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('total_views')
                    ->label('Total Views')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0))
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('unique_views')
                    ->label('Unique Views')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state ?? 0))
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->separator(',')
                    ->limit(2),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->date()
                    ->sortable(),
            ])
            ->heading('Top Performing Posts')
            ->description('Your most viewed blog posts');
    }
}
