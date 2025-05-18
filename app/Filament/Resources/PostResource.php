<?php

namespace App\Filament\Resources;



use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Carbon\CarbonImmutable;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Livewire\TemporaryUploadedFile;
use OpenAI\Laravel\Facades\OpenAI;
use Santosh\FilamentAiTools\Forms\Components\AiContentGenerator;
use Santosh\FilamentAiTools\Http\Livewire\ContentGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostResource extends Resource
{

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make("Create Post")
                            ->description("Create a new post here.")
                            ->schema([ //
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(55)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, string $state, Forms\Set $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                TextInput::make('slug')->unique(ignoreRecord: true)
                                    ->minLength(1)
                                    ->maxLength(55)
                                    ->readOnly(),

                                AiContentGenerator::make('content-generator')
                                    ->targetField('content')
                                    ->columnSpanFull(),
                                MarkdownEditor::make('content')
                                    ->columnSpan('full')
                                    ->required()
                                    ->hiddenLabel()
                                    ->reactive()
                                    ->extraAttributes(['class' => 'prose max-w-none']),                        
                            ])->columns(2),
                        Section::make("Meta")
                            ->description('SEO Metadata')
                            ->Schema([
                                TextInput::make('keywords')
                                    ->helperText("Enter keywords that capture the blog's essence"),
                                TextInput::make('meta_description')
                                    ->helperText("Enter description about the blog's essence"),
                                Select::make('Category')
                                    ->relationship('categories', 'name')
                                    ->preload()
                                    ->multiple()
                                    ->searchable(),
                                Select::make("Tags")
                                    ->relationship('tags', 'name')
                                    ->preload(true)
                                    ->multiple()
                                    ->searchable(),
                            ])->collapsible()->columns(2),

                    ])->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make("Admin")
                            ->schema([
                                Toggle::make('is_published')->label('Publish')
                                    ->helperText('Do you want to publish this Post?')
                                    ->visible(fn() => auth()->user = "super_user"),
                                Toggle::make('is_featured')->label('Featured')
                                    ->helperText('Do you want mark this as featured Post?')
                                    ->visible(fn() => auth()->user = "super_user"),
                                Toggle::make('is_verified')->label('Verified')
                                    ->visible(fn() => auth()->user = "super_user"),
                                Forms\Components\DatePicker::make('created_at')
                                    ->label('Created At')
                                    ->minDate(CarbonImmutable::now()->startOfDay())
                                    ->native()
                                    ->default(now())
                                    ->required()
                                    ->visible(fn() => auth()->user = "super_user"),
                            ]),
                        Section::make("Image Upload")
                            ->description('Upload an image for images and set Alt tag')
                            ->Schema([
                                FileUpload::make('image')
                                    ->disk('public')
                                    ->directory('form-attachments')
                                    ->preserveFilenames()
                                    ->image()
                                    ->imageEditor()
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                    ->previewable()
                                    ->loadingIndicatorPosition('right')
                                    ->panelLayout('integrated'),

                                TextInput::make('alt'),

                            ])->collapsible(),

                    ]),


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published')
                    ->searchable()
                    ->sortable()->toggleable(),
                IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean()
                    ->searchable()
                    ->sortable()->toggleable(),
                TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),


            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueLabel('Only Featured Post')
                    ->falseLabel('Only Drafted Post')
                    ->native(false)
            ])
            ->actions(
                [
                    Tables\Actions\EditAction::make(),
                ]
            )
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
    //    public static function getEloquentQuery(): Builder
    //    {
    ////        dd(auth()->user()->is_admin);
    ////        return parent::getEloquentQuery()->where('author_id', Auth::id() );
    //    }
}
