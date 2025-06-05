<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ContentVideo;

class TrendingVideos extends BaseWidget
{
    protected static ?string $heading = 'Trending Videos';
    protected static ?int $sort = 3;
    protected $defaultPaginationPageOption = 5;
    protected $isPaginationEnabled = true;

    public function getTableQuery(): Builder
    {
        return ContentVideo::query()
            ->where('status', 'approved')
            ->orderBy('total_views', 'desc')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->limit(30),
            \Filament\Tables\Columns\TextColumn::make('user.name')
                ->sortable(),
            \Filament\Tables\Columns\TextColumn::make('total_views')
                ->label('Views')
                ->sortable(),
            \Filament\Tables\Columns\TextColumn::make('approved_at')
                ->label('Approved Date')
                ->dateTime()
                ->sortable(),
            \Filament\Tables\Columns\ImageColumn::make('thumbnail')
                ->label('Thumbnail')
                ->circular(),
        ];
    }
}