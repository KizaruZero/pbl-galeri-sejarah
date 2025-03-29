<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserCommentResource\Pages;
use App\Filament\Resources\UserCommentResource\RelationManagers;
use App\Filament\Resources\UserCommentResource\Widgets\UserCommentOverview;
use App\Models\UserComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Livewire\DatabaseNotifications;



class UserCommentResource extends Resource
{
    protected static ?string $model = UserComment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'Users';


    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('content_photo_id')
                    ->nullable()
                    ->relationship('contentPhoto', 'title'),
                Forms\Components\Select::make('content_video_id')
                    ->nullable()
                    ->relationship('contentVideo', 'title'),
                Forms\Components\Select::make('status')
                    ->options([
                        'published' => 'Published',
                        'hidden' => 'Hidden',
                    ])
                    ->default('published')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $recipient = auth()->user();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\TextColumn::make('contentPhoto.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contentVideo.title')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')->state(function (UserComment $record): string {
                    return match ($record->status) { 'published' => 'Published', 'hidden' => 'Hidden', default => $record->status,
                    };
                })->colors([
                            'success' => 'Published',
                            'danger' => 'Hidden',
                        ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Published',
                        'heroicon-o-x-circle' => 'Hidden',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('published')

                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(UserComment $record) => $record->status === 'hidden')
                    ->action(function (UserComment $record) {
                        $record->update([
                            'status' => 'published',
                        ]);
                        Notification::make()
                            ->title('Comment Published')
                            ->body('The comment has been successfully published.')
                            ->success()
                            ->sendToDatabase(auth()->user())
                            ->send();
                        return response()->json(['message' => 'Order approved and receipt sent to the user!']);
                    }),

                Action::make('hidden')
                    ->label('Hide')
                    ->icon('heroicon-o-eye-slash')
                    ->visible(fn(UserComment $record) => $record->status === 'published')
                    ->action(function (UserComment $record) {
                        $record->update(['status' => 'hidden']);
                        Notification::make()
                            ->title('Comment Hidden')
                            ->body('The comment has been successfully hidden from public view.')
                            ->danger()
                            ->sendToDatabase(auth()->user())
                            ->send();
                    }),
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
            'index' => Pages\ListUserComments::route('/'),
            'create' => Pages\CreateUserComment::route('/create'),
            'edit' => Pages\EditUserComment::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UserCommentOverview::class,
        ];
    }
}
