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
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('video_url')
                    ->disk('public'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                //
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
