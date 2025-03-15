<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
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
                    ->required(),
                Forms\Components\FileUpload::make('image_url')
                    ->disk('public')
                    ->image(),
                Forms\Components\FileUpload::make('thumbnail_url')
                    ->disk('public')
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
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_views')
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
                Action::make('published')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn(Article $record) => $record->status === 'draft' || $record->status === 'archived')
                    ->action(function (Article $record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);
                        return response()->json(['message' => 'Order approved and receipt sent to the user!']);
                    }),

                Action::make('archived')
                    ->label('Archive')
                    ->icon('heroicon-o-eye-slash')
                    ->visible(fn(Article $record) => $record->status === 'published')
                    ->action(fn(Article $record) => $record->update(['status' => 'archived'])),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
