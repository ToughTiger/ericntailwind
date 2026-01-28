<?php

namespace App\Filament\Widgets;

use App\Models\PostVersion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentVersionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Post Revisions';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PostVersion::query()
                    ->with(['post', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Post')
                    ->limit(40)
                    ->searchable()
                    ->url(fn (PostVersion $record): string => 
                        route('filament.admin.resources.posts.edit', $record->post_id)),
                    
                Tables\Columns\TextColumn::make('version_label')
                    ->label('Version')
                    ->badge()
                    ->color(fn (PostVersion $record): string => 
                        $record->is_major_change ? 'warning' : 'gray'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->default('System')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('change_summary')
                    ->label('Changes')
                    ->limit(50)
                    ->default('No summary'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (PostVersion $record): string => 
                        route('filament.admin.resources.posts.edit', $record->post_id))
                    ->color('info'),
            ]);
    }
}
