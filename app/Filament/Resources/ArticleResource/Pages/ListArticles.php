<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;


class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ArticleResource::getWidgets();
    }


    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'published' => Tab::make()->query(fn($query) => $query->where('status', 'published')),
            'draft' => Tab::make()->query(fn($query) => $query->where('status', 'draft')),
            'archived' => Tab::make()->query(fn($query) => $query->where('status', 'hidden')),
        ];
    }
}
