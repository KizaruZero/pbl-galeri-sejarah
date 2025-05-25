<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentPhotoResource\Pages;
use App\Filament\Resources\ContentPhotoResource\RelationManagers;
use App\Models\ContentPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Filters\CategoryFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use App\Models\UserComment;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Filament\Resources\ContentPhotoResource\Widgets\ContentPhotoOverview;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieMediaLibraryImageEditor;
use App\Notifications\PhotoStatus;






class ContentPhotoResource extends Resource
{
    protected static ?string $model = ContentPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Photo Contents';


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
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->directory('foto_content')
                    ->optimize('webp')
                    ->resize(50)
                    ->hint('Max file size: 10MB. Allowed formats: JPG, JPEG, PNG, HEIC.') // Helper text
                    ->hintIcon('heroicon-o-information-circle') // Optional icon
                    ->hintColor('warning') // Optional color (danger, warning, success, etc.)
                    ->disk('public')
                    ->maxSize(10000),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alt_text')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tag')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'approved') {
                            $set('approved_at', now()->toDateTimeString());
                        } else {
                            $set('approved_at', null);
                        }
                    }),
                Forms\Components\DateTimePicker::make('approved_at')
                    ->hidden()
                    ->dehydrated(true),
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
                Tables\Columns\TextColumn::make('description')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('categoryContents.category.category_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tag')
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
                        return $query->withCount(['contentReactions', 'userComments'])
                            ->orderByRaw('(content_reactions_count * 1) + (user_comments_count * 2) + (total_views * 0.5) ' . $direction);
                    })
                    ->getStateUsing(function (ContentPhoto $record) {
                        return $record->calculatePopularity();
                    })
                    ->searchable()
                    ->limit(20),
                BadgeColumn::make('status')->state(function (ContentPhoto $record): string {
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
                    ->description(fn(ContentPhoto $record): string => $record->note ?? '')
                    // notification

                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                CategoryFilter::make('category'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(ContentPhoto $record) => $record->status === 'pending')
                    ->action(function (ContentPhoto $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);

                        // Send notification to the user
                        $record->user->notify(new PhotoStatus('approved', $record->title, ''));

                        Notification::make()
                            ->title('Content Approved')
                            ->body('The content has been approved!')
                            ->success()
                            ->sendToDatabase(auth()->user())
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(ContentPhoto $record) => $record->status === 'pending')
                    ->form([
                        Textarea::make('note')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('Enter the reason for rejection...')
                            ->columnSpanFull(),
                    ])
                    ->action(function (ContentPhoto $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'note' => $data['note'],
                        ]);

                        // Send notification to the user
                        $record->user->notify(new PhotoStatus('rejected', $record->title, $data['note']));

                        Notification::make()
                            ->title('Content Rejected')
                            ->body('The content has been rejected.')
                            ->danger()
                            ->sendToDatabase(auth()->user())
                            ->send();
                    }),

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['contentReactions', 'userComments', 'userFavorite'])
            ->when(request()->has('sort'), function (Builder $query) {
                if (request('sort') === 'popularity') {
                    $query->orderByRaw('
                        (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                        (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 1 +
                        (content_photo.total_views) * 0.5 + (SELECT COUNT(*) FROM user_favorites WHERE user_favorites.content_photo_id = content_photo.id) * 1
                    ' . (request('direction') === 'desc' ? 'DESC' : 'ASC'));
                }
            })
            ->when(request()->has('search'), function (Builder $query) {
                $search = request('search');
                $query->whereRaw('
                    (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                    (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 1 +
                    (content_photo.total_views) * 0.5 + (SELECT COUNT(*) FROM user_favorites WHERE user_favorites.content_photo_id = content_photo.id) * 1
                    LIKE ?',
                    ["%{$search}%"]
                );
            });
    }

    public static function query(Builder $query): Builder
    {
        return $query->withCount('userComments', 'contentReactions');
    }

    public static function getWidgets(): array
    {
        return [
            ContentPhotoOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentPhotos::route('/'),
            'create' => Pages\CreateContentPhoto::route('/create'),
            'edit' => Pages\EditContentPhoto::route('/{record}/edit'),
        ];
    }

    public static function getHourlyUploadedContent()
    {
        $newContent = ContentPhoto::whereBetween('created_at', [now()->subHour(), now()])->count();
        if ($newContent > 0) {
            Notification::make()
                ->title('New Content Uploaded')
                ->body("There are {$newContent} new content photo uploaded in the last hour.")
                ->sendToDatabase(auth()->user())
                ->send();
        }
    }

    public static function handleCategoryRelationships(ContentPhoto $record, array $data): void
    {
        if (isset($data['categories'])) {
            // Delete existing category relationships
            $record->categoryContents()->delete();

            // Create new category relationships
            foreach ($data['categories'] as $categoryId) {
                $record->categoryContents()->create([
                    'category_id' => $categoryId,
                    'content_photo_id' => $record->id
                ]);
            }
        }
    }
}
