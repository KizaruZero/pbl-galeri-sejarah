<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentVideoResource\Pages;
use App\Filament\Resources\ContentVideoResource\RelationManagers;
use App\Models\ContentVideo;
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
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\FileUpload::make('video_url')
                    ->directory('video_content')
                    ->disk('public')
                    ->maxSize(20000),
                Forms\Components\FileUpload::make('thumbnail')
                    ->directory('video_content')
                    ->disk('public')
                    ->maxSize(20000),
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
                Tables\Columns\ImageColumn::make('video_url')
                    ->disk('public'),
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->disk('public'),
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
                Tables\Columns\TextColumn::make('popularity')
                    ->limit(length: 20)
                    ->sortable()
                    ->searchable(),
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
                    ->sortable(),
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
                    ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                    ->action(function (ContentVideo $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                        ]);
                        return response()->json(['message' => 'Order approved and receipt sent to the user!']);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->visible(fn(ContentVideo $record) => $record->status === 'pending')
                    ->action(fn(ContentVideo $record) => $record->update(['status' => 'rejected'])),
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
            'index' => Pages\ListContentVideos::route('/'),
            'create' => Pages\CreateContentVideo::route('/create'),
            'edit' => Pages\EditContentVideo::route('/{record}/edit'),
        ];
    }
}
