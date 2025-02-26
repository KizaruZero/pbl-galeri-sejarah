<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentReactionResource\Pages;
use App\Filament\Resources\ContentReactionResource\RelationManagers;
use App\Models\ContentReaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentReactionResource extends Resource
{
    protected static ?string $model = ContentReaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 4;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('content_photo_id')
                    ->relationship('contentPhoto', 'title')
                    ->nullable(),
                Forms\Components\Select::make('content_video_id')
                    ->relationship('contentVideo', 'title')
                    ->nullable(),
                Forms\Components\Select::make('reaction_type_id')
                    ->relationship('reactionType', 'react_type')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentPhoto.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentVideo.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reactionType.react_type')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListContentReactions::route('/'),
            'create' => Pages\CreateContentReaction::route('/create'),
            'edit' => Pages\EditContentReaction::route('/{record}/edit'),
        ];
    }
}
