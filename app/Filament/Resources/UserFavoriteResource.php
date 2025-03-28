<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserFavoriteResource\Pages;
use App\Filament\Resources\UserFavoriteResource\RelationManagers;
use App\Models\UserFavorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserFavoriteResource extends Resource
{
    protected static ?string $model = UserFavorite::class;

    protected static ?string $navigationIcon = 'heroicon-s-star';
    protected static ?string $navigationGroup = 'Users';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('content_photo_id')
                    ->nullable()
                    ->relationship('contentPhoto', 'title'),
                Forms\Components\Select::make('content_video_id')
                    ->nullable()
                    ->relationship('contentVideo', 'title'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentPhoto.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentVideo.title')
                    ->searchable()
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
            'index' => Pages\ListUserFavorites::route('/'),
            'create' => Pages\CreateUserFavorite::route('/create'),
            'edit' => Pages\EditUserFavorite::route('/{record}/edit'),
        ];
    }
}
