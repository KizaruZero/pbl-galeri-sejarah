<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReactionResource\Pages;
use App\Filament\Resources\ReactionResource\RelationManagers;
use App\Models\Reaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReactionResource extends Resource
{
    protected static ?string $model = Reaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('react_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon')
                    ->image()   
                    ->disk('public')
                    ->directory('reactions')
                    ->required()
                    ->maxSize(1024),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('react_type')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('icon')
                    ->disk('public'),
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
            'index' => Pages\ListReactions::route('/'),
            'create' => Pages\CreateReaction::route('/create'),
            'edit' => Pages\EditReaction::route('/{record}/edit'),
        ];
    }
}
