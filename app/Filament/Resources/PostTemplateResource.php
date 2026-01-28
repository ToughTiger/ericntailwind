<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostTemplateResource\Pages;
use App\Filament\Resources\PostTemplateResource\RelationManagers;
use App\Models\PostTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostTemplateResource extends Resource
{
    protected static ?string $model = PostTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationGroup = 'Blog';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $navigationLabel = 'Templates';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Details')
                    ->description('Basic template information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => 
                                $set('slug', Str::slug($state))
                            ),
                            
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated(),
                            
                        Forms\Components\Textarea::make('description')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                            
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-document-text')
                                    ->helperText('Heroicon name'),
                                    
                                Forms\Components\ColorPicker::make('color')
                                    ->helperText('Template badge color'),
                                    
                                Forms\Components\TextInput::make('usage_count')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Template Content')
                    ->description('Default content and structure')
                    ->schema([
                        Forms\Components\TextInput::make('default_title_pattern')
                            ->label('Title Pattern')
                            ->placeholder('[Topic]: A Complete Guide')
                            ->helperText('Use placeholders like [Topic], [Date], etc.')
                            ->maxLength(255),
                            
                        Forms\Components\MarkdownEditor::make('content_template')
                            ->label('Content Template')
                            ->helperText('Default post structure with placeholders')
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('default_excerpt')
                            ->label('Default Excerpt')
                            ->rows(2)
                            ->maxLength(500),
                            
                        Forms\Components\Textarea::make('default_meta_description')
                            ->label('Meta Description Template')
                            ->rows(2)
                            ->maxLength(160),
                            
                        Forms\Components\TextInput::make('default_keywords')
                            ->label('Default Keywords')
                            ->placeholder('tutorial, guide, how-to'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active templates appear in selection'),
                            
                        Forms\Components\Toggle::make('is_default')
                            ->label('Default Template')
                            ->helperText('Automatically apply to new posts')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Unset other default templates
                                    PostTemplate::where('is_default', true)->update(['is_default' => false]);
                                }
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->icon(fn (PostTemplate $record) => $record->icon ?? 'heroicon-o-document-text')
                    ->color(fn (PostTemplate $record) => $record->color),
                    
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('usage_count')
                    ->label('Used')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('usage_count', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Only')
                    ->boolean(),
                    
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Default Template')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->color('success'),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->color('danger'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostTemplates::route('/'),
            'create' => Pages\CreatePostTemplate::route('/create'),
            'edit' => Pages\EditPostTemplate::route('/{record}/edit'),
        ];
    }
}
