<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryContentResource\Pages;
use App\Filament\Resources\CategoryContentResource\RelationManagers;
use App\Models\CategoryContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryContentResource extends Resource
{
    protected static ?string $model = CategoryContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-pointing-in';
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->required(),
                Forms\Components\Select::make('content_photo_id')
                    ->relationship('contentPhoto', 'title')
                    ->nullable(),
                Forms\Components\Select::make('content_video_id')
                    ->relationship('contentVideo', 'title')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.category_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentPhoto.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentVideo.title')
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
            'index' => Pages\ListCategoryContents::route('/'),
            'create' => Pages\CreateCategoryContent::route('/create'),
            'edit' => Pages\EditCategoryContent::route('/{record}/edit'),
        ];
    }
}
