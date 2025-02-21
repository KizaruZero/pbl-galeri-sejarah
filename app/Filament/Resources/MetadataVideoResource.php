<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetadataVideoResource\Pages;
use App\Filament\Resources\MetadataVideoResource\RelationManagers;
use App\Models\MetadataVideo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MetadataVideoResource extends Resource
{
    protected static ?string $model = MetadataVideo::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationGroup = 'Video Contents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_size')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('frame_rate')
                    ->maxLength(255),
                Forms\Components\TextInput::make('resolution')
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->maxLength(255),
                Forms\Components\TextInput::make('format_file')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tag')
                    ->maxLength(255),
                Forms\Components\TextInput::make('codec_video_audio')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('collection_date')
                    ->required(),
                Forms\Components\Select::make('content_video_id')
                    ->relationship('contentVideo', 'title')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('frame_rate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('resolution')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->searchable(),
                Tables\Columns\TextColumn::make('format_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codec_video_audio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('collection_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('content_video_id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListMetadataVideos::route('/'),
            'create' => Pages\CreateMetadataVideo::route('/create'),
            'edit' => Pages\EditMetadataVideo::route('/{record}/edit'),
        ];
    }
}
