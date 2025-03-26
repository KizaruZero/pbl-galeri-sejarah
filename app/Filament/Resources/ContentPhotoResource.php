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
                Forms\Components\FileUpload::make('image_url')
                    ->image()
                    ->directory('foto_content')
                    ->optimize('webp')
                    ->resize(50)
                    ->disk('public')
                    ->maxSize(20000),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alt_text')
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
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
                        return response()->json(['message' => 'Order approved and receipt sent to the user!']);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(ContentPhoto $record) => $record->status === 'pending')
                    ->action(fn(ContentPhoto $record) => $record->update(['status' => 'rejected'])),
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
            ->with(['contentReactions', 'userComments'])
            ->when(request()->has('sort'), function (Builder $query) {
                if (request('sort') === 'popularity') {
                    $query->orderByRaw('
                        (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                        (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 2 +
                        (content_photo.total_views) * 0.5
                    ' . (request('direction') === 'desc' ? 'DESC' : 'ASC'));
                }
            })
            ->when(request()->has('search'), function (Builder $query) {
                $search = request('search');
                $query->whereRaw('
                    (SELECT COUNT(*) FROM content_reactions WHERE content_reactions.content_photo_id = content_photo.id) * 1 +
                    (SELECT COUNT(*) FROM user_comments WHERE user_comments.content_photo_id = content_photo.id) * 2 +
                    (content_photo.total_views) * 0.5
                    LIKE ?',
                    ["%{$search}%"]
                );
            });
    }

    public static function query(Builder $query): Builder
    {
        return $query->withCount('userComments', 'contentReactions');
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentPhotos::route('/'),
            'create' => Pages\CreateContentPhoto::route('/create'),
            'edit' => Pages\EditContentPhoto::route('/{record}/edit'),
        ];
    }
}
