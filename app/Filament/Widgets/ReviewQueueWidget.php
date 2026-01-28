<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ReviewQueueWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Posts Pending Review')
            ->query(
                Post::query()
                    ->pendingReview()
                    ->with(['author', 'reviewer'])
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
                
                Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Assigned To')
                    ->searchable()
                    ->default('Unassigned')
                    ->color(fn ($state) => $state === 'Unassigned' ? 'warning' : 'gray'),
                
                Tables\Columns\TextColumn::make('submitted_for_review_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable(),
                
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
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('No posts pending review')
            ->emptyStateDescription('All caught up! 🎉')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
