<?php

namespace App\Filament\Resources\ContentVideoResource\Pages;

use App\Filament\Resources\ContentVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;


class ListContentVideos extends ListRecords
{
    protected static string $resource = ContentVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ContentVideoResource::getWidgets();
    }


    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'pending' => Tab::make()->query(fn($query) => $query->where('status', 'pending')),
            'approved' => Tab::make()->query(fn($query) => $query->where('status', 'approved')),
            'rejected' => Tab::make()->query(fn($query) => $query->where('status', 'rejected')),
        ];
    }
}
