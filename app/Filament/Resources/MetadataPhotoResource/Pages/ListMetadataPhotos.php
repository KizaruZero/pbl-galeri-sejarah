<?php

namespace App\Filament\Resources\MetadataPhotoResource\Pages;

use App\Filament\Resources\MetadataPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMetadataPhotos extends ListRecords
{
    protected static string $resource = MetadataPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
