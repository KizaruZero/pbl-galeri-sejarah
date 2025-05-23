<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserReactionResource\Pages;
use App\Filament\Resources\UserReactionResource\RelationManagers;
use App\Models\UserReaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserReactionResource extends Resource
{
    protected static ?string $model = UserReaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Comments Reactions';
    protected static ?int $navigationSort = 5;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('comment_id')
                    ->relationship('comment', 'content')
                    ->required(),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment.content')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUserReactions::route('/'),
            'create' => Pages\CreateUserReaction::route('/create'),
            'edit' => Pages\EditUserReaction::route('/{record}/edit'),
        ];
    }
}
