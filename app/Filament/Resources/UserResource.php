<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Admin';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make(fn (string $operation) => $operation === 'edit' ? 'Edit User' : 'Create New User')
                            ->description(fn (string $operation) => $operation === 'edit' 
                            ? 'You are editing an existing record' 
                            : 'Please fill out all required fields')
                            ->schema([
                                TextInput::make('name'),
                                TextInput::make('phone'),
                                TextInput::make('email'),
                                TextInput::make('social'),
                                TextInput::make('password')
                                ->password()
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                ->dehydrated(fn ($state) => filled($state))
                                ->hiddenOn('edit'),
                                TextInput::make('password_confirmation')
                                ->hiddenOn('edit'),
                                Forms\Components\MarkdownEditor::make('bio')
                                    ->columnSpan('full')
                                ->helperText('Brief Bio of User for SEO'),

                            ])->columns(2)
                    ]),

                Group::make()
                    ->schema([
                        Section::make('Image Upload')
                            ->description('Upload photo User')
                            ->schema([
                                FileUpload::make('image_url')
                                    ->directory('form-attachments')
                                    ->preserveFilenames()
                                    ->image()
                                    ->imageEditor(),
                                TextInput::make('alt Text')
                                ->helperText('Enter the name of user'),
                                TextInput::make('address'),
                                TextInput::make('city'),
                                TextInput::make('state'),
                                TextInput::make('country'),
                                TextInput::make('zip Code'),
                                Forms\Components\Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                            ])->columns(2)
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                ->label('Photo')
                ->circular() // optional: makes it round
                ->size(50),   // optional: controls image size
                TextColumn::make('name'),
                TextColumn::make('phone'),
                TextColumn::make('email'),
            ])->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
