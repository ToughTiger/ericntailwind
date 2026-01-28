<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowQualityPostsWidget extends BaseWidget
{
    protected static ?string $heading = 'Posts Needing Improvement';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()
                    ->where('status', 'published')
                    ->whereNotNull('quality_score')
                    ->where(function($query) {
                        $query->where('quality_score', '<', 70)
                              ->orWhere('quality_grade', 'F')
                              ->orWhere('quality_grade', 'D');
                    })
                    ->orderBy('quality_score', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record)),
                    
                Tables\Columns\TextColumn::make('quality_grade')
                    ->label('Grade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        'D' => 'warning',
                        'F' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('quality_score')
                    ->label('Quality')
                    ->sortable()
                    ->suffix('%')
                    ->color(fn (float $state): string => $state >= 70 ? 'success' : ($state >= 60 ? 'warning' : 'danger')),
                    
                Tables\Columns\TextColumn::make('readability_score')
                    ->label('Readability')
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => number_format($state, 1)),
                    
                Tables\Columns\TextColumn::make('word_count')
                    ->label('Words')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state)),
                    
                Tables\Columns\TextColumn::make('reading_time')
                    ->label('Read Time')
                    ->sortable()
                    ->suffix(' min'),
                    
                Tables\Columns\IconColumn::make('meta_description')
                    ->label('Meta')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn (Post $record): bool => !empty($record->meta_description)),
                    
                Tables\Columns\IconColumn::make('keywords')
                    ->label('Keywords')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn (Post $record): bool => !empty($record->keywords)),
            ])
            ->actions([
                Tables\Actions\Action::make('improve')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => route('filament.admin.resources.posts.edit', $record))
                    ->color('warning'),
            ]);
    }
}
