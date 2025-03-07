<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BulletPointResource\Pages;
use App\Filament\Resources\BulletPointResource\RelationManagers;
use App\Models\BulletPoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BulletPointResource extends Resource
{
    protected static ?string $model = BulletPoint::class;
    protected static ?string $navigationGroup = 'Featured';
    protected static ?string $navigationGroupIcon = 'heroicon-s-document-text';
    protected static ?string $navigationIcon = 'heroicon-o-stop-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('featured_id')
                ->label('Featured')
                ->options(\App\Models\Featured::pluck('title', 'id'))
                ->required(),
                Forms\Components\TextInput::make('heading')
                ->label('Heading for Bullet Point')
                ->required(),
            Forms\Components\Textarea::make('text')
                ->label('Bullet Point Text')
                ->required(),
            Forms\Components\TextInput::make('order')
                ->label('Order')
                ->numeric()
                ->default(0),
                Forms\Components\ColorPicker::make('icon_color'),
                Forms\Components\ColorPicker::make('heading_color'),
                Forms\Components\ColorPicker::make('link_color'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title')->label('Post'),
                Tables\Columns\TextColumn::make('text')->label('Text'),
                Tables\Columns\TextColumn::make('order')->label('Order'),
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
            'index' => Pages\ListBulletPoints::route('/'),
            'create' => Pages\CreateBulletPoint::route('/create'),
            'edit' => Pages\EditBulletPoint::route('/{record}/edit'),
        ];
    }
}
