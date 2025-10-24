<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkedInPostResource\Pages;
use App\Filament\Resources\LinkedInPostResource\RelationManagers;
use App\Models\LinkedInPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class LinkedInPostResource extends Resource
{
    protected static ?string $model = LinkedInPost::class;
    protected static ?string $navigationGroup = 'Linkedin';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\FileUpload::make('media_path')
                    ->disk('public')
                    ->label('Media File')
                    ->directory('linkedin-media')
                    ->preserveFilenames()
                    ->maxSize(512000) // 500MB for videos
                    ->acceptedFileTypes([
                        'image/jpeg',
                        'image/png',
                        'video/mp4',
                    ]),
                Forms\Components\Select::make('media_type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                        'article' => 'Article',
                    ])
                    ->reactive(),
                Forms\Components\DateTimePicker::make('scheduled_for')
                    ->seconds(false),
                Forms\Components\Toggle::make('publish_now')
                    ->label('Publish Immediately')
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        $set('scheduled_for', $state ? now() : null);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->content),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('scheduled_for')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
    ->label('Publish Now')
    ->action(function ($record) {
        try {
            app(LinkedInService::class)->postContent(
                $record->user,         // or auth()->user()
                $record->content,
                $record->media_path,
                $record->media_type
            );

            $record->status = 'published';
            $record->last_error = null; // add this column on posts if you have it
            $record->save();

            Notification::make()
                ->title('Published to LinkedIn')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            // DO NOT rethrow — keep UI stable
            $record->status = 'failed';
            $record->last_error = $e->getMessage();
            $record->save();

            // store on user too if you want
            optional($record->user)->update(['linkedin_error_message' => $e->getMessage()]);

            Notification::make()
                ->title('LinkedIn publish failed')
                ->body(Str::limit($e->getMessage(), 300))
                ->danger()
                ->send();
        }
    })
    ->visible(fn ($record) => $record->status !== 'published'),
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
            'index' => Pages\ListLinkedInPosts::route('/'),
            'create' => Pages\CreateLinkedInPost::route('/create'),
            'edit' => Pages\EditLinkedInPost::route('/{record}/edit'),
        ];
    }
}
