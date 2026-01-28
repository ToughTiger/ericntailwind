<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyReviewsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Assigned Reviews')
            ->query(
                Post::query()
                    ->assignedToMe()
                    ->pendingReview()
                    ->with(['author'])
                    ->latest('submitted_for_review_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record)),
                
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('submitted_for_review_at')
                    ->label('Waiting')
                    ->since()
                    ->sortable()
                    ->color(fn ($state) => $state?->diffInHours(now()) > 24 ? 'danger' : 'gray'),
                
                Tables\Columns\TextColumn::make('quality_score')
                    ->label('Quality')
                    ->numeric(1)
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('word_count')
                    ->label('Words')
                    ->numeric()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review Now')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record)),
            ])
            ->emptyStateHeading('No reviews assigned')
            ->emptyStateDescription('You have no pending reviews at the moment.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
