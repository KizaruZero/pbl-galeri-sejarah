<?php

namespace App\Filament\Resources\ContentPhotoResource\Pages;

use App\Filament\Resources\ContentPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;


class ListContentPhotos extends ListRecords
{
    protected static string $resource = ContentPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return ContentPhotoResource::getWidgets();
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
