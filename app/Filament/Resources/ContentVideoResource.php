<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentVideoResource\Widgets\ContentVideoOverview;
use App\Filament\Resources\ContentVideoResource\Pages;
use App\Filament\Resources\ContentVideoResource\RelationManagers;
use App\Models\ContentVideo;
use App\Notifications\VideoStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use App\Filament\Filters\CategoryFilter;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Textarea;
use Illuminate\Validation\Rule;
use Filament\Notifications\Notification;



class ContentVideoResource extends Resource
{
    protected static ?string $model = ContentVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Video Contents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tag')
                    ->maxLength(255),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('categories')
                    ->relationship('categoryContents.category', 'category_name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('category_name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('category_description')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('category_image')
                            ->image()
                            ->directory('category_images')
                            ->disk('public'),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $category = \App\Models\Category::create([
                            'category_name' => $data['category_name'],
                            'slug' => $data['slug'],
                            'category_description' => $data['category_description'] ?? null,
                            'category_image' => $data['category_image'] ?? null,
                        ]);
                        return $category->id;
                    })
                    ->required(),
                Forms\Components\FileUpload::make('video_url')
                    ->directory('video_content')
                    ->resize(50)
                    ->disk('public')
                    ->nullable()
                    ->hint('Max file size: 20MB. Allowed formats: MP4, MOV, AVI.') // Helper text
                    ->hintIcon('heroicon-o-information-circle') // Optional icon
                    ->hintColor('warning') // Optional color (danger, warning, success, etc.)
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo']) // MIME types
                    ->requiredWithout('video_url,link_youtube')
                    ->maxSize(20000),
                Forms\Components\TextInput::make('link_youtube')
                    ->nullable(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->optimize('webp')
                    ->hint('Max file size: 10MB. Allowed formats: JPG, JPEG, PNG, HEIC.') // Helper text
                    ->hintIcon('heroicon-o-information-circle') // Optional icon
                    ->hintColor('warning') // Optional color (danger, warning, success, etc.)
                    ->directory('thumbnail_video')
                    ->resize(50)
                    ->disk('public')
                    ->maxSize(10000),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('source')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('tag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_youtube'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('categoryContents.category.category_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_comments') // Accessor
                    ->label('Total Comments')
                    ->sortable(query: function (Builder $query, string $direction) {
                        // Urutkan berdasarkan jumlah komentar
                        $query->withCount('userComments')->orderBy('user_comments_count', $direction);
                    }),
                Tables\Columns\TextColumn::make('total_reactions') // Accessor
                    ->label('Total Likes')
                    ->sortable(query: function (Builder $query, string $direction) {
                        // Urutkan berdasarkan jumlah komentar
                        $query->withCount('contentReactions')->orderBy('content_reactions_count', $direction);
                    }),
                Tables\Columns\TextColumn::make('total_views')
                    ->limit(length: 20)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_favorites')
                    ->limit(length: 20)
                    ->sortable(query: function (Builder $query, string $direction) {
                        // Urutkan berdasarkan jumlah komentar
                        $query->withCount('userFavorite')->orderBy('user_favorite_count', $direction);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('popularity')
                    ->label('Popularity')
                    ->sortable(query: function (Builder $query, string $direction) {
                        // Calculate popularity in the query
                        return $query->withCount(['contentReactions', 'userComments', 'userFavorite'])
                            ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 1) + (total_views * 0.5) + (user_favorite_count * 1) ' . $direction);
                    })
                    ->getStateUsing(function (ContentVideo $record) {
                        return $record->calculatePopularity();
                    }),
                BadgeColumn::make('status')->state(function (ContentVideo $record): string {
                    return match ($record->status) { 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', default => $record->status,
                    };
                })->colors([
                            'primary' => 'Pending',
                            'success' => 'Approved',
                            'danger' => 'Rejected',
                        ])
                    ->icons([
                        'heroicon-o-clock' => 'Pending',
                        'heroicon-o-check-circle' => 'Approved',
                        'heroicon-o-x-circle' => 'Rejected',
                    ])
                    ->description(description: fn(ContentVideo $record): string => $record->note ?? '')

                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                CategoryFilter::make('category'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                    ->action(function (ContentVideo $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                        $record->user->notify(new VideoStatus('approved', $record->title, ''));
                        Notification::make()
                            ->title('Content Approved')
                            ->body('The content has been approved!')
                            ->success()
                            ->sendToDatabase(users: auth()->user())
                            ->send();
                        return response()->json(['message' => 'The content has been approved!']);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                    ->form([
                        Textarea::make('note')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('Enter the reason for rejection...')
                            ->columnSpanFull(),
                    ])
                    ->action(function (ContentVideo $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'note' => $data['note'], // or whatever your notes column is named
                        ]);
                        Notification::make()
                            ->title('Content Rejected')
                            ->body('The content has been rejected. Reason: ' . $data['note'])
                            ->danger()
                            ->sendToDatabase(users: auth()->user())
                            ->send();
                    })
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

    public static function getWidgets(): array
    {
        return [
            ContentVideoOverview::class,
        ];
    }

    public static function getHourlyUploadedContent()
    {
        $newContent = ContentVideo::whereBetween('created_at', [now()->subHour(), now()])->count();
        if ($newContent > 0) {
            Notification::make()
                ->title('New Content Uploaded')
                ->body("There are {$newContent} new content video uploaded in the last hour.")
                ->sendToDatabase(auth()->user())
                ->send();
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentVideos::route('/'),
            'create' => Pages\CreateContentVideo::route('/create'),
            'edit' => Pages\EditContentVideo::route('/{record}/edit'),
        ];
    }

    public static function handleCategoryRelationships(ContentVideo $record, array $data): void
    {
        if (isset($data['categories'])) {
            // Delete existing category relationships
            $record->categoryContents()->delete();

            // Create new category relationships
            foreach ($data['categories'] as $categoryId) {
                $record->categoryContents()->create([
                    'category_id' => $categoryId,
                    'content_video_id' => $record->id
                ]);
            }
        }
    }
}
