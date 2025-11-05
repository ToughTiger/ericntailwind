<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkedInConnectionResource\Pages;
use App\Filament\Resources\LinkedInConnectionResource\RelationManagers;
use App\Models\LinkedInConnection;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinkedInConnectionResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Linkedin';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('name')
    //             ->label('Name')
    //             ->required()
    //             ->maxLength(255),
    //             Forms\Components\TextInput::make('linkedin_name')
    //                 ->label('LinkedIn Name')
    //                 ->maxLength(255),
    //             Forms\Components\Toggle::make('has_linkedin')
    //                 // ->label('Connected to LinkedIn')
    //                 // ->disabled()
    //                 // ->dehydrated(false)
    //                 ->afterStateHydrated(function ($component, $record) {
    //                     $component->state(! empty($record->linkedin_access_token));
    //                 }),
    //             Forms\Components\TextInput::make('linkedin_urn')
    //                 ->label('LinkedIn URN')
    //                 ->disabled()
    //                 ->dehydrated(false),
    //         ]);
    // }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Example: allow editing of name and LinkedIn display name
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('linkedin_name')
                ->label('LinkedIn Name')
                ->maxLength(255), // <-- remove ->disabled()

            // Show connection state, but don't save it
            Forms\Components\Toggle::make('has_linkedin')
                ->label('Connected to LinkedIn')
                ->disabled()           // readonly in UI
                ->dehydrated(false)    // don't save to DB
                ->afterStateHydrated(function ($component, $record) {
                    $component->state(! empty($record->linkedin_access_token));
                }),

            // (Optional) show URN/read-only fields
            Forms\Components\TextInput::make('linkedin_urn')
                ->label('LinkedIn URN')
                ->disabled()
                ->dehydrated(false),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('linkedin_name')
                    ->label('LinkedIn Name'),
                Tables\Columns\IconColumn::make('has_linkedin')
                    ->label('Connected')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->linkedin_access_token !== null),
                Tables\Columns\TextColumn::make('linkedin_token_expires_at')
                    ->label('Token Expires')
                    ->dateTime()
                    ->since()             // shows “in 2 days” etc.
                    ->sortable(),
                     ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('connect')
                    ->label(fn ($record) => $record->linkedin_access_token ? 'Reconnect' : 'Connect')
                    ->url(fn ($record) => route('linkedin.auth', ['user' => $record->id]))
                    ->openUrlInNewTab()
                    ->extraAttributes([
                        // ensure Livewire navigate never hijacks this link
                        'wire:navigate' => 'false',
                        'data-navigate' => 'false',
                        'data-turbo'    => 'false',
                        'onclick'       => "window.location.href=this.href; return false;", // hard fallback
                        'rel'           => 'noopener external',
                    ]),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLinkedInConnections::route('/'),
        ];
    }
}
