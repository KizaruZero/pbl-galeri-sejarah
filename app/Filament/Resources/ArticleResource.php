<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Filament\Resources\ArticleResource\Widgets\ArticleOverview;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;



class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

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
                Forms\Components\MarkdownEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('user_id')
                    ->relationship('author', 'name')
                    ->default(Auth::id()) // Automatically set the default value to the logged-in user's ID
                    ->required()
                    ->dehydrated(), // Include the field in the form submission data
                Forms\Components\FileUpload::make('image_url')
                    ->disk('public')
                    ->optimize('webp')
                    ->resize(50)
                    ->image(),
                Forms\Components\FileUpload::make('thumbnail_url')
                    ->disk('public')
                    ->optimize('webp')
                    ->resize(50)
                    ->image(),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_url'),
                Tables\Columns\ImageColumn::make('thumbnail_url'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_views')
                    ->numeric()
                    ->sortable(),
                BadgeColumn::make('status')->state(function (Article $record): string {
                    return match ($record->status) { 'draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived', default => $record->status,
                    };
                })->colors([
                            'primary' => 'Draft',
                            'success' => 'Published',
                            'danger' => 'Archived',
                        ])
                    ->icons([
                        'heroicon-o-clock' => 'Draft',
                        'heroicon-o-check-circle' => 'Published',
                        'heroicon-o-x-circle' => 'Archived',
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
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $userRoles = auth()->user()->roles->pluck('name');

                if (!$userRoles->contains('super_admin')) {
                    return $query->where('user_id', auth()->id());
                }
            })
            ->actions([
                Action::make('published')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(Article $record) => $record->status === 'draft' || $record->status === 'archived')
                    ->action(function (Article $record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);
                        Notification::make()
                            ->title('Article Published')
                            ->body('The article has been published!')
                            ->success()
                            ->sendToDatabase(auth()->user())
                            ->send();
                        return response()->json(['message' => 'Order approved and receipt sent to the user!']);
                    }),
                Action::make('archived')
                    ->label('Archive')
                    ->icon('heroicon-o-eye-slash')
                    ->visible(fn(Article $record) => $record->status === 'published')
                    ->action(function (Article $record) {
                        $record->update(['status' => 'archived']);

                        Notification::make()
                            ->title('Article Archived')
                            ->body('The article has been archived!')
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

    public static function getWidgets(): array
    {
        return [
            ArticleOverview::class,
        ];
    }

    public static function getHourlyUploadedContent()
    {
        $newContent = Article::whereBetween('created_at', [now()->subHour(), now()])->count();
        if ($newContent > 0) {
            Notification::make()
                ->title('New Content Uploaded')
                ->body("There are {$newContent} new article uploaded in the last hour.")
                ->sendToDatabase(auth()->user())
                ->send();
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
