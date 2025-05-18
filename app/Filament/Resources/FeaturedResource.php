<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeaturedResource\Pages;
use App\Filament\Resources\FeaturedResource\RelationManagers;
use App\Models\Featured;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FeaturedResource\RelationManagers\BulletPointsRelationManager;
class FeaturedResource extends Resource
{
    protected static ?string $model = Featured::class;
    protected static ?string $navigationGroup = 'Featured';
    protected static ?string $navigationGroupIcon = 'heroicon-s-document-text';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Featured';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('featured/images')
                    ->preserveFilenames()
                    ->image()
                    ->imageEditor()
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                    ->previewable()
                    ->loadingIndicatorPosition('right')
                    ->panelLayout('integrated'),
                Forms\Components\TextInput::make('link')
                    ->label('Link')
                    ->url(),
                Forms\Components\Toggle::make('isActive')
                    ->label('Is Active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('description')->label('Description')->limit(50),
                Tables\Columns\ImageColumn::make('image')->label('Image'),
                Tables\Columns\TextColumn::make('link')->label('Link'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFeatureds::route('/'),
            'create' => Pages\CreateFeatured::route('/create'),
            'edit' => Pages\EditFeatured::route('/{record}/edit'),
        ];
    }

    public static function relations(): array
{
    return [
        BulletPointsRelationManager::class,
    ];
}
}
