<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ScheduledPostsWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()
                    ->pendingScheduled()
                    ->orderBy('scheduled_at', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50)
                    ->url(fn (Post $record) => route('filament.admin.resources.posts.edit', $record), shouldOpenInNewTab: false)
                    ->color('primary'),

                Tables\Columns\TextColumn::make('author.name')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled For')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clock'),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Time Until')
                    ->formatStateUsing(fn ($state) => $state->diffForHumans())
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\Action::make('publish_now')
                    ->label('Publish Now')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Post $record) {
                        $record->publish();
                        $this->dispatch('$refresh');
                    }),
            ])
            ->heading('Upcoming Scheduled Posts')
            ->description('Posts scheduled to publish automatically');
    }
}
