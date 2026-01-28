<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\PostVersion;
use App\Services\VersionControlService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';
    
    protected static ?string $title = 'Version History';
    
    protected static ?string $recordTitleAttribute = 'version_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('version_number')
                            ->label('Version')
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('user.name')
                            ->label('Author')
                            ->disabled(),
                    ]),
                    
                Forms\Components\TextInput::make('title')
                    ->disabled()
                    ->columnSpanFull(),
                    
                Forms\Components\Textarea::make('change_summary')
                    ->disabled()
                    ->rows(2)
                    ->columnSpanFull(),
                    
                Forms\Components\MarkdownEditor::make('content')
                    ->disabled()
                    ->columnSpanFull(),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Textarea::make('meta_description')
                            ->disabled()
                            ->rows(2),
                            
                        Forms\Components\TextInput::make('keywords')
                            ->disabled(),
                    ]),
                    
                Forms\Components\Placeholder::make('created_at')
                    ->label('Created At')
                    ->content(fn (PostVersion $record): string => $record->created_at->format('M d, Y H:i:s')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version_number')
            ->defaultSort('version_number', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('version_number')
                    ->label('Version')
                    ->badge()
                    ->color(fn (PostVersion $record): string => 
                        $record->is_major_change ? 'warning' : 'info')
                    ->formatStateUsing(fn (int $state, PostVersion $record): string => 
                        "v{$state}" . ($record->is_major_change ? ' (Major)' : ''))
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->default('System')
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('change_summary')
                    ->label('Changes')
                    ->limit(60)
                    ->default('No summary')
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('formatted_changes')
                    ->label('Fields Modified')
                    ->limit(40)
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_major_change')
                    ->label('Major')
                    ->boolean()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_major_change')
                    ->label('Major Changes Only')
                    ->boolean(),
            ])
            ->headerActions([
                // No create action - versions are auto-created
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalWidth('5xl'),
                    
                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->requiresConfirmation()
                    ->modalHeading('Restore this version?')
                    ->modalDescription('This will restore the post to this version and create a new version with current content.')
                    ->action(function (PostVersion $record) {
                        $versionService = app(VersionControlService::class);
                        $versionService->restoreVersion($record->post, $record);
                        
                        Notification::make()
                            ->title('Version restored successfully')
                            ->success()
                            ->send();
                    })
                    ->color('warning'),
                    
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (PostVersion $record): bool => !$record->is_major_change),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $deleted = 0;
                            foreach ($records as $record) {
                                if (!$record->is_major_change) {
                                    $record->delete();
                                    $deleted++;
                                }
                            }
                            
                            Notification::make()
                                ->title("Deleted {$deleted} version(s)")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}
