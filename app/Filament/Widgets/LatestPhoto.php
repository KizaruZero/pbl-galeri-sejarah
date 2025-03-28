<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ContentPhoto;

class LatestPhoto extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContentPhoto::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(length: 20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public'),
            ]);
    }
}
