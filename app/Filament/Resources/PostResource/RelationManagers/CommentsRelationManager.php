<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->rows(4)
                    ->helperText('Use @username to mention someone'),
                
                Forms\Components\Select::make('type')
                    ->label('Comment Type')
                    ->options([
                        'comment' => 'General Comment',
                        'review' => 'Review Feedback',
                        'approval' => 'Approval Note',
                        'rejection' => 'Rejection Note',
                    ])
                    ->default('comment')
                    ->required(),
                
                Forms\Components\Toggle::make('is_internal')
                    ->label('Internal Only')
                    ->helperText('Only visible to admin users')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'comment' => 'gray',
                        'review' => 'info',
                        'approval' => 'success',
                        'rejection' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'comment' => 'Comment',
                        'review' => 'Review',
                        'approval' => 'Approval',
                        'rejection' => 'Rejection',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_internal')
                    ->label('Internal')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-globe-alt')
                    ->trueColor('warning')
                    ->falseColor('success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Posted At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'comment' => 'Comment',
                        'review' => 'Review',
                        'approval' => 'Approval',
                        'rejection' => 'Rejection',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_internal')
                    ->label('Internal Only')
                    ->trueLabel('Internal Comments')
                    ->falseLabel('Public Comments')
                    ->native(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        
                        // Extract mentions from comment
                        preg_match_all('/@(\w+)/', $data['comment'], $matches);
                        if (!empty($matches[1])) {
                            $data['mentioned_users'] = $matches[1];
                        }
                        
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
