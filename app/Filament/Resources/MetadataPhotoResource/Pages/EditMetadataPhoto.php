<?php

namespace App\Filament\Resources\MetadataPhotoResource\Pages;

use App\Filament\Resources\MetadataPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetadataPhoto extends EditRecord
{
    protected static string $resource = MetadataPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
