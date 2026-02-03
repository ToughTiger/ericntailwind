<?php

namespace App\Filament\Resources;


use Filament\Forms\Components\View;
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
                                Forms\Components\Select::make('template_id')
                                    ->label('Use Template')
                                    ->placeholder('Select a template (optional)')
                                    ->options(\App\Models\PostTemplate::active()->pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?int $state) {
                                        if ($state) {
                                            $template = \App\Models\PostTemplate::find($state);
                                            if ($template) {
                                                if ($template->content_template) {
                                                    $set('content', $template->content_template);
                                                }
                                                if ($template->default_meta_description) {
                                                    $set('meta_description', $template->default_meta_description);
                                                }
                                                if ($template->default_keywords) {
                                                    $set('keywords', $template->default_keywords);
                                                }
                                            }
                                        }
                                    })
                                    ->helperText('Apply a template to start with pre-filled content')
                                    ->columnSpanFull()
                                    ->visible(fn (string $operation): bool => $operation === 'create'),
                                    
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

                                View::make('filament.components.anthropic-generator')
                                    ->columnSpanFull(),
                                MarkdownEditor::make('content')
                                ->columnSpan('full')
                                ->required()
                                ->hiddenLabel()
                                ->reactive()
                                ->extraAttributes(['class' => 'prose max-w-none']),
                                                        
                            ])->columns(2),
                        Section::make("Meta & SEO")
                            ->description('SEO Metadata - Use AI to generate')
                            ->schema([
                                TextInput::make('keywords')
                                    ->helperText("Enter keywords or click 'Generate' to use AI")
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateKeywords')
                                            ->icon('heroicon-m-sparkles')
                                            ->action(function (Forms\Set $set, Forms\Get $get) {
                                                $aiService = app(\App\Services\AIContentService::class);
                                                $keywords = $aiService->generateKeywords(
                                                    $get('title') ?? '',
                                                    $get('content') ?? ''
                                                );
                                                $set('keywords', $keywords);
                                            })
                                    ),
                                    
                                Textarea::make('meta_description')
                                    ->helperText("Enter description or click 'Generate' button below")
                                    ->rows(3)
                                    ->maxLength(160),
                                    
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('generateMeta')
                                        ->label('Generate Meta Description')
                                        ->icon('heroicon-m-sparkles')
                                        ->action(function (Forms\Set $set, Forms\Get $get) {
                                            $aiService = app(\App\Services\AIContentService::class);
                                            $metaDesc = $aiService->generateMetaDescription(
                                                $get('title') ?? '',
                                                $get('content') ?? ''
                                            );
                                            $set('meta_description', $metaDesc);
                                        })
                                        ->color('primary'),
                                ]),
                                    
                                Select::make('Category')
                                    ->label('Campaign')
                                    ->relationship('categories', 'name')
                                    ->preload()
                                    ->multiple()
                                    ->searchable()
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('suggestCategories')
                                            ->icon('heroicon-m-sparkles')
                                            ->tooltip('AI Suggest Categories')
                                            ->action(function (Forms\Set $set, Forms\Get $get) {
                                                $smartService = app(\App\Services\SmartContentService::class);
                                                $suggestions = $smartService->suggestCategories(
                                                    $get('content') ?? '',
                                                    $get('title') ?? ''
                                                );
                                                
                                                if (!empty($suggestions)) {
                                                    $categoryIds = \App\Models\Category::whereIn('name', $suggestions)->pluck('id')->toArray();
                                                    $set('Category', $categoryIds);
                                                }
                                            })
                                    ),
                                    
                                Select::make("Tags")
                                    ->relationship('tags', 'name')
                                    ->preload(true)
                                    ->multiple()
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->unique(),
                                    ])
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('suggestTags')
                                            ->icon('heroicon-m-sparkles')
                                            ->tooltip('AI Suggest Tags')
                                            ->action(function (Forms\Set $set, Forms\Get $get) {
                                                $smartService = app(\App\Services\SmartContentService::class);
                                                $suggestions = $smartService->suggestTags(
                                                    $get('content') ?? '',
                                                    $get('title') ?? '',
                                                    10
                                                );
                                                
                                                if (!empty($suggestions)) {
                                                    // Find or create tags
                                                    $tagIds = [];
                                                    foreach ($suggestions as $tagName) {
                                                        $tag = \App\Models\Tag::firstOrCreate(
                                                            ['name' => $tagName],
                                                            ['slug' => \Illuminate\Support\Str::slug($tagName)]
                                                        );
                                                        $tagIds[] = $tag->id;
                                                    }
                                                    $set('Tags', $tagIds);
                                                }
                                            })
                                    ),
                            ])->collapsible()->columns(2),
                        
                        Section::make("Content Quality")
                            ->description('AI-powered content analysis')
                            ->schema([
                                Forms\Components\Placeholder::make('quality_metrics')
                                    ->label('')
                                    ->content(function ($record) {
                                        if (!$record || !$record->content) {
                                            return 'Save post to see quality metrics';
                                        }
                                        
                                        $analysisService = app(\App\Services\ContentAnalysisService::class);
                                        $readability = $analysisService->calculateReadabilityScore($record->content);
                                        $quality = $analysisService->calculateQualityScore($record->content, $record->toArray());
                                        $readingTime = $analysisService->calculateReadingTime($record->content);
                                        
                                        return view('filament.components.quality-metrics', [
                                            'readability' => $readability,
                                            'quality' => $quality,
                                            'readingTime' => $readingTime,
                                        ]);
                                    })
                                    ->columnSpanFull(),
                            ])->collapsible()->collapsed(),

                    ])->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make("Publishing")
                            ->description('Control when and how this post is published')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'review' => 'In Review',
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                        'scheduled' => 'Scheduled',
                                        'published' => 'Published',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state === 'published') {
                                            $set('is_published', true);
                                            $set('published_at', now());
                                        } elseif ($state === 'draft') {
                                            $set('is_published', false);
                                        }
                                    }),
                                
                                Forms\Components\Select::make('reviewer_id')
                                    ->label('Assign Reviewer')
                                    ->relationship('reviewer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get) => in_array($get('status'), ['review', 'approved', 'rejected']))
                                    ->helperText('Assign someone to review this post'),

                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('Schedule For')
                                    ->helperText('Set a future date/time to publish this post automatically')
                                    ->native(false)
                                    ->seconds(false)
                                    ->minDate(now())
                                    ->visible(fn (Forms\Get $get) => $get('status') === 'scheduled')
                                    ->required(fn (Forms\Get $get) => $get('status') === 'scheduled'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Published At')
                                    ->native(false)
                                    ->seconds(false)
                                    ->visible(fn (Forms\Get $get) => $get('status') === 'published'),
                                
                                Forms\Components\Textarea::make('rejection_reason')
                                    ->label('Rejection Reason')
                                    ->rows(3)
                                    ->visible(fn (Forms\Get $get) => $get('status') === 'rejected')
                                    ->disabled()
                                    ->dehydrated(false),

                                Toggle::make('is_featured')->label('Featured')
                                    ->helperText('Mark this as a featured post')
                                    ->visible(fn() => auth()->user = "super_user"),
                                    
                                Toggle::make('is_verified')->label('Verified')
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
                    ->sortable()
                    ->limit(50),
                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'review' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'scheduled' => 'warning',
                        'published' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'draft' => 'heroicon-o-pencil',
                        'review' => 'heroicon-o-eye',
                        'approved' => 'heroicon-o-check-badge',
                        'rejected' => 'heroicon-o-x-circle',
                        'scheduled' => 'heroicon-o-clock',
                        'published' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-document',
                    })
                    ->sortable(),
                TextColumn::make('quality_grade')
                    ->label('Quality')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        'D' => 'warning',
                        'F' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('word_count')
                    ->label('Words')
                    ->formatStateUsing(fn (?int $state): string => $state ? number_format($state) : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('reading_time')
                    ->label('Read')
                    ->suffix(' min')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->dateTime()
                    ->sortable()
                    ->toggleable()
                    ->visible(fn ($record) => $record?->status === 'scheduled'),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
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
                    ->native(false),
                Tables\Filters\SelectFilter::make('quality_grade')
                    ->label('Quality Grade')
                    ->options([
                        'A' => 'A - Excellent',
                        'B' => 'B - Good',
                        'C' => 'C - Fair',
                        'D' => 'D - Poor',
                        'F' => 'F - Needs Work',
                    ])
                    ->multiple(),
            ])
            ->actions(
                [
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('clone')
                        ->label('Clone')
                        ->icon('heroicon-m-document-duplicate')
                        ->color('gray')
                        ->action(function (Post $record) {
                            $newPost = $record->replicate();
                            $newPost->title = $record->title . ' (Copy)';
                            $newPost->slug = Str::slug($newPost->title) . '-' . time();
                            $newPost->status = 'draft';
                            $newPost->is_published = false;
                            $newPost->published_at = null;
                            $newPost->scheduled_at = null;
                            $newPost->author_id = auth()->id();
                            $newPost->save();
                            
                            // Copy relationships
                            $newPost->categories()->sync($record->categories->pluck('id'));
                            $newPost->tags()->sync($record->tags->pluck('id'));
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Post cloned successfully')
                                ->success()
                                ->send();
                                
                            return redirect()->route('filament.admin.resources.posts.edit', $newPost);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Clone this post?')
                        ->modalDescription('This will create a copy as a draft with all content and categories/tags.'),
                    
                    Tables\Actions\Action::make('submitForReview')
                        ->label('Submit for Review')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->visible(fn (Post $record) => $record->status === 'draft')
                        ->form([
                            Forms\Components\Select::make('reviewer_id')
                                ->label('Assign Reviewer')
                                ->relationship('reviewer', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function (Post $record, array $data) {
                            $record->submitForReview($data['reviewer_id']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Post submitted for review')
                                ->success()
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (Post $record) => $record->status === 'review')
                        ->form([
                            Forms\Components\Textarea::make('comment')
                                ->label('Approval Comment (Optional)')
                                ->rows(3),
                        ])
                        ->action(function (Post $record, array $data) {
                            $record->approve($data['comment'] ?? null);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Post approved')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Post $record) => $record->status === 'review')
                        ->form([
                            Forms\Components\Textarea::make('reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3),
                            Forms\Components\Textarea::make('comment')
                                ->label('Additional Feedback (Optional)')
                                ->rows(3),
                        ])
                        ->action(function (Post $record, array $data) {
                            $record->reject($data['reason'], $data['comment'] ?? null);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Post rejected')
                                ->body('Feedback has been sent to the author')
                                ->warning()
                                ->send();
                        })
                        ->requiresConfirmation(),
                    
                    Tables\Actions\Action::make('recalculate_quality')
                        ->label('Recalculate')
                        ->icon('heroicon-m-arrow-path')
                        ->action(fn (Post $record) => $record->updateQualityMetrics())
                        ->requiresConfirmation()
                        ->color('info')
                        ->visible(fn (Post $record) => $record->content),
                ]
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'published',
                                    'is_published' => true,
                                    'published_at' => now(),
                                ]);
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->color('success'),
                    
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish')
                        ->icon('heroicon-o-x-circle')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update([
                                    'status' => 'draft',
                                    'is_published' => false,
                                ]);
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->color('warning'),
                    
                    Tables\Actions\BulkAction::make('assign_categories')
                        ->label('Assign Categories')
                        ->icon('heroicon-o-folder')
                        ->form([
                            Forms\Components\Select::make('categories')
                                ->label('Categories')
                                ->options(\App\Models\Category::pluck('name', 'id'))
                                ->multiple()
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->categories()->sync($data['categories']);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('info'),
                    
                    Tables\Actions\BulkAction::make('assign_tags')
                        ->label('Assign Tags')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('tags')
                                ->label('Tags')
                                ->options(\App\Models\Tag::pluck('name', 'id'))
                                ->multiple()
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->tags()->sync($data['tags']);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->color('info'),
                    
                    Tables\Actions\BulkAction::make('recalculate_quality')
                        ->label('Recalculate Quality')
                        ->icon('heroicon-m-arrow-path')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->updateQualityMetrics();
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion()
                        ->color('info'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VersionsRelationManager::class,
            RelationManagers\CommentsRelationManager::class,
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
